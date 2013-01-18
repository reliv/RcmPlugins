<?php
namespace RcmSimpleConfigStorage\StorageEngine;
class PhpFileNewInstanceRepo
{
    protected $newInstanceConfigPath;

    function __construct($newInstanceConfigPath){
        $this->newInstanceConfigPath = $newInstanceConfigPath;
    }

    function getInstanceConfig(){
        return include $this->newInstanceConfigPath;
    }
}
