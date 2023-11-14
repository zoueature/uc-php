<?php

namespace Package\Uc\Exception;


use Throwable;

class ErrIdentifyFormatException extends UcException
{
    public function __construct($message = "", $code = Errcode::ERR_FORMAT, Throwable $previous = null)
    {
        parent::__construct($message . ' format error', $code, $previous);
    }
}