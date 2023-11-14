<?php
// Package\Uc\Login/FacebookLogin


namespace Package\Uc\Impl\Oauth;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Package\Uc\Common\LoginType;
use Package\Uc\Exception\UcException;
use Package\Uc\Impl\OauthLoginImpl;
use Package\Uc\Interf\OauthLogin;

class Facebook extends OauthLoginImpl implements OauthLogin
{
    const GET_ACCESS_TOKEN_URL = 'https://graph.facebook.com/v18.0/oauth/access_token';

    protected string $loginType = LoginType::FACEBOOK;

    public function getToken($code)
    {
        // TODO: Implement getToken() method.
    }

    /**
     * @throws GuzzleException|UcException
     */
    private function getAccessToken(string $code): string
    {
        $data        = $this->doHttpRequestWithJsonResp('GET', self::GET_ACCESS_TOKEN_URL, [
            RequestOptions::QUERY => [
                'client_id'     => '',
                'redirect_uri'  => '',
                'client_secret' => '',
                'code'          => '',
            ]
        ]);
        $accessToken = $data['access_token'] ?? '';
        if (empty($accessToken)) {
            throw new \Exception("Get access token error");
        }
        return $accessToken;
    }

    public function getInfos($tokenInfo)
    {
        // TODO: Implement getInfos() method.
    }
}