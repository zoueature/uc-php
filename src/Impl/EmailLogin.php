<?php
// Package\Uc\Login/EmailLogin


namespace Package\Uc\Impl;


use Package\Uc\Exception\VerifyCodeNotMatchException;
use Package\Uc\Interf\InternalLogin;
use Package\Uc\LoginType;

class EmailLogin extends \Package\Uc\Impl\InternalLogin implements InternalLogin
{


    protected $loginType = LoginType::EMAIL;

    public function sendSmsCode(int $codeType, string $identify)
    {
        $verifyCode = $this->verifyCodeCli->generateVerifyCode($identify, $codeType);
        // TODO 发送邮件
        echo $verifyCode;
    }






}