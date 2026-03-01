<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

try {
    // IMPORTANTE: O TiDB exige SSL para funcionar na Vercel
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [PDO::MYSQL_ATTR_SSL_CA => true] 
    );
    // conectou!
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
