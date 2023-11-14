<?php

namespace Package\Uc\Exception;


use Throwable;

class LackDataException extends UcException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Lack of data : ' . $message, $code, $previous);
    }
}