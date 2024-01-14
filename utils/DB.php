<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'secretpassword');
define('DB_NAME', 'naive_bayes');

class DB
{
    public static function connect() {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $connection;
    }

    public static function close($connection) {
        mysqli_close($connection);
    }

    public static function query($connection, $query) {
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($connection));
        }
        return $result;
    }
}