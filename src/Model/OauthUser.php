<?php

namespace Package\Uc\Model;

use Package\Uc\Common\Constant;
use Package\Uc\Exception\UserNotFoundException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Model;

//CREATE TABLE `oauth_user` (
//    `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
//    `user_id` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方用户id',
//    `login_type` varchar(16) NOT NULL DEFAULT '' COMMENT '登录类型',
//    `bind_user_id` int NOT NULL DEFAULT 0 COMMENT '绑定的内部用户id(user.id)',
//    `active` tinyint NOT NULL DEFAULT 1 COMMENT '1正常， 0删除',
//    `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
//    `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//    PRIMARY KEY (`id`) USING BTREE,
//    UNIQUE `uk_identify` (`user_id`, `login_type`),
//    INDEX `idx_bind_user` (`bind_user_id`)
//) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8mb4 COMMENT='Oauth用户表';

/**
 * @property mixed $id
 * @property mixed $bind_user_id
 * @property mixed|string $login_type
 * @property mixed|string $user_id
 */
class OauthUser extends Model
{
    protected $table      = 'oauth_user';
    protected $connection = 'user_center';

    protected $schema = [
        'id'           => 'int',
        'user_id'      => 'varchar(32)',
        'login_type'   => 'varchar(16)',
        'bind_user_id' => 'int',
        'active'       => 'tinyint',
        'create_time'  => 'timestamp',
        'update_time'  => 'timestamp',
    ];

    /**
     * 根据openid获取第三方登录用户信息
     * @param string $loginType
     * @param string $userId
     * @return OauthUser
     * @throws UserNotFoundException
     */
    public function getUserByUserId(string $loginType, string $userId): OauthUser
    {
        try {
            return $this->newQuery()
                        ->where('login_type', '=', $loginType)
                        ->where('user_id', '=', $userId)
                        ->where('active', '=', Constant::DATA_STATUS_NORMAL)
                        ->findOrFail();
        } catch (DataNotFoundException|ModelNotFoundException $e) {
            throw new UserNotFoundException('Oauth');
        }
    }
}