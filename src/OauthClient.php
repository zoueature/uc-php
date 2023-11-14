<?php
// Package\Uc/ThirdClient


namespace Package\Uc;


use Package\Uc\Common\LoginType;
use Package\Uc\Component\Jwt;
use Package\Uc\Config\Config;
use Package\Uc\Config\ConfigOption;
use Package\Uc\Config\OauthConfig;
use Package\Uc\DataStruct\OauthUserInfo;
use Package\Uc\DataStruct\UserInfoWithJwt;
use Package\Uc\Exception\LackDataException;
use Package\Uc\Exception\UcException;
use Package\Uc\Exception\UndefinedLoginTypeException;
use Package\Uc\Exception\UserNotFoundException;
use Package\Uc\Interf\OauthLogin;
use Package\Uc\Model\OauthUser;
use Package\Uc\Model\User;

class OauthClient
{

    use Jwt;

    private OauthLogin $loginClient;

    private OauthConfig $config;

    private User $user;

    private OauthUser $oauthUser;

    /**
     * @throws UndefinedLoginTypeException
     */
    public function __construct(OauthConfig $config)
    {
        $this->config        = $config;
        $this->loginClient   = $this->generateLoginClient($config->loginType);
        $userModelClass      = Config::getConfig(ConfigOption::USER_MODEL_CLASS);
        $oauthUserModelClass = Config::getConfig(ConfigOption::OAUTH_USER_MODEL_CLASS);
        $this->user          = new $userModelClass();
        $this->oauthUser     = new $oauthUserModelClass();
    }

    /**
     * 实例化第三方客户端
     * @param string $loginType
     * @return OauthLogin
     * @throws UndefinedLoginTypeException
     */
    private function generateLoginClient(string $loginType): OauthLogin
    {
        $clientClass = LoginType::THIRD_LOGIN_TYPE[$loginType] ?? null;
        if (empty($clientClass)) {
            throw new UndefinedLoginTypeException($loginType);
        }
        return new $clientClass($this->config);
    }

    /**
     * @param string $code
     * @return UserInfoWithJwt
     * @throws LackDataException|UcException
     */
    public function login(string $code): UserInfoWithJwt
    {
        $accessToken = $this->loginClient->getToken($code);
        $oauthInfo   = $this->loginClient->getInfos($accessToken);
        $user        = null;
        try {
            $user = $this->getUserInfo($this->config->loginType, $oauthInfo->userId);
        } catch (UserNotFoundException $exception) {
            //  新用户登录， 注册新用户
            $user = $this->register($oauthInfo);
        }
        $userDto = $user->toUserInfo();
        return new UserInfoWithJwt($userDto, $this->encodeJwt($userDto));
    }

    /**
     * @param string $loginType
     * @param string $userId
     * @return User
     * @throws UserNotFoundException|LackDataException
     */
    private function getUserInfo(string $loginType, string $userId): User
    {
        $oauthUser = $this->oauthUser->getUserByUserId($loginType, $userId);
        try {
            return $this->user->getUserById($oauthUser->id);
        } catch (UserNotFoundException $exception) {
            throw new LackDataException('user info for oauth');
        }
    }

    /**
     * register 新用户注册
     * @param OauthUserInfo $oauthUserInfo
     * @return User
     * @throws UcException
     */
    private function register(OauthUserInfo $oauthUserInfo): User
    {
        $user      = clone $this->user;
        $oauthUser = clone $this->oauthUser;
        // 开启事务完成两边数据的插入
        $user->startTrans();

        // 插入用户信息主表， 空置密码， 无法使用用户名等登录
        $user->login_type = $this->config->loginType;
        $user->identify   = $this->config->loginType . '-' . $oauthUserInfo->userId;
        $user->nickname   = $oauthUserInfo->username;
        $user->avatar     = $oauthUserInfo->avatar;
        $ok               = $user->save();
        if (!$ok) {
            $user->rollback();
            throw new UcException("create user error");
        }

        // 插入第三方登录用户信息表， 并与主表关联
        $oauthUser->bind_user_id = $user->id;
        $oauthUser->login_type   = $user->login_type;
        $oauthUser->user_id      = $oauthUserInfo->userId;
        $ok                      = $oauthUser->save();
        if (!$ok) {
            $user->rollback();
            throw new UcException("create oauth user error");
        }
        $user->commit();
        return $user;
    }
}