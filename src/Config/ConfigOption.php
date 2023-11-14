<?php

namespace Package\Uc\Config;

class ConfigOption
{
    const USER_MODEL_CLASS       = 'user_model';          // 用户模型类名
    const OAUTH_USER_MODEL_CLASS = 'oauth_user_model';    // oauth用户模型类名
    const MAIL_SENDER_DSN        = 'mail_sender_dsn';     // 邮箱配置
    const SMS_MAIL_SUBJECT       = 'sms_subject';         // 验证码邮件标题
}