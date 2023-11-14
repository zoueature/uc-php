<?php

namespace Package\Uc\Impl\Oauth;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Package\Uc\DataStruct\OauthUserInfo;
use Package\Uc\Exception\UcException;
use Package\Uc\Impl\OauthLoginImpl;
use Package\Uc\Interf\OauthLogin;

class Google extends OauthLoginImpl implements OauthLogin
{
    const GET_USER_INFO_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';


    /**
     * google 登录回调过来的就是token
     * @param $code
     * @return string
     */
    public function getToken($code): string
    {
        return $code;
    }

    /**
     * 获取用户信息
     * @param $tokenInfo
     * @return OauthUserInfo
     * @throws UcException
     * @throws GuzzleException
     */
    public function getInfos($tokenInfo): OauthUserInfo
    {
        $userInfo = $this->doHttpRequestWithJsonResp('GET', self::GET_USER_INFO_URL, [
            RequestOptions::QUERY => [
                'access_token' => $tokenInfo,
            ]
        ]);
        $userId   = $userInfo['id'] ?? '';
        $email    = $userInfo['email'] ?? '';
        $avatar   = $userInfo['picture'] ?? '';
        if (empty($userId)) {
            throw new UcException("Google login fail :" . json_encode($userInfo));
        }
        $username = '';
        if (!empty($email)) {
            $arr      = explode('@', $email);
            $username = $arr[0];
        }
        return new OauthUserInfo($userId, $username, $avatar, $email);
    }
}