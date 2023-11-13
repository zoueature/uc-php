<?php
// Package\Uc/ThirdClient


namespace Package\Uc;


use Package\Uc\Common\LoginType;
use Package\Uc\DataStruct\UserInfoWithJwt;
use Package\Uc\Exception\UndefinedLoginTypeException;
use Package\Uc\Interf\ThirdLogin;
use think\db\ConnectionInterface;

class ThirdClient
{
    /** @var ConnectionInterface $dbConn */
    private ConnectionInterface $dbConn;

    /** @var ThirdLogin $loginClient */
    private ThirdLogin $loginClient;

    /**
     * @throws UndefinedLoginTypeException
     */
    public function __construct(string $loginType, ConnectionInterface $conn)
    {
        $this->dbConn = $conn;
        $this->loginClient = $this->generateLoginClient($loginType);
    }

    /**
     * 实例化第三方客户端
     * @param string $loginType
     * @return ThirdLogin
     * @throws UndefinedLoginTypeException
     */
    private function generateLoginClient(string $loginType) :ThirdLogin
    {
        $clientClass = LoginType::THIRD_LOGIN_TYPE[$loginType] ?? null;
        if (empty($clientClass)) {
            throw new UndefinedLoginTypeException($loginType);
        }
        $client = new $clientClass();
        return $client;
    }

    public function login(string $code) :UserInfoWithJwt
    {
        $accessToken = $this->loginClient->getToken($code);
        $userInfo = $this->loginClient->getInfos($accessToken);

    }
}