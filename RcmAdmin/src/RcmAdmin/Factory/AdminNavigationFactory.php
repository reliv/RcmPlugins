<?php
namespace RcmAdmin\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;

/**
 * Default navigation factory.
 */
class AdminNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'RcmAdminMenu';
    }
}