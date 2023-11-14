<?php
// Package\Uc\Login/EmailLogin


namespace Package\Uc\Impl\Internal;


use Package\Uc\Common\LoginType;
use Package\Uc\Config\Config;
use Package\Uc\Config\ConfigOption;
use Package\Uc\Impl\InternalLoginImpl;
use Package\Uc\Interf\InternalLogin;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class EmailLoginImpl extends InternalLoginImpl implements InternalLogin
{


    protected string $loginType = LoginType::EMAIL;


    public function sendSmsCode(int $codeType, string $identify)
    {
        $verifyCode = $this->verifyCodeCli->generateVerifyCode($identify, $codeType);
        // TODO 发送邮件, 消息队列异步发送
        $this->sendSmsMail("", $identify, $verifyCode);
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendSmsMail(string $from, string $to, string $verifyCode)
    {
        $transport = Transport::fromDsn(Config::getConfig(ConfigOption::MAIL_SENDER_DSN));
        $mailer    = new Mailer($transport);

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject(Config::getConfig(ConfigOption::SMS_MAIL_SUBJECT))
            ->html("<p>The code is $verifyCode</p>");
        $mailer->send($email);

    }


}