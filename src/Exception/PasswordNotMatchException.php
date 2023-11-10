<?php
// Package\Uc\Exception/PasswordNotMatchException


namespace Package\Uc\Exception;



use Throwable;

class PasswordNotMatchException extends \Exception
{
    public function __construct($message = "password not match", $code = Errcode::ERR_PASSWORD_NOT_MATCH, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}