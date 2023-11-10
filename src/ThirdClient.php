<?php
// Package\Uc/ThirdClient


namespace Package\Uc;


use Package\Uc\Interf\ThirdLogin;
use think\db\Connection;

class ThirdClient
{
    /** @var Connection $dbConn */
    private $dbConn;

    /** @var ThirdLogin $loginClient */
    private $loginClient;

    public function __construct(ThirdLogin $loginClient, Connection $conn)
    {
        $this->dbConn = $conn;
        $this->loginClient = $loginClient;
    }
}