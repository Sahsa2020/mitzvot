<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EntityConflictException extends Exception
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
     * EntityConflictException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 409, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Set exception code
     * 
     * @param int $code
     */
    public function setCode($code = 409)
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
        $this->message = strlen(trim($message)) ? $message : "Entity has been conflicted.";
    }
}
