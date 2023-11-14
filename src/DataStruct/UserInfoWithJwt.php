<?php
// Package\Uc\DataStruct/UserInfoWithJwt


namespace Package\Uc\DataStruct;


class UserInfoWithJwt
{
    /** @var UserInfo $userInfo */
    public UserInfo $userInfo;

    /** @var string $jwt */
    public string $jwt;

    public function __construct(UserInfo $info, string $jwt)
    {
        $this->userInfo = $info;
        $this->jwt      = $jwt;
    }
}