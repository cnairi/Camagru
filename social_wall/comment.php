<?php

include __DIR__ . '/../config/database.php';

if (isset($_POST['submit'])) {

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}

$stmt = $conn->prepare("INSERT INTO Comments (user_id, img_id, comment_content) 
VALUES (:user_id, :img_id, :comment_content)");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':img_id', $img_id);
$stmt->bindParam(':comment_content', $comment_content);
$user_id = intval($_POST['hidden_comment_user']);
$img_id = intval($_POST['hidden_img_id']);
$comment_content = htmlentities($_POST['comment']);
$stmt->execute();
}

$stmt = $conn->prepare("SELECT user_email, notifications, myusers.user_login FROM myusers INNER JOIN myimg ON myusers.user_id = myimg.user_id WHERE myimg.img_id = '".$_POST['hidden_img_id']."'");
$stmt->execute();
$data = $stmt->fetch();
$email = $data['user_email'];
$user_login = $data['user_login'];
$notifs = $data['notifications'];

$stmt = $conn->prepare("SELECT user_login FROM myusers WHERE user_id = '".intval($_POST['hidden_comment_user'])."'");
$stmt->execute();
$data = $stmt->fetch();
$login = $data['user_login'];

if ($notifs == 1) {
	confirmation_email($user_login, $login, $email);
}

function confirmation_email($user_login, $login, $email) {
  $subject = "New comment on Camagru";
  $header = "From: cnairi@student.42.fr";
  $path = explode("/", __DIR__);

  $message = 'Hi there,

  It seems that '.$login.' has just left you a new comment on one of your pics.

  Please, sign in to read it:
  http://localhost/'.$path[4].'/social_wall/interact.php?img_id='.intval($_POST['hidden_img_id']).'&user_login='.$user_login.'

  --------------
  This is an automated message - Please do not reply directly to this email.';

  mail($email, $subject, $message, $header);
}

header("Location: interact.php?img_id=".$_POST['hidden_img_id']."&user_id=".$_POST['hidden_comment_user']."");

?>