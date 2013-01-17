<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmSimpleConfigStorages\RcmSimpleConfigStorage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmSimpleConfigStorage\Controller;
use \RcmSimpleConfigStorage\StorageEngine\PhpFileNewInstanceRepo,
    \RcmSimpleConfigStorage\StorageEngine\JsonDoctrineRepo;
/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmSimpleConfigStorages\RcmSimpleConfigStorage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class SimpleConfigStorageController
    extends \Zend\Mvc\Controller\AbstractActionController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * @var string Tells function renderInstance() which template to use.
     */
    protected $template;

    /**
     * @var string Tells function renderDefaultInstance() where the default data
     * for a new instance of this plugin is
     */
    protected $newInstanceConfigPath;

    /**
     * Stores configs for active instances
     * Now there is only one type of repo but this may become swap-able later
     * @var \RcmSimpleConfigStorage\StorageEngine\JsonDoctrineRepo
     */
    protected $configRepo;

    /**
     * Stores default configs for new instances
     * @var \RcmSimpleConfigStorage\StorageEngine\PhpFileNewInstanceRepo
     */
    protected $newInstanceConfigRepo;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $template = null,
        $newInstanceConfigPath = null
    ) {
        $this->configRepo = new JsonDoctrineRepo($entityMgr);
        $this->template = $template;

        if(!$newInstanceConfigPath){
            $reflection = new \ReflectionClass(get_class($this));
            $newInstanceConfigPath= dirname($reflection->getFileName())
                . '/../../../config/default.content.json';
        }

        $newName= dirname($newInstanceConfigPath). '/newInstanceConfig.php';
        $this->newInstanceConfigRepo = new PhpFileNewInstanceRepo($newName);

        $this->newInstanceConfigPath = $newInstanceConfigPath;

//        if(file_exists($newInstanceConfigPath)){
//            $json = json_decode(json_encode($this->newInstanceConfigRepo->getInstanceConfig()),true);
//            $php = "<?php\nreturn ".var_export($json, true).';';
//            $newName = dirname($this->newInstanceConfigPath). '/newInstanceConfig.php';
//            file_put_contents($newName, $php);
//            unlink($newInstanceConfigPath);
//        }
    }

    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId){
        $view = new \Zend\View\Model\ViewModel(
            array('data' => $this->configRepo->getInstanceConfig($instanceId))
        );
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderDefaultInstance(){
        $view = new \Zend\View\Model\ViewModel(
            array('data' => $this->newInstanceConfigRepo->getInstanceConfig())
        );
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Saves a plugin instance to persistent storage
     *
     * @param string $instanceId plugin instance id
     * @param array  $configData       posted data to be saved
     *
     * @return null
     */
    function saveInstance($instanceId, $configData){
        $this->configRepo->createInstanceConfig($instanceId, $configData);
    }

    /**
     * Deletes a plugin instance from persistent storage
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    function deleteInstance($instanceId){
        $this->configRepo->deleteInstanceConfig($instanceId);
    }
    
    /**
     * Get entity content as JSON. This is called by the editor javascript of
     * some plugins. Urls look like
     * '/rcm-plugin-admin-proxy/rcm-plugin-name/223/admin-data'
     *
     * @param integer $instanceId instance id
     *
     * @return null
     */
    function dataAdminAjaxAction($instanceId)
    {
        exit(json_encode($this->configRepo->getInstanceConfig($instanceId)));
    }

    function getInstanceConfig($instanceId){
        if ($instanceId < 0) {
            return new \RcmSimpleConfigStorage\Entity\JsonInstanceConfig(
                null, $this->newInstanceConfigRepo->getInstanceConfig()
            );
        } else {
            return $this->configRepo->readConfig($instanceId);
        }
    }

    function dataAndDefaultDataAdminAjaxAction($instanceId){
        exit(
            json_encode(
                array(
                    'data'=>$this->configRepo->getInstanceConfig($instanceId),
                    'defaultData'=>$this->newInstanceConfigRepo->getInstanceConfig()
                )
            )
        );
    }

    /**
     * Shortcut method to get post
     *
     * @return \Zend\Stdlib\Parameters
     */
    function getPost()
    {
        return $this->getEvent()->getRequest()->getPost();
    }
}
