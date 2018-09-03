<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 17-1-24
 * Time: 下午2:10
 */
use service\Os;

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/sdk/Aliyun.php';

define('APP_PATH', __DIR__);

if (Os::getType() == Os::NOT_SUPPORT_OS) {
    echo "现在仅支持Linux、OSX系统！\n";
    exit(0);
}

$config = include __DIR__ . '/config/config.php';
$aliyun = new Aliyun($config, $argv);
$aliyun->init();