<?php
namespace RcmSimpleConfigStorage\StorageEngine;
class PhpFileNewInstanceRepo
{
    protected $newInstanceConfigPath;

    function __construct($newInstanceConfigPath){
        $this->newInstanceConfigPath = $newInstanceConfigPath;
    }

    function getInstanceConfig(){
        //TODO RETURN ARRAY INSTEAD OF OBJECT
        return json_decode(json_encode(include($this->newInstanceConfigPath)));
    }
}
