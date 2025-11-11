<?php
define('DB_HOST', 'sql207.infinityfree.com');
define('DB_NAME', 'if0_40232844_floricultura');
define('DB_USER', 'if0_40232844');
define('DB_PASS', 'mateuseu');

function conectarDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}
?>

