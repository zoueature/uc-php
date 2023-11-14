<?php

namespace Package\Uc\Config;

class ThirdConfig
{
    public string $clientId;

    public string $clientSecret;

    public string $loginType;

    public function withLoginType(string $loginType): ThirdConfig
    {
        $this->loginType = $loginType;
        return $this;
    }

    public function withClientId(string $clientId): ThirdConfig
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function withClientSecret(string $clientSecret): ThirdConfig
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }
}