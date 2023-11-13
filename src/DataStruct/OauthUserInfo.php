<?php

namespace Package\Uc\DataStruct;

class OauthUserInfo
{
    public string $userId;

    public string $username;

    public string $avatar;

    public string $email;

    public function __construct(string $userId, string $username = '', string $avatar = '', string $email = '')
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->email = $email;
    }
}