<?php

include __DIR__ . '/../config/database.php';

session_start();

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}

$img_id = intval($_GET['img_id']);
$user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) FROM Likes WHERE img_id = '".$img_id."' AND user_id = '".$user_id."'");
$stmt->execute();
$data = $stmt->fetch();

if ($data['COUNT(*)'] != 0) {
	$stmt = $conn->prepare("DELETE FROM Likes WHERE img_id = '".$img_id."' AND user_id = '".$user_id."'");
	$stmt->execute();
	$stmt = $conn->prepare("UPDATE MyImg SET likes_counter = likes_counter - 1 WHERE img_id = '".$img_id."'");
	$stmt->execute();
	$stmt = $conn->prepare("SELECT likes_counter FROM MyImg WHERE img_id = '".$img_id."'");
	$stmt->execute();
	$data = $stmt->fetch();
}

?>