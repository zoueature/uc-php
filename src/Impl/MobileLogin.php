<?php
// Package\Uc\Login/MobileLogin


namespace Package\Uc\Impl;


use Exception;
use Package\Uc\Interf\InternalLogin;
use Package\Uc\LoginType;

class MobileLogin extends \Package\Uc\Impl\InternalLogin implements InternalLogin
{

    protected $loginType = LoginType::MOBILE;

    /**
     * @throws Exception
     */
    public function sendSmsCode(int $codeType, string $identify)
    {
        $verifyCode = $this->verifyCodeCli->generateVerifyCode($identify, $codeType);
        // TODO 发送短信
    }
}