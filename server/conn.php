<?php  
// Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = $_SERVER['HTTP_HOST'] ?? '';

if ($host === 'localhost' || str_contains($host, '127.0.0.1') || str_contains($host, '192.168.18.17') || str_contains($host, '172.20.10.10') || str_contains($host, 'localhost')) {

    define('db_host', 'localhost');
    define('db_user', 'root');
    define('db_pass', '');
    define('db_name', 'brightpath200');

} else {

    define('db_host', 'localhost');
    define('db_user', 'bustofrj_busterblog');	
    define('db_pass', 'Samuel252.');
    define('db_name', 'bustofrj_brightpath');
}
   $conn=new mysqli(db_host,db_user,db_pass) or die('unknow database connection');
   $conn->query('create database if not exists '. db_name);
   $conn->select_db(db_name);
