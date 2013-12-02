<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 10/23/13
 * Time: 8:54 AM
 */

namespace RcmBrightcovePlayer\Controller;

use RcmDoctrineJsonPluginStorage\Controller\SimpleConfigStorageController;

class PluginController extends SimpleConfigStorageController
{

    /** @var  \Rcm\Entity\User */

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $config
    )
    {
        parent::__construct($entityMgr, $config);
    }

    function renderInstance($instanceId)
    {

        return parent::renderInstance(
            $instanceId,
            array(

            )
        );
    }

    function renderDefaultInstance($instanceId){
        return parent::renderDefaultInstance(
            $instanceId,
            array(

            )
        );
    }

}