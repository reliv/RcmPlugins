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
use \RcmSimpleConfigStorage\StorageEngine\NewInstanceRepo,
    \RcmSimpleConfigStorage\StorageEngine\DoctrineSerializedRepo;
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
     * Stores configs for active instances
     * Now there is only one type of repo but this may become swap-able later
     * @var \RcmSimpleConfigStorage\StorageEngine\DoctrineSerializedRepo
     */
    protected $configRepo;

    protected $defaultInstanceConfig;

    protected $pluginName;

    protected $pluginNameLowerCaseDash;

    protected $pluginDirectory;

    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    /**
     * Caches instance configs to speed up multiple calls to getInstanceConfig()
     * @var array
     */
    private $instanceConfigs=array();

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $config,
        $pluginDirectory = null
    ) {
        $this->entityMgr = $entityMgr;
        $this->configRepo = new DoctrineSerializedRepo($entityMgr);

        if(!$pluginDirectory){
            //Allow auto path detection for controllers that extend this class
            $reflection = new \ReflectionClass(get_class($this));
            $pluginDirectory =
                realpath(dirname($reflection->getFileName()) . '/../../../');
        }

        $this->pluginDirectory=$pluginDirectory;
        $this->pluginName=basename($pluginDirectory);
        $this->pluginNameLowerCaseDash=$this->camelToHyphens($this->pluginName);
        $this->template = $this->pluginNameLowerCaseDash.'/plugin';

        $this->defaultInstanceConfig=$config['rcmPlugin'][$this->pluginName]
        ['defaultInstanceConfig'];

    }

    /**
     * Allows core to properly pass the request to this plugin controller
     * @param $request
     */
    function setRequest($request){
        $this->request = $request;
    }

    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId, $extraViewVariables = array()){
        $view = new \Zend\View\Model\ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'ic' => $this->getInstanceConfig($instanceId)
                ),
                $extraViewVariables
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    public function getNewInstanceConfig(){
        return $this->defaultInstanceConfig;
    }

    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderDefaultInstance($instanceId, $extraViewVariables = array()){
        $view = new \Zend\View\Model\ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'ic' => $this->getNewInstanceConfig($instanceId)
                ),
                $extraViewVariables
            )
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
    function instanceConfigAdminAjaxAction($instanceId)
    {
        exit(json_encode($this->getInstanceConfig($instanceId)));
    }

    function getInstanceConfig($instanceId){
        if ($instanceId < 0) {
            return $this->getNewInstanceConfig();
        } else {
            if(!isset($this->instanceConfigs[$instanceId])){
                $this->instanceConfigs[$instanceId]
                    = $this->getMergedInstanceConfig($instanceId);
            }
            return $this->instanceConfigs[$instanceId];
        }
    }

    /**
     * merges the instance config with the new instance config so that default
     * values are used when the db instance config doesn't yet have them after
     * new functionality is added
     * @param $instanceId
     * @return array
     */
    function getMergedInstanceConfig($instanceId){
        return self::mergeConfigArrays(
            $this->getNewInstanceConfig(),
            $this->configRepo->getInstanceConfig($instanceId)
        );
    }

    static function mergeConfigArrays($default,$changes){
        foreach($changes as $key => $value){
            if(is_array($value)){
                if(isset($value['0'])){
                    /*
                     * Numeric arrays ignore default values because o
                     * of the "more in default
                     * that on production" issue
                     */
                    $default[$key]=$changes[$key];
                }else{
                    $default[$key]=self::mergeConfigArrays(
                        $default[$key],
                        $changes[$key]
                    );
                }
            }else{
                $default[$key]=$changes[$key];
            }
        }
        return $default;
    }

    function instanceConfigAndNewInstanceConfigAdminAjaxAction($instanceId){
        exit(
            json_encode(
                array(
                    'instanceConfig'=>$this->getInstanceConfig($instanceId),
                    'defaultInstanceConfig'=>$this->getNewInstanceConfig()
                )
            )
        );
    }

    /**
     * Redirects to https version of current url is not already https
     */
    function ensureHttps(){
        if(!$this->isHttps()){
            $this->redirectHttps();
        }
    }

    /**
     * Redirect to the current page but on https
     */
    function redirectHttps(){
        $url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        header('Location: '.$url);
        exit();
    }

    /**
     * returns if https or not
     * @return bool
     */
    function isHttps()
    {
        return (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : null) == 'on';
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

    /**
     * Deletes all keys in this session container. Is two-foreach process due to
     * weirdness with zf2 sessions
     *
     * @return null
     */
    function destroySession()
    {
        $keysToKill = array();
        foreach ($this->session->getIterator() as $key => $val) {
            $keysToKill[] = $key;
        }
        foreach ($keysToKill as $key) {
            $this->session->offsetUnset($key);
        }
    }

    public function postIsForThisPlugin($pluginName){
        $request=$this->getRequest();
        if($request->isPost()){
            $post=$request->getPost();
            if(
                isset($post['rcmPluginName'])
                &&$post['rcmPluginName']==$pluginName
            ){
                return true;
            }
        }
        return false;
    }

    /*
 * Converts camelCase to lower-case-hyphens
 *
 * @param string $value the value to convert
 *
 * @return string
 */
    function camelToHyphens($value)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $value));
    }
}
