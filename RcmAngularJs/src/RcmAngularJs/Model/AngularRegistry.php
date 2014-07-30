<?php
/**
 * AngularRegistry.php
 *
 * AngularRegistry
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAngularJs\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAngularJs\Model;


/**
 * Class AngularRegistry
 *
 * AngularRegistry
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAngularJs\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class AngularRegistry
{

    /**
     * @var array $modules
     */
    protected static $modules = array();

    /**
     * setModules
     *
     * @param array $modules
     *
     * @return void
     */
    public static function setModules($modules)
    {
        foreach($modules as $module){

            self::setModule($module);
        }
    }

    /**
     * setModule
     *
     * @param string $module
     *
     * @return void
     */
    public static function setModule($module)
    {
        if(!in_array ($module, self::$modules)){

            self::$modules[] = $module;
        }
    }

    /**
     * getModules @todo
     *
     * @return array
     */
    public static function getModules()
    {
        return self::$modules;
    }

    /**
     * getTemplate
     *
     * @return string

    public static function getTemplate()
    {
        $modules = json_encode(self::$modules);
        $template = "// @todo";

        return $template;
    }
     */
} 