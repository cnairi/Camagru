<?php

include __DIR__ . '/../config/database.php';

session_start();

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
catch (Exception $e) {die("Unsuccessful access to database.");}

$user_id_ok = $_SESSION['user_id'];
$upload_dir = __DIR__ . '/../img/selfie';
$img = $_POST['hidden_data'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file_name = mktime() . ".png";
$file = $upload_dir . $file_name;

header("Content-type: image/png");
 
$image = imagecreatefromstring($data);

$mini_left = imagecreatefrompng('/Applications/MAMP/htdocs' . $_POST['hidden_filter']);

$filter_width = imageSX($mini_left);
$filter_height = imageSY($mini_left);
$img_width = imageSX($image);
$img_height = imageSY($image);

imageCopyResized($image,$mini_left,$img_width / 3,20,0,0,$img_width,$img_height,$filter_width,$filter_height);

$stmt = $conn->prepare("INSERT INTO MyImg (img_name, user_id, user_login) 
VALUES (:img_name, :user_id, :user_login)");
$stmt->bindParam(':img_name', $img_name);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':user_login', $user_login);
$img_name = "selfie" . $file_name;
$user_id = $_SESSION['user_id'];
$user_login = htmlentities($_SESSION['loggued_on_user']);
$stmt->execute();

$success = imagepng($image, $file);
echo ("../img/" . $img_name);

?>