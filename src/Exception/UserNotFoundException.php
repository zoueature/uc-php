<?php
// Package\Uc\Exception/UserNotFoundException


namespace Package\Uc\Exception;



use Throwable;

class UserNotFoundException extends \Exception
{
    public function __construct($message = "user not found", $code = Errcode::ERR_USER_NOTFOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}