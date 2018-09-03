<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/9/3
 * Time: 16:59
 */

namespace service;

use League\CLImate\CLImate;

class Bootstrap
{
    public static function start()
    {
        if (Os::getType() == Os::NOT_SUPPORT_OS) {
            echo "现在仅支持Linux、OSX系统！\n";
            exit(0);
        }
        self::initDir();
        self::checkConfigFile();
    }

    protected static function initDir()
    {
        if (!file_exists(Os::getPath())) {
            mkdir(Os::getPath(), 0777, true);
        }
    }

    protected static function checkConfigFile()
    {
        if (!file_exists(Os::getConfigPath())) {
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

        $configFileContent = sprintf(\Aliyun::getConfigTemplate(), $key, $secret, $password);
        file_put_contents(Os::getConfigPath(), $configFileContent);
    }
}