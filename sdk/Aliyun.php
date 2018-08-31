<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/8/31
 * Time: 11:18
 */
include_once __DIR__ . '/aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php';

use Ecs\Request\V20140526 as Ecs;
use League\CLImate\CLImate;

class Aliyun{
    private $config = [];
    private $argv = [];
    private $cmd = null;
    private $client = null;
    public function __construct(array $config, array $argv)
    {
        if(!$config || !is_array($config)){
            throw new Exception('Not found config.');
        }
        $this->config = $config;
        $this->argv = $argv;
        $this->cmd = new CLImate();
        $iClientProfile = DefaultProfile::getProfile($config['regionId'], $config['key'], $config['secret']);
        $this->client = new DefaultAcsClient($iClientProfile);
    }

    public function init(){
        if(count($this->argv) < 2){
            $action = 'list';
        } else {
            $action = $this->argv[1];
        }

        switch ($action) {
            case 'list' : $this->ecsList(); break;
            case 'create' : $this->create(); break;
            case 'stop' : $this->stop(); break;
            case 'destroy' : $this->destroy(); break;
            default : $this->cmd->red('Need action.');
        }
    }

    private function ecsList($return = false){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $this->client->getAcsResponse($request);

        if($response->TotalCount < 1) {
            $this->cmd->green('Not found instance.');
            return [];
        }

        $output = [];
        foreach($response->Instances->Instance as $ecs){
            $output[] = [
                'InstanceId' => $ecs->InstanceId,
                'Type' => $ecs->InstanceType,
                'ZoneId' => $ecs->ZoneId,
                'Memory' => $ecs->Memory,
                'Cpu' => $ecs->Cpu,
                'Ip' => isset($ecs->PublicIpAddress->IpAddress[0]) ? $ecs->PublicIpAddress->IpAddress[0] : '',
                'ChargeType' => $ecs->InstanceChargeType,
                'ExpiredTime'=> $ecs->ExpiredTime,
                'Status' => $ecs->Status
            ];
        }
        if($return){
            return $output;
        }else{
            $this->cmd->table($output);
        }
    }

    private function create(){
        $ecsList = $this->ecsList(true);
        if($ecsList){
            $this->cmd->table($ecsList);
            $this->cmd->red("Instance exists, please destroy.");
            return;
        }

        $this->cmd->out('Start create instance ...');
        $request = new Ecs\CreateInstanceRequest();
        $request->setImageId($this->config['imageId']);
        $request->setInstanceType($this->config['instanceType']);
        $request->setInternetChargeType('PayByTraffic');
        $request->setInternetMaxBandwidthOut(5);
        $request->setPassword($this->config['password']);
        $request->setInstanceChargeType('PostPaid');
        $request->setSystemDiskCategory('cloud_efficiency');
        $request->setMethod("GET");
        $response = $this->client->getAcsResponse($request);
        if($response instanceof stdClass && $response->InstanceId){
            $this->cmd->out(sprintf("New instance: %s", $response->InstanceId));
            $this->cmd->out("Start allocate public ip address ... ");
            $ip = $this->allocatePublicIpAddress($response->InstanceId);
            if(!$ip){
                return;
            }
            $this->start($response->InstanceId);
            $this->ecsList();
        }else{
            print_r($response);
            /*
            stdClass Object
            (
                [InstanceId] => i-j6c0f2omjvqw5gjk83al
                [RequestId] => 3AAC2F2E-FC72-475F-9CA7-37F660408752
            )
            */
        }
    }

    private function allocatePublicIpAddress($instanceId){
        $request = new Ecs\AllocatePublicIpAddressRequest();
        $request->setInstanceId($instanceId);
        $response = $this->client->getAcsResponse($request);
        if($response->IpAddress){
            $this->updateShell($response->IpAddress);
            $this->cmd->out(sprintf('Successful public ip address: %s, have update aliyun-socks5-listen, Please use ./aliyun-socks-listen start listen', $response->IpAddress));
            return $response->IpAddress;
        }else{
            print_r($response);
            return '';
        }
    }

    private function start($instanceId){
        $request = new Ecs\StartInstanceRequest();
        $request->setInstanceId($instanceId);
        $response = $this->client->getAcsResponse($request);
        if($response->RequestId){
            $this->cmd->out('Successful start.');
        }else{
            print_r($response);
        }
    }

    private function updateShell($ipAddress) {
        $str = <<<EOF
#!/bin/bash
vpnpid=$(ps -ef | grep "ssh -NT" | grep -v grep | awk '{print $2}')
if test \$vpnpid
then
echo "发现已有端口转发: \$vpnpid."
echo "现在开始kill."
kill \$vpnpid
else
echo "没有发现已存在ssh端口"
fi
echo "开始创建新的连接"
ssh -NTf -D 10000 root@$ipAddress
ps -ef | grep ssh | grep -v grep
EOF;
        file_put_contents(APP_PATH . '/aliyun-socks5-listen', $str);

    }

    private function stop(){
        $ecsList = $this->ecsList(true);
        if(empty($ecsList)){
            $this->cmd->green('Not found any ecs.');
            return;
        }
        $this->cmd->table($ecsList);

        $request = new Ecs\StopInstanceRequest();
        foreach($ecsList as $ecs) {
            if ($ecs['Status'] == 'Running') {
                $this->cmd->out(sprintf("Start stop %s ...", $ecs['InstanceId']));
                $request->setInstanceId($ecs['InstanceId']);
                $response = $this->client->getAcsResponse($request);
                if($response instanceof stdClass && $response->RequestId){
                    $this->cmd->out("Successful stop.");
                }
            }
        }
    }

    private function destroy(){
        $ecsList = $this->ecsList(true);
        if(empty($ecsList)){
            $this->cmd->green('Not found any ecs.');
            return;
        }
        $this->cmd->table($ecsList);

        foreach($ecsList as $ecs) {
            if($ecs['Status'] != 'Stopped'){
                $this->cmd->red(sprintf("%s not stopped, ignore.", $ecs['InstanceId']));
            }else{
                $request = new Ecs\DeleteInstanceRequest();
                $request->setInstanceId($ecs['InstanceId']);
                $response = $this->client->getAcsResponse($request);
                if($response->RequestId){
                    $this->cmd->out(sprintf('Successful delete instance : %s', $ecs['InstanceId']));
                }else{
                    print_r($response);
                }
            }
        }
    }

    //$request = new Ecs\DescribeRegionsRequest();
    //$request->setMethod("GET");
    //$response = $client->getAcsResponse($request);
    //print_r($response);exit;
}