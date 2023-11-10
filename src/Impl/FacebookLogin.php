<?php
// Package\Uc\Login/FacebookLogin


namespace Package\Uc\Impl;


use Package\Uc\Interf\ThirdLogin;
use Package\Uc\LoginType;

class FacebookLogin implements ThirdLogin
{

    public function getLoginType() :string
    {
        return LoginType::FACEBOOK;
    }

    public function getToken($code)
    {
        // TODO: Implement getToken() method.
    }

    public function getInfos($tokenInfo)
    {
        // TODO: Implement getInfos() method.
    }
}