<?php


namespace RcmMessage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 * Class MessageListController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MessageListController extends AbstractActionController
{
    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * indexAction
     *
     * @return array
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}