<?php

namespace Package\Uc\Config;

class ConfigOption
{
    // 用户模型类名
    const USER_MODEL_CLASS = 'user_model';
    // 第三方登录用户模型类名
    const OAUTH_USER_MODEL_CLASS = 'oauth_user_model';
    // 邮箱配置
    const MAIL_SENDER_DSN  = 'mail_sender_dsn';
    // 验证码邮件标题
    const SMS_MAIL_SUBJECT = 'sms_subject';
}