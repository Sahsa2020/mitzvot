<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class HttpBadRequestException extends Exception
{
    /**
     * Exception code
     * 
     * @var int
     */
    protected $code;

    /**
     * Exception message
     * 
     * @var string
     */
    protected $message;

    /**
     * HttpBadRequestException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Set exception code
     * 
     * @param int $code
     */
    public function setCode($code = 400)
    {
        $this->code = $code;
    }

    /**
     * Set exception message
     * 
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = strlen(trim($message)) ? $message : "Your have made a bad request.";
    }
}
