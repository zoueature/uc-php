<?php
// Package\Uc\Exception/PasswordEqualOldException


namespace Package\Uc\Exception;



use Throwable;

class PasswordEqualOldException extends \Exception
{
    public function __construct($message = "The new password is equal to old one", $code = Errcode::ERR_PASSWORD_EQUAL_OLD, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}