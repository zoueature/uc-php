<?php
// Package\Uc\Exception/Errcode


namespace Package\Uc\Exception;


class Errcode
{
    const ERR_USER_NOTFOUND = 40001;
    const ERR_PASSWORD_NOT_MATCH = 40002;
    const ERR_VERIFY_CODE_NOT_MATCH = 40003;
    const ERR_USER_ALREADY_EXISTS = 40004;
    const ERR_PASSWORD_EQUAL_OLD = 40005;
    const ERR_TOKEN_EXPIRE = 40006;
    const ERR_UNDEFINED_LOGIN_TYPE = 40007;
}