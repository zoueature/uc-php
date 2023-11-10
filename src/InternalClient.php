<?php
// Package\Uc/Clinet


namespace Package\Uc;

use Package\Uc\DataStruct\UserInfo;
use Package\Uc\Impl\EmailLogin;
use Package\Uc\Impl\MobileLogin;
use Package\Uc\Interf\InternalLogin;
use think\db\Connection;


class InternalClient
{

    /** @var Connection $dbConn */
    private $dbConn;

    /** @var InternalLogin $loginClient */
    private $loginClient;

    public function __construct(string $loginType, Connection $conn)
    {
        $this->dbConn = $conn;
        $this->loginClient = $this->getLoginClientByLoginType($loginType);
    }

    /**
     * @param string $loginType
     * @return InternalLogin
     * @throws \Exception
     */
    private function getLoginClientByLoginType(string $loginType) :InternalLogin
    {
        $loginClient = null;
        switch ($loginType) {
            case LoginType::EMAIL:
                $loginClient = new EmailLogin($this->dbConn);
                break;
            case LoginType::MOBILE:
                $loginClient = new MobileLogin();
                break;
            default:
                throw new \Exception("undefined login type " . $loginType);
        }
        return $loginClient;
    }

    public function InternalLogin(string $identify, string $password) :UserInfo
    {
        return $this->loginClient->login($identify, $password);
    }
}