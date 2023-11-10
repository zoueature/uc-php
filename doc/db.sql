CREATE TABLE `user` (
    `id` int NOT NULL AUTO_INCREMENT COMMENT '用户id',
    `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
    `login_type` varchar(16) NOT NULL DEFAULT '' COMMENT '登录类型',
    `identify` varchar(64) NOT NULL DEFAULT '' COMMENT '标志性账号, 登录类型是email则为邮箱， mobile则为手机号',
    `password` char(40) NOT NULL DEFAULT '' COMMENT '密码',
    `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
    `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
    `gender` tinyint NOT NULL DEFAULT 0 COMMENT '性别， 0未知， 1男， 2女',
    `active` tinyint NOT NULL DEFAULT 1 COMMENT '1争产， 0删除',
    `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `last_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后登录时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE `uk_username` (`username`),
    UNIQUE `uk_identify` (`identify`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8mb4 COMMENT='用户表'