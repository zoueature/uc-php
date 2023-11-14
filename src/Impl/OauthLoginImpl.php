<?php

namespace Package\Uc\Impl;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Package\Uc\Config\OauthConfig;
use Package\Uc\Exception\UcException;

class OauthLoginImpl
{
    protected string $loginType;

    protected Client $httpClient;

    protected OauthConfig $config;

    public function __construct(OauthConfig $config)
    {
        $this->config     = $config;
        $this->httpClient = new Client();
    }

    /**
     * 获取登录类型
     * @return string
     */
    public function getLoginType(): string
    {
        return $this->loginType;
    }

    /**
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     * @throws GuzzleException|UcException
     */
    protected function doHttpRequestWithJsonResp(string $method, string $uri = '', array $options = []): array
    {
        $resp = $this->httpClient->request($method, $uri, $options);
        if ($resp->getStatusCode() != 200) {
            throw new UcException("Return http status code: " . $resp->getStatusCode());
        }
        $body = $resp->getBody()->getContents();
        $data = json_decode($body, true);
        if (empty($data)) {
            throw new UcException('Return body is not json: ' . $body);
        }
        return $data;
    }
}