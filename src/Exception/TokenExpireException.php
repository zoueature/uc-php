<?php
// Package\Uc\Exception/TokenExpireException


namespace Package\Uc\Exception;



use Throwable;

class TokenExpireException extends \Exception
{
    public function __construct($message = "Jwt token expire", $code = Errcode::ERR_TOKEN_EXPIRE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}