<?php
/**
 * FormatBase.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Format
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Format;

use RcmErrorHandler\Model\Config;
use RcmErrorHandler\Model\GenericError;


/**
 * Class FormatBase
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Format
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class FormatBase implements FormatInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @todo Support templates for output
     *
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        if (!($config instanceof Config)) {
            $config = new Config(array());
        }

        $this->config = $config;
    }

    /**
     * getString
     *
     * @param GenericError $error
     *
     * @return mixed
     */
    public function getString(
        GenericError $error
    ) {
        $output = $error->getType() . ': ' .
            $error->getMessage() . ' ' .
            'File: ' . $error->getFile() . ' ' .
            'Line: ' . $error->getLine() . "\n";
        return $output;
    }

    /**
     * getBasicString - no details exposed - public friendly
     *
     * @param GenericError $error
     *
     * @return mixed
     */
    public function getBasicString(
        GenericError $error
    ) {
        return 'An error occurred during execution; please try again later.';
    }

    /**
     * getTraceString
     *
     * @param GenericError $error
     * @param int          $options
     * @param int          $limit
     *
     * @return mixed|string
     */
    public function getTraceString(
        GenericError $error,
        $options = 3,
        $limit = 0
    ) {

        $backtrace = $error->getTrace($options);

        $output = '';

        foreach ($backtrace as $i => $call) {

            if ($i > ($limit - 1) && $limit !== 0) {
                $output .= '.';
                continue;
            }

            $file = (isset($call['file']) ? $call['file'] : '?');
            $line = (isset($call['line']) ? $call['line'] : '?');
            $class = (isset($call['class']) ? $call['class'] : '');
            $function = (isset($call['function']) ? $call['function'] : '');
            $type = (isset($call['type']) ? $call['type'] : '');

            $args = (isset($call['args']) ? (array)$call['args'] : array());

            $object = '';
            if (!empty($class)) {

                $object = $class . $type;
            }

            foreach ($args as $key => $arg) {

                if (is_object($arg)) {
                    $args[$key] = get_class($arg);
                } else {
                    if (is_array($arg)) {
                        $args[$key] = 'array';
                    } else {
                        $args[$key] = (string)$arg;
                    }
                }
            }

            $argStr = implode(', ', $args);

            $output .= '# ' . $i . ' ' .
                ': ' . $object . $function . '(' . $argStr . ') ' .
                'File: ' . $file . ': ' . $line . "\n";
        }

        return $output;
    }

    /**
     * displayString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayString(GenericError $error, \Zend\Mvc\MvcEvent $event)
    {
        echo $this->getString($error);
    }

    /**
     * displayBasicString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayBasicString(GenericError $error, \Zend\Mvc\MvcEvent $event)
    {
        echo $this->getBasicString($error);
    }

    /**
     * displayTraceString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayTraceString(GenericError $error, \Zend\Mvc\MvcEvent $event)
    {
        echo $this->getTraceString($error);
    }
} 