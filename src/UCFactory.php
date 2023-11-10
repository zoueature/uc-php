<?php
// Package\Uc/UCFactory


namespace Package\Uc;


use think\db\Connection;

class UCFactory
{
    /**
     * @param string $loginType
     * @param Connection $conn
     * @return InternalClient|ThirdClient
     */
    public static function newInternalCline(string $loginType, Connection $conn)
    {

    }

    private static function generateLoginClientByType(string $loginType)
    {
        switch ($loginType) {

        }
    }
}