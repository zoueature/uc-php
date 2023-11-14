<?php
// Package\Uc\Impl/InternalLogin


namespace Package\Uc\Impl;


use Package\Uc\Component\Convert;
use Package\Uc\Component\PasswordEncrypt;
use Package\Uc\Config\Config;
use Package\Uc\Config\ConfigOption;
use Package\Uc\DataStruct\UserInfo;
use Package\Uc\Exception\PasswordEqualOldException;
use Package\Uc\Exception\PasswordNotMatchException;
use Package\Uc\Exception\UcException;
use Package\Uc\Exception\UserExistsException;
use Package\Uc\Exception\UserNotFoundException;
use Package\Uc\Exception\VerifyCodeNotMatchException;
use Package\Uc\Model\User;
use think\db\exception\DbException;

class InternalLoginImpl
{
    use PasswordEncrypt, Convert;

    protected User $userModel;

    /** @var VerifyCodeImpl $verifyCodeCli */
    protected VerifyCodeImpl $verifyCodeCli;

    /** @var string $loginType */
    protected string $loginType;

    public function __construct($cacheConn)
    {
        $class               = Config::getConfig(ConfigOption::USER_MODEL_CLASS);
        $this->userModel     = new $class();
        $this->verifyCodeCli = new VerifyCodeImpl($cacheConn);
    }

    public function getLoginType(): string
    {
        return $this->loginType;
    }

    /**
     * 根据标识获取用户信息
     * @param string $identify
     * @return User
     * @throws UserNotFoundException
     */
    protected function getUserByIdentify(string $identify): User
    {
        return $this->userModel->getUserByIdentify($this->loginType, $identify);
    }

    /**
     * 根据用户名获取用户信息
     * @param string $username
     * @return User
     * @throws UserNotFoundException
     */
    protected function getUserByUsername(string $username): User
    {
        return $this->userModel->getUserByUsername($username);
    }

    /**
     * 根据标识获取用户信息并验证密码登录
     * @param string $identify
     * @param string $password
     * @return UserInfo
     * @throws PasswordNotMatchException|UserNotFoundException
     */
    public function login(string $identify, string $password): UserInfo
    {
        $encryptPassword = $this->encryptPassword($password);
        $user            = $this->getUserByIdentify($identify);
        if ($user->password != $encryptPassword) {
            throw new PasswordNotMatchException();
        }
        return $user->toUserInfo();
    }

    /**
     * 根据用户名获取用户信息
     * @param string $username
     * @param string $password
     * @return UserInfo
     * @throws PasswordNotMatchException
     * @throws UserNotFoundException
     */
    public function loginByUsername(string $username, string $password): UserInfo
    {
        $encryptPassword = $this->encryptPassword($password);

        $user = $this->getUserByUsername($username);
        if ($user->password != $encryptPassword) {
            throw new PasswordNotMatchException();
        }
        return $user->toUserInfo();
    }

    /**
     * @param string $identify
     * @param string $password
     * @param string $verifyCode
     * @param array $userInfo
     * @return UserInfo
     * @throws UserExistsException
     * @throws VerifyCodeNotMatchException|UcException
     */
    public function register(string $identify, string $password, string $verifyCode, array $userInfo): UserInfo
    {
        if (!$this->verifyCodeCli->verifyCode($identify, VerifyCodeImpl::VERIFY_CODE_TYPE_REGISTER, $verifyCode)) {
            throw new VerifyCodeNotMatchException();
        }
        // 没有设置用户名则用标识替代
        $userInfo['username'] = $userInfo['username'] ?? $identify;
        try {
            // 检查当前标识是否已经被注册
            $user = $this->getUserByIdentify($identify);
            if (!empty($user)) {
                throw new UserExistsException();
            }
            // 检查用户名是否被注册
            $this->getUserByUsername($userInfo['username']);
            throw new UserExistsException();
        } catch (UserNotFoundException $exception) {
            return $this->createUser($identify, $password, $userInfo);
        }
    }

    /**
     * 在DB中创建用户
     * @param string $identify
     * @param string $password
     * @param array $userInfo
     * @return UserInfo
     * @throws UcException
     */
    private function createUser(string $identify, string $password, array $userInfo): UserInfo
    {
        $model             = clone $this->userModel;
        $model->login_type = $this->loginType;
        $model->identify   = $identify;
        $model->password   = $this->encryptPassword($password);
        $model->username   = $userInfo['username'] ?? '';
        $model->nickname   = $userInfo['nickname'] ?? '';
        $model->avatar     = $userInfo['avatar'] ?? '';
        $model->gender     = $userInfo['gender'] ?? 0;
        $ok                = $model->save();
        if (!$ok) {
            throw new UcException('create user error');
        }
        return $model->toUserInfo();
    }

    /**
     * 修改密码
     * @param string $identify
     * @param string $verifyCode
     * @param string $password
     * @return void
     * @throws VerifyCodeNotMatchException|UserNotFoundException|DbException
     * @throws PasswordEqualOldException
     */
    public function changePassword(string $identify, string $verifyCode, string $password): bool
    {
        if (!$this->verifyCodeCli->verifyCode($identify, VerifyCodeImpl::VERIFY_CODE_TYPE_FORGOT_PASSWORD, $verifyCode)) {
            throw new VerifyCodeNotMatchException();
        }
        $user            = $this->getUserByIdentify($identify);
        $encryptPassword = $this->encryptPassword($password);
        if ($encryptPassword == $user->password) {
            throw new PasswordEqualOldException();
        }
        $user->password = $encryptPassword;
        return $user->save();
    }

    /**
     * 根据旧密码修改密码
     * @param string $identify
     * @param string $oldPassword
     * @param string $password
     * @return bool
     * @throws UserNotFoundException|PasswordEqualOldException|PasswordNotMatchException
     */
    public function changePasswordByOldPassword(string $identify, string $oldPassword, string $password): bool
    {
        $user               = $this->getUserByIdentify($identify);
        $encryptOldPassword = $this->encryptPassword($oldPassword);
        $encryptNewPassword = $this->encryptPassword($password);
        if ($user->password != $encryptOldPassword) {
            throw new PasswordNotMatchException();
        }
        if ($encryptOldPassword == $encryptNewPassword) {
            throw new PasswordEqualOldException();
        }
        $user->password = $encryptNewPassword;
        return $user->save();
    }
}