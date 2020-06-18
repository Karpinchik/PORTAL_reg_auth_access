<?php
session_start();
define('USER', '');
define('PASSWORD', '');

$dsn = 'mysql:host=; dbname=;';
$user = USER;
$password = PASSWORD;

$opt = [
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES   => false
];


