<?php

namespace Package\Uc\Model;

use Package\Uc\Common\Constant;
use Package\Uc\DataStruct\UserInfo;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Model;

// CREATE TABLE `user` (
//    `id` int NOT NULL AUTO_INCREMENT COMMENT '用户id',
//    `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
//    `login_type` varchar(16) NOT NULL DEFAULT '' COMMENT '登录类型',
//    `identify` varchar(64) NOT NULL DEFAULT '' COMMENT '标志性账号, 登录类型是email则为邮箱， mobile则为手机号',
//    `password` char(40) NOT NULL DEFAULT '' COMMENT '密码',
//    `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
//    `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
//    `gender` tinyint NOT NULL DEFAULT 0 COMMENT '性别， 0未知， 1男， 2女',
//    `active` tinyint NOT NULL DEFAULT 1 COMMENT '1争产， 0删除',
//    `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
//    `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//    `last_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后登录时间',
//    PRIMARY KEY (`id`) USING BTREE,
//    UNIQUE `uk_username` (`username`),
//    UNIQUE `uk_identify` (`identify`)
//) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

/**
 * @property mixed $password
 */
class User extends Model
{
    protected $table      = 'user';
    protected $connection = 'user_center';

    protected $schema = [
        'id'              => 'int',
        'username'        => 'varchar(32)',
        'login_type'      => 'varchar(16)',
        'identify'        => 'varchar(64)',
        'password'        => 'char(40)',
        'nickname'        => 'varchar(32)',
        'avatar'          => 'varchar(255)',
        'gender'          => 'tinyint',
        'active'          => 'tinyint',
        'create_time'     => 'timestamp',
        'update_time'     => 'timestamp',
        'last_login_time' => 'timestamp',
    ];

    public function toUserInfo(): UserInfo
    {
        $userInfo = new UserInfo();
        $userInfo->id = $this->id;
        $userInfo->loginType = $this->login_type;
        $userInfo->name = $this->nickname;
        $userInfo->avatar = $this->avatar;
        $userInfo->gender = $this->gender;
        return $userInfo;
    }

    /**
     * getUserByIdentify 根据标识获取用户信息
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function getUserByIdentify(string $loginType, string $identify): User
    {
        return $this->newQuery()
                    ->where('login_type', '=', $loginType)
                    ->where('identify', '=', $identify)
                    ->where('active', '=', Constant::DATA_STATUS_NORMAL)
                    ->findOrFail();
    }

    /**
     * getUserByUsername 根据用户名获取用户信息
     * @param string $username
     * @return User
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function getUserByUsername(string $username): User
    {
        return $this->newQuery()
                    ->where('username', '=', $username)
                    ->where('active', '=', Constant::DATA_STATUS_NORMAL)
                    ->findOrFail();
    }
}