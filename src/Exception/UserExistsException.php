<?php
// Package\Uc\Exception/UserExistsException


namespace Package\Uc\Exception;


use Throwable;

class UserExistsException extends UcException
{
    public function __construct($message = "user already exists", $code = Errcode::ERR_USER_ALREADY_EXISTS, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}