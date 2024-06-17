<?php

$DB_HOST = 'MySQL-8.2';
$DB_PORT = '3306';
$DB_NAME = 'car_orend';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';

try {
    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME}";
    $pdo = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
} catch (PDOException $e) {
  echo "Помилка: " . $e->getMessage();
}