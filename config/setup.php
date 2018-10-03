<?php

include 'database.php';

$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "CREATE DATABASE IF NOT EXISTS $DB_NAME";
$conn->exec($sql);
$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "CREATE TABLE IF NOT EXISTS MyUsers (
user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
user_login VARCHAR(255) NOT NULL,
user_password VARCHAR(64) NOT NULL,
user_email VARCHAR(255) NOT NULL,
user_key VARCHAR(32) NOT NULL,
user_active INT(1) DEFAULT '0',
notifications INT(1) DEFAULT '1'
)";
$conn->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS MyImg (
img_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
img_name VARCHAR(255) NOT NULL,
user_id INT(6) NOT NULL,
user_login VARCHAR(255) NOT NULL,
likes_counter INT(6) DEFAULT '0'
)";
$conn->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS Likes (
like_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT(6) NOT NULL,
img_id INT(6) NOT NULL
)";
$conn->exec($sql);
$sql = "CREATE TABLE IF NOT EXISTS Comments (
comment_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT(6) NOT NULL,
img_id INT(6) NOT NULL,
comment_content TEXT NOT NULL
)";
$conn->exec($sql);
$conn = null;

?>