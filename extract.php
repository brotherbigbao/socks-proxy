<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/8/31
 * Time: 10:57
 */

$phar = new Phar('socks-proxy.phar');
$phar->extractTo('tmp');