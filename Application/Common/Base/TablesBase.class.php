<?php
namespace Common\Base;

class TablesBase
{
    public static $tables = array(
       'member' => 'member',
    );

    public static function getTableName($t = '')
    {
        return isset(self::$tables[$t]) ? self::$tables[$t] : null;
    }
}