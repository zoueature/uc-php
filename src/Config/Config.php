<?php

namespace Package\Uc\Config;

use Package\Uc\Model\OauthUser;
use Package\Uc\Model\User;

class Config
{
    private static array $config = [
        ConfigOption::USER_MODEL_CLASS       => User::class,
        ConfigOption::OAUTH_USER_MODEL_CLASS => OauthUser::class,
        ConfigOption::MAIL_SENDER_DSN        => '',
    ];

    public static function setConfig(string $option, $value)
    {
        static::$config[$option] = $value;
    }

    public static function getConfig(string $option)
    {
        return static::$config[$option];
    }

}