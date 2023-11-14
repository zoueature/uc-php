<?php
// Package\Uc\Login/FacebookLogin


namespace Package\Uc\Impl\Oauth;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Package\Uc\Common\LoginType;
use Package\Uc\DataStruct\OauthUserInfo;
use Package\Uc\Exception\UcException;
use Package\Uc\Impl\OauthLoginImpl;
use Package\Uc\Interf\OauthLogin;

class Facebook extends OauthLoginImpl implements OauthLogin
{
    const GET_ACCESS_TOKEN_URL = 'https://graph.facebook.com/v18.0/oauth/access_token';
    const GET_USER_INFO_URL    = 'https://graph.facebook.com/me';

    protected string $loginType = LoginType::FACEBOOK;

    /**
     * @param $code
     * @return mixed|string
     * @throws GuzzleException
     * @throws UcException
     */
    public function getToken($code): string
    {
        $data        = $this->doHttpRequestWithJsonResp('GET', self::GET_ACCESS_TOKEN_URL, [
            RequestOptions::QUERY => [
                'client_id'     => $this->config->clientId,
                'redirect_uri'  => $this->config->redirectURI,
                'client_secret' => $this->config->clientSecret,
                'code'          => $code,
            ]
        ]);
        $accessToken = $data['access_token'] ?? '';
        if (empty($accessToken)) {
            throw new UcException("Get access token error");
        }
        return $accessToken;
    }

    /**
     * 获取用户信息
     * @param $tokenInfo
     * @return OauthUserInfo
     * @throws GuzzleException
     * @throws UcException
     */
    public function getInfos($tokenInfo): OauthUserInfo
    {
        $data     = $this->doHttpRequestWithJsonResp('GET', self::GET_USER_INFO_URL, [
            RequestOptions::QUERY => [
                'access_token' => $tokenInfo,
            ]
        ]);
        $userId   = $data['id'] ?? '';
        $nickname = $data['name'] ?? '';
        if (empty($userId)) {
            throw new UcException("Facebook login fail :" . json_encode($data));
        }
        // TODO 获取邮箱/头像等详细信息
        return new OauthUserInfo($userId, $nickname);
    }
}