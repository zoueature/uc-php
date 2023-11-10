<?php
// Package\Uc\Component/PasswordEncrypt


namespace Package\Uc\Component;


trait PasswordEncrypt
{
    private $passwordSalt = "AiodaHUYT%^4sad&%%9";

    /**
     * 加密密码
     * @param string $password
     * @return string
     */
    private function encryptPassword(string $password): string
    {
        $toEncryptString = $password . $this->passwordSalt;
        $encryptPassword = sha1($toEncryptString);
        return $encryptPassword;
    }
}