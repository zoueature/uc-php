<?php
// Package\Uc\Login\Impl/ThirdLogin


namespace Package\Uc\Interf;


interface ThirdLogin
{
    public function getLoginType() :string;

    # 获取token和openid
    public function getToken($code);

    # 获取nickname等用户信息
    public function getInfos($tokenInfo);

}