<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/9/3
 * Time: 16:59
 */

namespace service;

use League\CLImate\CLImate;
use AliyunSdk;

include_once APP_PATH . '/sdk/AliyunSdk.php';

class Bootstrap
{
    public static function start(array $argv)
    {
        if (Os::getType() == Os::NOT_SUPPORT_OS) {
            echo "现在仅支持Linux、OSX系统！\n";
            exit(0);
        }
        self::checkDir();
        self::checkConfigFile();

        $configArray = include Os::getConfigFilePath();
        $cloudService = new AliyunSdk($configArray, Os::getBinFilePath());
        if(count($argv) < 2){
            $cmd = new CLImate();
            $cmd->green('请使用参数 list|create|stop|destroy|config');
            exit;
        } else {
            $action = $argv[1];
        }
        switch ($action) {
            case 'list' : $cloudService->ecsList(); break;
            case 'create' : $cloudService->create(); break;
            case 'stop' : $cloudService->stop(); break;
            case 'destroy' : $cloudService->destroy(); break;
            case 'config' : self::createConfigFile(); break;
            case 'listen' : self::listen(); break;
            default :
                $cmd = new CLImate();
                $cmd->red('参数错误，请输入: list|create|stop|destroy|config');
        }
    }

    protected static function checkDir()
    {
        if (!file_exists(Os::getPath())) {
            mkdir(Os::getPath(), 0777, true);
        }
    }

    protected static function checkConfigFile()
    {
        if (!file_exists(Os::getConfigFilePath())) {
            self::createConfigFile();
        }
    }

    public static function createConfigFile()
    {
        $cmd = new CLImate();

        $input = $cmd->input("请输入key:");
        $key = $input->prompt();

        $input = $cmd->input("请输入secret:");
        $secret = $input->prompt();

        $input = $cmd->input("请设定服务器ROOT账户密码:");
        $password = $input->prompt();

        echo $key,"\n";
        echo $secret,"\n";
        echo $password,"\n";

        $configFileContent = sprintf(AliyunSdk::getConfigTemplate(), $key, $secret, $password);
        file_put_contents(Os::getConfigFilePath(), $configFileContent);
    }

    protected static function listen()
    {
        system(Os::getBinFilePath());
    }
}