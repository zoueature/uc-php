<?php
// Package\Uc/Constant


namespace Package\Uc;


class Constant
{
    const GENDER_MALE = 1; // 男性
    const GENDER_FEMALE = 2; // 女性

    const DATA_STATUS_NORMAL = 1;  // 正常数据
    const DATA_STATUS_DELETED = 0; // 被删除的数据

    private static $userDbTable = "user";

    /**
     * 设置用户表名
     * @param string $tableName
     * @return void
     */
    public static function setUserDbTable(string $tableName)
    {
        static::$userDbTable = $tableName;
    }

    /**
     * 获取用户表名
     * @return string
     */
    public static function getUserDbTable() :string
    {
        return static::$userDbTable;
    }

}