<?php

namespace Package\Uc\Impl\Oauth;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Package\Uc\DataStruct\OauthUserInfo;
use Package\Uc\Exception\UcException;
use Package\Uc\Impl\OauthLoginImpl;
use Package\Uc\Interf\OauthLogin;

class TikTok extends OauthLoginImpl implements OauthLogin
{

    const OBTAIN_ACCESS_TOKEN_URL = 'https://open.tiktokapis.com/v2/oauth/token/';
    const GET_USER_INFO_URL       = 'https://open.tiktokapis.com/v2/research/user/info/';

    const OK_STATUS = 'ok';

    private string $tmpOpenId = '';

    /**
     * @param $code
     * @return mixed|string
     * @throws UcException
     * @throws GuzzleException
     */
    public function getToken($code)
    {
        $data   = $this->doHttpRequestWithJsonResp('POST', self::OBTAIN_ACCESS_TOKEN_URL, [
            RequestOptions::FORM_PARAMS => [
                'client_key'    => $this->config->clientId,
                'client_secret' => $this->config->clientSecret,
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->config->redirectURI,

            ]
        ]);
        $openid = $data['openid'] ?? '';
        if (empty($openid)) {
            throw new UcException("Get openid error");
        }
        $accessToken = $data['access_token'] ?? '';
        if (empty($accessToken)) {
            throw new UcException("Get access token error");
        }
        // 暂存
        $this->tmpOpenId = $openid;
        return $accessToken;
    }

    /**
     * @param $tokenInfo
     * @return OauthUserInfo
     * @throws GuzzleException
     * @throws UcException
     */
    public function getInfos($tokenInfo): OauthUserInfo
    {
        $data     = $this->doHttpRequestWithJsonResp('POST', self::GET_USER_INFO_URL, [
            RequestOptions::HEADERS     => [
                'Authorization' => 'Bearer ' . $tokenInfo,
            ],
            RequestOptions::FORM_PARAMS => [
                'fields' => 'display_name,avatar_url'
            ]
        ]);
        $status   = $data['error']['code'] ?? '';
        $username = '';
        $avatar   = '';
        if ($status === self::OK_STATUS) {
            $username = $data['data']['display_name'] ?? '';
            $avatar   = $data['data']['avatar_url'] ?? '';
        }
        return new OauthUserInfo($this->tmpOpenId, $username, $avatar);
    }
}