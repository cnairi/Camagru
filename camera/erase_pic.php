<?php

include __DIR__ . '/../config/database.php';

$img_name = $_REQUEST["img_name"];

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}

$stmt = $conn -> prepare('DELETE FROM MyImg WHERE img_name=:img_name');
$stmt -> execute(array("img_name"=>$img_name));