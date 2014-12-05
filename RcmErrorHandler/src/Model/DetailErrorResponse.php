<?php
 /**
 * Error.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Model;


 /**
 * Class Error
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class DetailErrorResponse {

    public $code = 0;

    public $message = '';

    public $file = '';

    public $line = 0;

    public $backtrace = [];

    public function __construct(
        $message = 'Internal Server Error',
        $code = 0,
        $file = '',
        $line = 0,
        $backtrace = []
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->backtrace = $backtrace;
    }
} 