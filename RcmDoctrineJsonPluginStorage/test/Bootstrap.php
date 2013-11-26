<?php

namespace RcmDoctrineJsonPluginStorageTest;

require __DIR__ . '/../../../Rcm/test/RcmTest/Base/RcmBootstrap.php';

use \RcmTest\Base\RcmBootstrap;

class Bootstrap extends RcmBootstrap
{

}

/** Array is zend special application config */
Bootstrap::init(include 'application.test.config.php');