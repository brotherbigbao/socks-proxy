<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/8/31
 * Time: 11:56
 */

namespace service;

class Os
{
    const OSX = 'Darwin';
    const LINUX = 'Linux';
    const NOT_SUPPORT_OS = 'not_support_os';

    public static function getType()
    {
        if (!in_array(PHP_OS, [self::OSX, self::LINUX])) {
            return self::NOT_SUPPORT_OS;
        }
        return PHP_OS;
    }

    public static function getPath()
    {
        return getenv('HOME').'/.socks-proxy/';
    }

    public static function getBinPath()
    {
        return getenv('HOME').'/.socks-proxy/bin/socks-proxy';
    }

    public static function getConfigPath()
    {
        return getenv('HOME').'/.socks-proxy/config.php';
    }
}