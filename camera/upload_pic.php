<?php

include __DIR__ . '/../config/database.php';

session_start();

$target_dir = __DIR__ . '/../img/selfie';
$file_name = mktime() . basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $file_name;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if ($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
}

if ($_FILES["fileToUpload"]["size"] > 5000000) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
	echo "Sorry, only JPG, JPEG, PNG & GIF are allowed.";
	$uploadOk = 0;
}

if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
} else {
	$image = imagecreatefromstring(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));

	echo " " . $image . " ";

	$mini_left = imagecreatefrompng('../img/' . $_POST['add_filter']);

	echo " " . $mini_left . " ";

	$filter_width = imageSX($mini_left);
	$filter_height = imageSY($mini_left);
	$img_width = imageSX($image);
	$img_height = imageSY($image);

	imageCopyResized($image,$mini_left,$img_width / 3,20,0,0,$img_width,$img_height,$filter_width,$filter_height);

	imagepng($image, $target_file);

	try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
	catch (Exception $e) {die("Unsuccessful access to database.");}

	$stmt = $conn->prepare("INSERT INTO MyImg (img_name, user_id, user_login) 
	VALUES (:img_name, :user_id, :user_login)");
	$stmt->bindParam(':img_name', $img_name);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->bindParam(':user_login', $user_login);
	$img_name = "selfie" . $file_name;
	$user_id = $_SESSION['user_id'];
	$user_login = $_SESSION['loggued_on_user'];
	$stmt->execute();
}

header('Location: camera.php');

?>