<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=cemazon_db;charset=utf8", "root", "cem123");
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>