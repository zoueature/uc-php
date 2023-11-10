<?php
// Package\Uc\DataStruct/UserInfoWithJwt


namespace Package\Uc\DataStruct;


class UserInfoWithJwt
{
    /** @var UserInfo $userInfo */
    public $userInfo;

    /** @var string $jwt */
    public $jwt;

    public function __construct(UserInfo $info, string $jwt)
    {
        $this->userInfo = $info;
        $this->jwt = $jwt;
    }
}