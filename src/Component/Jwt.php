<?php
// Package\Uc\Component/Jwt


namespace Package\Uc\Component;


use Firebase\JWT\Key;
use Package\Uc\DataStruct\UserInfo;
use Package\Uc\Exception\TokenExpireException;
use stdClass;

trait Jwt
{
    private $ttl = '604800'; // 默认7天

    private $jwtKey = 'HKJ8979asdhGD678*^&KJDAS';

    private $algorithm = 'HS256';

    public function setJwyKey(string $key)
    {
        $this->jwtKey = $key;
    }

    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;
    }

    public function setAlgo(string $algo)
    {
        $this->algorithm = $algo;
    }

    private function encodeJwt(UserInfo $info): string
    {
        $payload            = $info->toArray();
        $payload['loginAt'] = time();
        $jwt                = \Firebase\JWT\JWT::encode($payload, $this->jwtKey, $this->algorithm);
        return $jwt;
    }

    /**
     * @param string $token
     * @return stdClass
     * @throws TokenExpireException
     */
    private function decodeJwt(string $token): object
    {
        $info      = \Firebase\JWT\JWT::decode($token, new Key($this->jwtKey, $this->algorithm));
        $loginTime = time() - $info->loginAt ?? 0;
        if ($loginTime > $this->ttl) {
            throw new TokenExpireException();
        }
        return $info;
    }
}