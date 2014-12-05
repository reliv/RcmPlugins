<?php

namespace RcmErrorHandler\Format;

use RcmErrorHandler\Model\GenericError;

/**
 * Class FormatDefault
 *
 * Default format is very simple and is mainly for testing
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
class FormatDefault extends FormatBase
{

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
        $output = '
                <table class="xdebug-error"
                       dir="ltr"
                       border="1"
                       cellspacing="0"
                       cellpadding="1">
                    <tbody>
                    <tr>
                        <th align="left" bgcolor="#f57900" colspan="5">
                            <div>
                                <span style="background-color: #cc0000; color: #fce94f; font-size: x-large;">( ! )</span>
                                <span>' .
            $error->getType() . ': ' . $error->getMessage() . '
                                </span>
                            </div>
                            <div>File: ' . $error->getFile() . '</div>
                            <div>Line: <i>' . $error->getLine() . '</i></div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            ' . $this->getTraceString($error) . '
                        </td>
                    </tr>
                    </tbody>
                </table>
                ';
        return $output;
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
        $ret = [];

        $output = '
            <table dir="ltr"
                   border="1"
                   cellspacing="0"
                   cellpadding="1">
                <tr>
                    <th align="left" bgcolor="#e9b96e" colspan="5">Call Stack</th>
                </tr>
                <tr>
                    <th align="center" bgcolor="#eeeeec">#</th>
                    <th align="left" bgcolor="#eeeeec">Function</th>
                    <th align="left" bgcolor="#eeeeec">Location</th>
                </tr>
        ';

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

            $args = (isset($call['args']) ? (array)$call['args'] : []);

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


            $output .= '
                    <tr>
                        <td bgcolor="#eeeeec" align="center">' . $i . '</td>
                        <td bgcolor="#eeeeec">' .
                $object . $function . '(' . $argStr . ')' . '
                        </td>
                        <td title="' . $file . '"bgcolor="#eeeeec">' .
                $file . '<b>:</b>' . $line . '
                        </td>
                    </tr>
                ';
        }

        $output .= '
                    </tbody>
                </table>
            ';

        return $output;
    }
} 