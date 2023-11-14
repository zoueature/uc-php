<?php
// Package\Uc\Component/Convert


namespace Package\Uc\Component;


use Package\Uc\DataStruct\UserInfo;

trait Convert
{
    private function objectToUserInfo(object $info): UserInfo
    {
        $userInfo            = new UserInfo();
        $userInfo->id        = $info->id;
        $userInfo->loginType = $info->loginType;
        $userInfo->name      = $info->name;
        $userInfo->avatar    = $info->avatar;
        $userInfo->gender    = $info->gender;
        return $userInfo;
    }

    /**
     * 数据结构转换
     * @param array $user
     * @return UserInfo
     */
    private function arrayToUserInfo(array $user): UserInfo
    {
        $userInfo            = new UserInfo();
        $userInfo->id        = $user['id'];
        $userInfo->loginType = $user['login_type'];
        $userInfo->name      = $user['nickname'];
        $userInfo->avatar    = $user['avatar'];
        $userInfo->gender    = $user['gender'];
        return $userInfo;
    }
}