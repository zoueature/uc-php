<?php

namespace Package\Uc\Impl\Oauth;

use Package\Uc\DataStruct\OauthUserInfo;
use Package\Uc\Impl\OauthLoginImpl;
use Package\Uc\Interf\OauthLogin;

class Twitter extends OauthLoginImpl implements OauthLogin
{

    public function getToken($code)
    {
        // TODO: Implement getToken() method.
    }

    public function getInfos($tokenInfo): OauthUserInfo
    {
        // TODO: Implement getInfos() method.
    }
}