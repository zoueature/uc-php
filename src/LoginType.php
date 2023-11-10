<?php
// Package\Uc/LoginType


namespace Package\Uc;


class LoginType
{
    const EMAIL = 'email';
    const MOBILE = 'mobile';
    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const TWITTER = 'twitter';
    const TIKTOK = 'tiktok';

    const INTERNAL_LOGIN_TYPE = [
        self::EMAIL,
        self::MOBILE,
    ];

    const THIRD_LOGIN_TYPE = [
        self::FACEBOOK,
        self::GOOGLE,
        self::TWITTER,
        self::TIKTOK,
    ];

}