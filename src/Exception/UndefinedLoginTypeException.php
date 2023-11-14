<?php

namespace Package\Uc\Exception;


use Throwable;

class UndefinedLoginTypeException extends UcException
{
    public function __construct(string $loginType, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Undefined login type $loginType", $code, $previous);
    }
}