<?php

namespace RcmErrorHandler\Model;

/**
 * Class Config
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Config
{

    /**
     * @var array $configArray
     */
    protected $configArray = [];

    /**
     * __construct
     *
     * @param array $configArray
     */
    public function __construct($configArray)
    {
        if (is_array($configArray)) {
            $this->configArray = $configArray;
        }
    }

    /**
     * get
     *
     * @param string $key
     * @param null   $default
     *
     * @return null
     */
    public function get($key, $default = null)
    {
        if (isset($this->configArray[$key])) {

            return $this->configArray[$key];
        }

        return $default;
    }

    /**
     * getAll
     *
     * @return array
     */
    public function getAll()
    {
        return $this->configArray;
    }
} 