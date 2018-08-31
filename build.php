<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/8/31
 * Time: 10:06
 */

$fileName = 'socks-proxy.phar';
if (file_exists($fileName)) {
    unlink($fileName);
}

$phar = new Phar(__DIR__ . '/' . $fileName);
//$phar->buildFromDirectory(__DIR__, '/^(?!(.*git|.*idea))(.*)$/i');
$phar->buildFromDirectory(__DIR__, '/^(?!(.*\.git|.*\.idea))(.*)$/i');
$phar->delete('build.php');
$phar->delete('extract.php');
$phar->setDefaultStub('index.php');

echo "Finished!\n";