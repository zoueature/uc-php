<?php
// Package\Uc\DataStruct/UserInfo


namespace Package\Uc\DataStruct;


class UserInfo
{
    /** @var int $id */
    public $id;

    /** @var string $loginType */
    public $loginType;

    /** @var string $name */
    public $name;

    /** @var string $avatar */
    public $avatar;

    /** @var string $gender */
    public $gender;

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'loginType' => $this->loginType,
            'name'      => $this->name,
            'avatar'    => $this->avatar,
            'gender'    => $this->gender,
        ];
    }
}