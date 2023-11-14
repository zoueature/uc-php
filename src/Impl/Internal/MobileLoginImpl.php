<?php
// Package\Uc\Login/MobileLogin


namespace Package\Uc\Impl\Internal;


use Exception;
use Package\Uc\Common\LoginType;
use Package\Uc\Exception\ErrIdentifyFormatException;
use Package\Uc\Impl\InternalLoginImpl;
use Package\Uc\Interf\InternalLogin;

class MobileLoginImpl extends InternalLoginImpl implements InternalLogin
{

    protected string $loginType = LoginType::MOBILE;

    /**
     * @throws Exception
     */
    public function sendSmsCode(int $codeType, string $identify): void
    {
        $verifyCode = $this->verifyCodeCli->generateVerifyCode($identify, $codeType);
        // TODO 发送短信
    }

    /**
     * 检查是否手机号， 考虑多国家问题， 纯数字即可
     * @param string $identify
     * @return bool
     * @throws ErrIdentifyFormatException
     */
    public function checkIdentifyFormat(string $identify): bool
    {
        $ok = boolval(preg_match('/^[0-9]+$/', $identify));
        if (!$ok) {
            throw new ErrIdentifyFormatException('Mobile');
        }
        return $ok;
    }
}