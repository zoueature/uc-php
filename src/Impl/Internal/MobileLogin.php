<?php
// Package\Uc\Login/MobileLogin


namespace Package\Uc\Impl\Internal;


use Exception;
use Package\Uc\Common\LoginType;
use Package\Uc\Interf\InternalLogin;

class MobileLogin extends \Package\Uc\Impl\InternalLogin implements InternalLogin
{

    protected string $loginType = LoginType::MOBILE;

    /**
     * @throws Exception
     */
    public function sendSmsCode(int $codeType, string $identify)
    {
        $verifyCode = $this->verifyCodeCli->generateVerifyCode($identify, $codeType);
        // TODO 发送短信
    }
}