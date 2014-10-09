<?php

namespace RcmErrorHandler\Model;


/**
 * Class BasicErrorResponse
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class BasicErrorResponse{

    public $code = 0;

    public $message = '';

    public function __construct($message, $code = 0) {
        $this->code = $code;
        $this->message = $message;
    }
} 