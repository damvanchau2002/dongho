<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class Database
{
    public static function connection()
    {
        return getConnection();
    }
}
