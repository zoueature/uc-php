<?php
// Package\Uc/ThirdClient


namespace Package\Uc;


use Package\Uc\Interf\ThirdLogin;
use think\db\Connection;
use think\db\ConnectionInterface;

class ThirdClient
{
    /** @var ConnectionInterface $dbConn */
    private $dbConn;

    /** @var ThirdLogin $loginClient */
    private $loginClient;

    public function __construct(ThirdLogin $loginClient, ConnectionInterface $conn)
    {
        $this->dbConn = $conn;
        $this->loginClient = $loginClient;
    }
}