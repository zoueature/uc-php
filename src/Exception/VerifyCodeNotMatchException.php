<?php
// Package\Uc\Exception/VerifCOdeNotMatchException


namespace Package\Uc\Exception;


use Throwable;

class VerifyCodeNotMatchException extends \Exception
{
    public function __construct($message = "verify code not match", $code = Errcode::ERR_VERIFY_CODE_NOT_MATCH, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}