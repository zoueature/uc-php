<?php
// Package\Uc\Impl/VerifyCodeImpl


namespace Package\Uc\Impl;



use Exception;

class VerifyCodeImpl
{
    // $cacheConn 缓存连接
    private $cacheConn;

    const VERIFY_CODE_TYPE_REGISTER = 1;
    const VERIFY_CODE_TYPE_FORGOT_PASSWORD = 2;
    const VERIFY_CODE_TYPE_LOGIN = 3;

    const VERIFY_CODE_TYPE_CACHE_KEY_TEMPLATE = [
        self::VERIFY_CODE_TYPE_REGISTER => 'register_verify_code_%',
        self::VERIFY_CODE_TYPE_FORGOT_PASSWORD => 'forgot_password_verify_code_%',
        self::VERIFY_CODE_TYPE_LOGIN => 'login_verify_code_%',
    ];

    const VERIFY_CODE_TTL = 300; // 有效期300s


    public function __construct($cacheConn)
    {
        $this->cacheConn = $cacheConn;
    }

    /**
     * 生成随机验证码
     * @param string $identify
     * @param int $verifyCodeType
     * @param int $length
     * @return string
     * @throws Exception
     */
    public function generateVerifyCode(string $identify, int $verifyCodeType, int $length = 6) :string
    {
        $template = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for($i = 0; $i < $length; $i ++) {
            $seed = time();
            srand($seed);
            $rand = rand(0, 35);
            $code = $template[$rand];
        }
        $cacheKeyTemplate = self::VERIFY_CODE_TYPE_CACHE_KEY_TEMPLATE[$verifyCodeType] ?? '';
        if (empty($cacheKeyTemplate)) {
            throw new Exception("undefined verify code type");
        }
        $this->cacheConn->set(sprintf($cacheKeyTemplate, $identify), $code, self::VERIFY_CODE_TTL);
        return $code;
    }

    /**
     * 校验验证码
     * @param string $identify
     * @param int $verifyCodeType
     * @param string $code
     * @return bool
     */
    public function verifyCode(string $identify, int $verifyCodeType, string $code) :bool
    {
        if (empty($code)) {
            return false;
        }
        $cacheKeyTemplate = self::VERIFY_CODE_TYPE_CACHE_KEY_TEMPLATE[$verifyCodeType] ?? '';
        if (empty($cacheKeyTemplate)) {
            return false;
        }
        $trueCode = $this->cacheConn->get(sprintf($cacheKeyTemplate, $identify));
        return $trueCode == $code;
    }
}