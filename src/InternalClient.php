<?php
// Package\Uc/Clinet


namespace Package\Uc;

use Exception;
use Package\Uc\DataStruct\UserInfo;
use Package\Uc\Impl\EmailLogin;
use Package\Uc\Impl\MobileLogin;
use Package\Uc\Interf\InternalLogin;
use think\db\Connection;


class InternalClient
{

    /** @var Connection $dbConn */
    private $dbConn;

    private $cacheConn;

    /** @var InternalLogin $loginClient */
    private $loginClient;

    /**
     * @throws Exception
     */
    public function __construct(string $loginType, Connection $conn, $cacheConn)
    {
        $this->dbConn = $conn;
        $this->cacheConn = $cacheConn;
        $this->loginClient = $this->getLoginClientByLoginType($loginType);
    }

    /**
     * @param string $loginType
     * @return InternalLogin
     * @throws Exception
     */
    private function getLoginClientByLoginType(string $loginType) :InternalLogin
    {
        $loginClient = null;
        switch ($loginType) {
            case LoginType::EMAIL:
                $loginClient = new EmailLogin($this->dbConn, $this->cacheConn);
                break;
            case LoginType::MOBILE:
                $loginClient = new MobileLogin($this->dbConn, $this->cacheConn);
                break;
            default:
                throw new Exception("undefined login type " . $loginType);
        }
        return $loginClient;
    }

    // sendSmsCode 发送验证码
    public function sendSmsCode(int $codeType, string $identify) {
        return $this->loginClient->sendSmsCode($codeType, $identify);
    }

    // register 注册用户
    public function register(string $identify, string $password, string $verifyCode, array $userInfo) :UserInfo
    {
        return  $this->loginClient->register($identify, $password, $verifyCode, $userInfo);
    }

    // login 用户登录
    public function login(string $identify, string $password) :UserInfo
    {
        return $this->loginClient->login($identify, $password);
    }

    // login 用户登录
    public function loginByUsername(string $username, string $password) :UserInfo
    {
        return $this->loginClient->loginByUsername($username, $password);
    }

    // changePassword 忘记密码密码修改
    public function changePassword(string $identify, string $verifyCode, string $password)
    {
        return $this->loginClient->changePassword($identify, $verifyCode, $password);
    }

    // changePasswordByOldPassword 根据旧密码修改密码
    public function changePasswordByOldPassword(string $identify, string $oldPassword, string $password)
    {
        return $this->loginClient->changePasswordByOldPassword($identify, $oldPassword, $password);
    }

}