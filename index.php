<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 17-1-24
 * Time: 下午2:10
 */

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/sdk/Aliyun.php';

define('APP_PATH', __DIR__);

//echo PHP_OS, "\n"; exit;

$config = include __DIR__ . '/config/config.php';
$aliyun = new Aliyun($config, $argv);
$aliyun->init();