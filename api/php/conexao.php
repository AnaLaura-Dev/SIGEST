<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host   = 'db_host';
$user   = 'db_user';
$pass   = 'senha';
$dbname = 'db_name';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
