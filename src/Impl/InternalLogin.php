<?php
// Package\Uc\Impl/InternalLogin


namespace Package\Uc\Impl;


use Package\Uc\Component\PasswordEncrypt;
use Package\Uc\Constant;
use Package\Uc\DataStruct\UserInfo;
use Package\Uc\Exception\PasswordEqualOldException;
use Package\Uc\Exception\PasswordNotMatchException;
use Package\Uc\Exception\UserExistsException;
use Package\Uc\Exception\UserNotFoundException;
use Package\Uc\Exception\VerifyCodeNotMatchException;
use think\db\Connection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class InternalLogin
{
    use PasswordEncrypt;

    /** @var Connection $dbConn */
    protected $dbConn;

    /** @var VerifyCodeImpl $verifyCodeCli */
    protected $verifyCodeCli;

    /** @var string $loginType */
    protected $loginType;

    public function __construct(Connection $connection, $cacheConn)
    {
        $this->dbConn = $connection;
        $this->verifyCodeCli = new VerifyCodeImpl($cacheConn);
    }

    public function getLoginType(): string
    {
        return $this->loginType;
    }

    /**
     * 根据标识获取用户信息
     * @param string $identify
     * @return array
     * @throws DbException
     * @throws UserNotFoundException
     */
    protected function getUserByIdentify(string $identify) :array
    {
        try {
            $user = $this->dbConn->newQuery()->table(Constant::getUserDbTable())
                ->where('login_type', '=', $this->loginType)
                ->where('identify', '=', $identify)
                ->where('active', '=', Constant::DATA_STATUS_NORMAL)
                ->find()
                ->toArray();
        } catch (DataNotFoundException|ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * 根据用户名获取用户信息
     * @param string $username
     * @return array
     * @throws DbException
     * @throws UserNotFoundException
     */
    protected function getUserByUsername(string $username) :array
    {
        try {
            $user = $this->dbConn->newQuery()->table(Constant::getUserDbTable())
                ->where('username', '=', $username)
                ->where('active', '=', Constant::DATA_STATUS_NORMAL)
                ->find()
                ->toArray();
        } catch (DataNotFoundException|ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * 根据标识获取用户信息并验证密码登录
     * @param string $identify
     * @param string $password
     * @return UserInfo
     * @throws DbException|PasswordNotMatchException|UserNotFoundException
     */
    public function login(string $identify, string $password) :UserInfo
    {
        $encryptPassword = $this->encryptPassword($password);
        $user = $this->getUserByIdentify($identify);
        if ($user['password'] != $encryptPassword) {
            throw new PasswordNotMatchException();
        }
        return $this->generateUserInfoByUser($user);
    }


    /**
     * 数据结构转换
     * @param array $user
     * @return UserInfo
     */
    private function generateUserInfoByUser(array $user) :UserInfo
    {
        $userInfo = new UserInfo();
        $userInfo->id = $user['id'];
        $userInfo->loginType = $userInfo['login_type'];
        $userInfo->name = $user['nickname'];
        $userInfo->avatar = $user['avatar'];
        $userInfo->gender = $user['gender'];
        return $userInfo;
    }

    /**
     * 根据用户名获取用户信息
     * @param string $username
     * @param string $password
     * @return UserInfo
     * @throws DbException
     * @throws PasswordNotMatchException
     * @throws UserNotFoundException
     */
    public function loginByUsername(string $username, string $password): UserInfo
    {
        $encryptPassword = $this->encryptPassword($password);

        $user = $this->getUserByUsername($username);
        if ($user['password'] != $encryptPassword) {
            throw new PasswordNotMatchException();
        }
        return $this->generateUserInfoByUser($user);
    }

    /**
     * @param string $identify
     * @param string $password
     * @param string $verifyCode
     * @param array $userInfo
     * @return UserInfo
     * @throws DbException
     * @throws UserExistsException
     * @throws VerifyCodeNotMatchException
     */
    public function register(string $identify, string $password, string $verifyCode, array $userInfo): UserInfo
    {
        if ($this->verifyCodeCli->verifyCode($identify, VerifyCodeImpl::VERIFY_CODE_TYPE_REGISTER, $verifyCode)) {
            throw new VerifyCodeNotMatchException();
        }
        try {
            // 检查当前标识是否已经被注册
            $this->getUserByIdentify($identify);
            // 没有设置用户名则用标识替代
            $username = $userInfo['username'] ?? '';
            if (empty($username)) {
                $username = $identify;
            }
            // 检查用户名是否被注册
            $this->getUserByUsername($username);
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
     * @throws DbException
     */
    private function createUser(string $identify, string $password, array $userInfo) :UserInfo
    {
        $insertUserData = [
            'login_type' => $this->loginType,
            'identify' => $identify,
            'password' => $this->encryptPassword($password),
            'username' => $userInfo['username'] ?? '',
            'nickname' => $userInfo['nickname'] ?? '',
            'avatar' => $userInfo['avatar'] ?? '',
            'gender' => $userInfo['gender'] ?? 0,
        ];
        $id = $this->dbConn->newQuery()
            ->table(Constant::getUserDbTable())
            ->insertGetId($insertUserData);
        if (empty($id)) {
            throw new DbException('create user error');
        }
        $insertUserData['id'] = $id;
        return $this->generateUserInfoByUser($insertUserData);
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
    public function changePassword(string $identify, string $verifyCode, string $password)
    {
        if ($this->verifyCodeCli->verifyCode($identify, VerifyCodeImpl::VERIFY_CODE_TYPE_FORGOT_PASSWORD, $verifyCode)) {
            throw new VerifyCodeNotMatchException();
        }
        $user = $this->getUserByIdentify($identify);
        $encryptPassword = $this->encryptPassword($password);
        if ($encryptPassword == $user['password']) {
            throw new PasswordEqualOldException();
        }
        $this->updatePasswordByID($user['id'], $encryptPassword);
    }

    /**
     * 更新密码到数据库
     * @param int $id
     * @param string $newPassword
     * @return int
     * @throws DbException
     */
    private function updatePasswordByID(int $id, string $newPassword): int
    {
        return $this->dbConn->newQuery()
            ->table(Constant::getUserDbTable())
            ->where('id', '=', $id)
            ->update(['password' => $newPassword]);
    }

    /**
     * 根据旧密码修改密码
     * @param string $identify
     * @param string $oldPassword
     * @param string $password
     * @return void
     * @throws DbException
     * @throws UserNotFoundException|PasswordEqualOldException|PasswordNotMatchException
     */
    public function changePasswordByOldPassword(string $identify, string $oldPassword, string $password)
    {
        $user = $this->getUserByIdentify($identify);
        $encryptOldPassword = $this->encryptPassword($oldPassword);
        $encryptNewPassword = $this->encryptPassword($password);
        if ($user['password'] != $encryptOldPassword) {
            throw new PasswordNotMatchException();
        }
        if ($encryptOldPassword == $encryptNewPassword) {
            throw new PasswordEqualOldException();
        }
        $this->updatePasswordByID($user['id'], $encryptNewPassword);

    }
}