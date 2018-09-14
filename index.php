<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 17-1-24
 * Time: 下午2:10
 */
use service\Bootstrap;

include_once __DIR__ . '/vendor/autoload.php';

define('APP_PATH', __DIR__);

Bootstrap::start($argv);