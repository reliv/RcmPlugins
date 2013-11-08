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
 * @package   RcmDJPluginStorages\RcmDJPluginStorage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmDJPluginStorage\Controller;

use Doctrine\ORM\EntityManager;
use \RcmDJPluginStorage\StorageEngine\NewInstanceRepo,
    \RcmDJPluginStorage\StorageEngine\DoctrineSerializedRepo,
    \Zend\Http\PhpEnvironment\Request,
    \RcmDJPluginStorage\Entity\InstanceConfig;
use Zend\View\Model\ViewModel;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmDJPluginStorages\RcmDJPluginStorage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class BasePluginController
    extends \Zend\Mvc\Controller\AbstractActionController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * @var string Tells public function renderInstance() which template to use.
     */
    protected $template;

    protected $defaultInstanceConfig;

    protected $pluginName;

    protected $pluginNameLowerCaseDash;

    protected $pluginDirectory;

    protected $config;

    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    /**
     * Caches instance configs to speed up multiple calls to getDbInstanceConfig()
     * @var array
     */
    private $instanceConfigs = array();


    protected $jsonContentRepo;

    public function __construct(
        EntityManager $entityMgr,
        $config,
        $pluginDirectory = null
    ) {
        $this->entityMgr = $entityMgr;
        $this->jsonContentRepo = $this->entityMgr
            ->getRepository('RcmDJPluginStorage\Entity\InstanceConfig');

        if (!$pluginDirectory) {
            //Allow auto path detection for controllers that extend this class
            $reflection = new \ReflectionClass(get_class($this));
            $pluginDirectory =
                realpath(dirname($reflection->getFileName()) . '/../../../');
        }

        $this->pluginDirectory = $pluginDirectory;
        $this->pluginName = basename($pluginDirectory);
        $this->pluginNameLowerCaseDash = $this->camelToHyphens($this->pluginName);
        $this->template = $this->pluginNameLowerCaseDash . '/plugin';

        $this->defaultInstanceConfig = $config['rcmPlugin'][$this->pluginName]
        ['defaultInstanceConfig'];

        $this->config = $config;

    }

    /**
     * Allows core to properly pass the request to this plugin controller
     * @param $request
     */
    public function setRequest(Request $request)
    {
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
    public function renderInstance($instanceId, $extraViewVariables = array())
    {
        $view = new ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'ic' => $this->getInstanceConfig($instanceId),
                    'config' => $this->config,
                ),
                $extraViewVariables
            )
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
    public function renderDefaultInstance($instanceId, $extraViewVariables = array())
    {
        $view = new \Zend\View\Model\ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'ic' => $this->getDefaultInstanceConfig($instanceId),
                    'config' => $this->config
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
     * @param array $configData posted data to be saved
     *
     * @return null
     */
    public function saveInstance($instanceId, $configData)
    {
        $entity = new InstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($configData);
        $this->entityMgr->persist($entity);
        $this->entityMgr->flush($entity);
    }

    /**
     * Deletes a plugin instance from persistent storage
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    public function deleteInstance($instanceId)
    {
        $entity = $this->readEntityFromDb($instanceId);
        $this->entityMgr->remove($entity);
        $this->entityMgr->flush();
    }

    public function getDefaultInstanceConfig()
    {
        return $this->defaultInstanceConfig;
    }

    /**
     * Get entity content as JSON. This is called by the editor javascript of
     * some plugins. Urls look like
     * '/rcm-plugin-admin-proxy/rcm-plugin-name/223/admin-data'
     *
     *
     * @param integer $instanceId instance id
     *
     * @return null
     */
    public function instanceConfigAdminAjaxAction($instanceId)
    {
        exit(json_encode($this->getInstanceConfig($instanceId)));
    }

    /**
     * merges the instance config with the new instance config so that default
     * values are used when the db instance config doesn't yet have them after
     * new public functionality is added
     * @param $instanceId
     * @return array
     */
    public function getInstanceConfig($instanceId)
    {
        //Instance configs less than 0 are default instanc configs
        if ($instanceId < 0) {

            return $this->getDefaultInstanceConfig();

        } else {

            //Check to see if we already have a cached instance config
            if (!isset($this->instanceConfigs[$instanceId])) {

                //Grab from the db or use blank array if not there
                $instanceConfig = $this->readEntityFromDb($instanceId)->getConfig();
                if (!is_array($instanceConfig)) {
                    $instanceConfig = array();
                }

                //Merge the default and db instance configs. Db overwrites.
                $instanceConfig = self::mergeConfigArrays(
                    $this->getDefaultInstanceConfig(),
                    $instanceConfig
                );

                //Cache merged instance configs in this object
                $this->instanceConfigs[$instanceId] = $instanceConfig;
            }
            return $this->instanceConfigs[$instanceId];

        }
    }

    static public function mergeConfigArrays($default, $changes)
    {

        if (empty($default)) {
            return $changes;
        }

        if (empty($changes)) {
            return $default;
        }

        foreach ($changes as $key => $value) {
            if (is_array($value)) {
                if (isset($value['0'])) {
                    /*
                     * Numeric arrays ignore default values because of the
                     * "more in default that on production" issue
                     */
                    $default[$key] = $changes[$key];
                } else {
                    if (isset($default[$key])) {
                        $default[$key] = self::mergeConfigArrays(
                            $default[$key],
                            $changes[$key]
                        );
                    } else {
                        $default[$key] = $changes[$key];
                    }
                }
            } else {
                $default[$key] = $changes[$key];
            }
        }
        return $default;
    }

    public function instanceConfigAndNewInstanceConfigAdminAjaxAction($instanceId)
    {
        exit(
        json_encode(
            array(
                'instanceConfig' => $this->getInstanceConfig($instanceId),
                'defaultInstanceConfig' => $this->getDefaultInstanceConfig()
            )
        )
        );
    }

    /**
     * Redirects to https version of current url is not already https
     */
    public function ensureHttps()
    {
        if (!$this->isHttps()) {
            $this->redirectHttps();
        }
    }

    /**
     * Redirect to the current page but on https
     */
    public function redirectHttps()
    {
        $url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url);
        exit();
    }

    /**
     * returns if https or not
     * @return bool
     */
    public function isHttps()
    {
        return (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : null) == 'on';
    }

    public function postIsForThisPlugin($pluginName)
    {
        return $this->getRequest()->getPost('rcmPluginName') == $pluginName;
    }

    /**
     * Returns the JSON content for a given plugin instance Id
     * @param $instanceId
     *
     * @return \RcmDJPluginStorage\Entity\InstanceConfig|null
     * @throws \RcmDJPluginStorage\Exception\PluginDataNotFoundException
     */
    public function readEntityFromDb($instanceId)
    {
        $entity = $this->jsonContentRepo->findOneByInstanceId($instanceId);
        if (!$entity) {
            $entity = new InstanceConfig();
        }
        return $entity;
    }


    /*
 * Converts camelCase to lower-case-hyphens
 *
 * @param string $value the value to convert
 *
 * @return string
 */
    public function camelToHyphens($value)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $value));
    }
}
