<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/9/3
 * Time: 16:59
 */

namespace service;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\Stdin;

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
            $cmd = new CLImate();
            $account = new Stdin();
            $cmd->input("请输入账号:", $account);
        }
    }

    public static function createConfigFile()
    {

    }


}