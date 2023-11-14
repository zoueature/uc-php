<?php

namespace Package\Uc\Config;

class OauthConfig
{
    public string $clientId;

    public string $clientSecret;

    public string $loginType;

    public string $redirectURI;

    public function withRedirectURI(string $redirectURI): OauthConfig
    {
        $this->redirectURI = $redirectURI;
        return $this;
    }

    public function withLoginType(string $loginType): OauthConfig
    {
        $this->loginType = $loginType;
        return $this;
    }

    public function withClientId(string $clientId): OauthConfig
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function withClientSecret(string $clientSecret): OauthConfig
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }
}