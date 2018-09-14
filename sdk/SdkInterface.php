<?php
/**
 * Created by PhpStorm.
 * User: liuyibao
 * Date: 2018/9/14
 * Time: 09:24
 */
namespace sdk;

interface SdkInterface
{
    public function ecsList();

    public function create();

    public function stop();

    public function destroy();

    public static function getConfigTemplate();
}