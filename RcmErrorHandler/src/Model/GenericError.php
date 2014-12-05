<?php

namespace RcmErrorHandler\Model;

/**
 * Class GenericError
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
class GenericError
{
    /**
     * string DEFAULT_TYPE
     */
    const DEFAULT_TYPE = 'Unknown';

    /**
     * @var string $message
     */
    protected $message = '';

    /**
     * @var int $code
     */
    protected $code = 0;

    /**
     * @var int $severity
     */
    protected $severity = E_ERROR;

    /**
     * @var string $file
     */
    protected $file = '';

    /**
     * @var int $line
     */
    protected $line = 0;

    /**
     * @var string
     */
    protected $type = GenericError::DEFAULT_TYPE;

    /**
     * @var null | GenericError
     */
    protected $previous = null;

    /**
     * @var array | null $trace
     */
    protected $trace = null;

    /**
     * @param              $message
     * @param int          $code
     * @param int          $severity
     * @param string       $file
     * @param int          $line
     * @param string       $type
     * @param GenericError $previous
     * @param null         $trace
     */
    public function __construct(
        $message,
        $code = 0,
        $severity = E_ERROR,
        $file = __FILE__,
        $line = __LINE__,
        $type = GenericError::DEFAULT_TYPE,
        GenericError $previous = null,
        $trace = null
    ) {

        // @todo Create setters for this logic
        if (!is_string($type)) {
            $type = GenericError::DEFAULT_TYPE;
        }

        $this->message = $message;
        $this->code = $code;
        $this->severity = $severity;
        $this->file = $file;
        $this->line = $line;
        $this->type = $type;
        $this->previous = $previous;
        $this->trace = $trace;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * getCode
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getSeverity
     *
     * @return int
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * getFile
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * getLine
     *
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * getPrevious
     *
     * @return GenericError
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * getFirst
     *
     * @return GenericError
     */
    public function getFirst()
    {
        $errors = $this->getErrors($this);

        return $errors[0];
    }

    /**
     * getErrors
     *
     * @param GenericError $error
     * @param array        $errors
     *
     * @return array
     */
    public function getErrors(GenericError $error, $errors = [])
    {
        array_unshift($errors, $error);

        $prevError = $error->getPrevious();

        if (!$prevError) {

            return $errors;
        }

        return $this->getErrors($prevError, $errors);
    }

    /**
     * getTrace
     *
     * @param int $options
     * @param int $limit
     *
     * @return array
     */
    public function getTrace($options = 3, $limit = 0)
    {
        if(empty($this->trace)) {
            $this->trace = debug_backtrace(
                $options,
                //DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS,
                $limit
            );
        }

        return $this->trace;
    }
} 