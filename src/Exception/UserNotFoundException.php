<?php
// Package\Uc\Exception/UserNotFoundException


namespace Package\Uc\Exception;



use Throwable;

class UserNotFoundException extends UcException
{
    public function __construct($message = "", $code = Errcode::ERR_USER_NOTFOUND, Throwable $previous = null)
    {
        parent::__construct($message.' user not found', $code, $previous);
    }
}