<?php
// Package\Uc/Login


namespace Package\Uc\Interf;


use Package\Uc\DataStruct\UserInfo;

interface InternalLogin
{
    // getLoginType 获取登录类型
    public function getLoginType(): string;

    // sendSmsCode 发送验证码
    public function sendSmsCode(int $codeType, string $identify);

    // checkIdentifyFormat 检查标识格式
    public function checkIdentifyFormat(string $identify): bool;

    // register 注册用户
    public function register(string $identify, string $password, string $verifyCode, array $userInfo): UserInfo;

    // login 用户登录
    public function login(string $identify, string $password): UserInfo;

    // login 用户登录
    public function loginByUsername(string $username, string $password): UserInfo;

    // changePassword 忘记密码密码修改
    public function changePassword(string $identify, string $verifyCode, string $password);

    // changePasswordByOldPassword 根据旧密码修改密码
    public function changePasswordByOldPassword(string $identify, string $oldPassword, string $password);

}