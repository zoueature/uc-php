<?php
// Package\Uc/LoginType


namespace Package\Uc\Common;


use Package\Uc\Impl\Internal\EmailLoginImpl;
use Package\Uc\Impl\Internal\MobileLoginImpl;
use Package\Uc\Impl\Oauth\Facebook;
use Package\Uc\Impl\Oauth\Google;

class LoginType
{
    const EMAIL    = 'email';
    const MOBILE   = 'mobile';
    const FACEBOOK = 'facebook';
    const GOOGLE   = 'google';
    const TWITTER  = 'twitter';
    const TIKTOK   = 'tiktok';

    const INTERNAL_LOGIN_TYPE = [
        self::EMAIL  => EmailLoginImpl::class,
        self::MOBILE => MobileLoginImpl::class,
    ];

    const THIRD_LOGIN_TYPE = [
        self::FACEBOOK => Facebook::class,
        self::GOOGLE   => Google::class,
        self::TWITTER,
        self::TIKTOK,
    ];

}