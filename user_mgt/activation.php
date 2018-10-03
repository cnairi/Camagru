<?php

include __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
catch (Exception $e) {die("Unsuccessful access to database.");}

if (isset($_GET['log']) && isset($_GET['key'])) {
	$user_login = $_GET['log'];
	$user_key = $_GET['key'];
}
$stmt = $conn->prepare("SELECT user_key, user_active FROM MyUsers WHERE user_login like :user_login ");
if ($stmt->execute(array(':user_login' => $user_login)) && $row = $stmt->fetch())
{
	$db_key = $row['user_key'];
	$user_active = $row['user_active'];
	if ($user_active == '1') {
?>

<article class="message is-danger">
  <div class="message-body" style="color:black;">
    Your account has already been activated. Please <strong><a href="/<?php echo ($path[7]); ?>/user_mgt/sign_in.php" style="text-decoration:none;">sign in</a></strong>.
    <br>
    You will be automatically redirected to the connexion page in a few seconds.
  </div>
</article>

<?php
header("Refresh: 5; url=sign_in.php");
} else {
	if ($user_key == $db_key) {
?>

<article class="message" style="background-color: #efdda5;">
  <div class="message-body" style="border-color: #C3A239; color:black;">
    Your account has been activated successfully! You can now <strong><a href="/<?php echo ($path[7]); ?>/user_mgt/sign_in.php" style="text-decoration:none;">sign in</a></strong>. 
    <br>
    You will be automatically redirected to the connexion page in a few seconds.
  </div>
</article>

<?php
		$stmt = $conn->prepare("UPDATE MyUsers SET user_active = 1 WHERE user_login like :user_login ");
		$stmt->bindParam(':user_login', $user_login);
		$stmt->execute();
		header("Refresh: 5; url=sign_in.php");
	} else {
?>

<article class="message is-danger">
  <div class="message-body" style="color:black;">
    Error! Your account couldn't be activated... Please contact us at cnairi@student.42.fr or <strong><a href="/<?php echo ($path[7]); ?>/user_mgt/create_account.php" style="text-decoration:none;">create a new account</a></strong>.
  </div>
</article>

<?php
		}
	}
}
?>