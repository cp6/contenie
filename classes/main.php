<?php

class main
{
    public function db_connect(bool $select_only = false, string $db_host = '127.0.0.1'): object
    {
        if ($select_only) {
            $db_user = 'select_only';
            $db_password = '';
        } else {
            $db_user = 'root';
            $db_password = '';
        }
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        return new PDO("mysql:host=$db_host;dbname=contenie;charset=utf8mb4", $db_user, $db_password, $options);
    }
}