<?php
// Package\Uc/Clinet


namespace Package\Uc;

use Exception;
use Package\Uc\Common\LoginType;
use Package\Uc\Component\Convert;
use Package\Uc\Component\Jwt;
use Package\Uc\DataStruct\UserInfo;
use Package\Uc\DataStruct\UserInfoWithJwt;
use Package\Uc\Exception\TokenExpireException;
use Package\Uc\Exception\UndefinedLoginTypeException;
use Package\Uc\Impl\Internal\EmailLoginImpl;
use Package\Uc\Impl\Internal\MobileLoginImpl;
use Package\Uc\Interf\InternalLogin;
use Package\Uc\Model\User;
use think\db\ConnectionInterface;


class InternalClient
{
    use Jwt, Convert;


    // cacheConn 为缓存连接, 不做类型限制， 实现以下方法即可
    // get(string key)
    // set(string key, string value, int ttl)
    // delete(string key)
    private $cacheConn;

    /** @var InternalLogin $loginClient */
    private InternalLogin $loginClient;

    /**
     * @throws Exception
     */
    public function __construct(string $loginType, $cacheConn)
    {
        $this->cacheConn   = $cacheConn;
        $this->loginClient = $this->getLoginClientByLoginType($loginType);
    }

    /**
     * @param string $loginType
     * @return InternalLogin
     * @throws Exception
     */
    private function getLoginClientByLoginType(string $loginType): InternalLogin
    {
        $clientClass = LoginType::INTERNAL_LOGIN_TYPE[$loginType] ?? null;
        if (empty($clientClass)) {
            throw new UndefinedLoginTypeException($loginType);
        }
        return new $clientClass($this->cacheConn);
    }

    /**
     * sendSmsCode 发送验证码
     * @param int $codeType
     * @param string $identify
     * @return mixed
     */
    public function sendSmsCode(int $codeType, string $identify)
    {
        $this->loginClient->checkIdentifyFormat($identify);
        return $this->loginClient->sendSmsCode($codeType, $identify);
    }

    /**
     * register 注册用户
     * @param string $identify
     * @param string $password
     * @param string $verifyCode
     * @param array $userInfo
     * @return UserInfo
     */
    public function register(string $identify, string $password, string $verifyCode, array $userInfo): UserInfo
    {
        $this->loginClient->checkIdentifyFormat($identify);
        return $this->loginClient->register($identify, $password, $verifyCode, $userInfo);
    }

    /**
     * login 邮箱/手机号登录
     * @param string $identify
     * @param string $password
     * @return UserInfoWithJwt
     */
    public function login(string $identify, string $password): UserInfoWithJwt
    {
        $this->loginClient->checkIdentifyFormat($identify);
        $userInfo = $this->loginClient->login($identify, $password);
        $jwt      = $this->encodeJwt($userInfo);
        return new UserInfoWithJwt($userInfo, $jwt);
    }

    /**
     * @throws Exception|TokenExpireException
     */
    public function verifyToken(string $jwtToken): UserInfo
    {
        $info = $this->decodeJwt($jwtToken);
        return $this->objectToUserInfo($info);
    }

    /**
     * login 用户名密码登录
     * @param string $username
     * @param string $password
     * @return UserInfo
     */
    public function loginByUsername(string $username, string $password): UserInfo
    {
        return $this->loginClient->loginByUsername($username, $password);
    }

    /**
     * changePassword 忘记密码密码修改
     * @param string $identify
     * @param string $verifyCode
     * @param string $password
     * @return mixed
     */
    public function changePassword(string $identify, string $verifyCode, string $password)
    {
        return $this->loginClient->changePassword($identify, $verifyCode, $password);
    }

    /**
     * changePasswordByOldPassword 根据旧密码修改密码
     * @param string $identify
     * @param string $oldPassword
     * @param string $password
     * @return mixed
     */
    public function changePasswordByOldPassword(string $identify, string $oldPassword, string $password)
    {
        return $this->loginClient->changePasswordByOldPassword($identify, $oldPassword, $password);
    }

}