
<?php

include __DIR__ . '/../config/database.php';

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}

$com_id = intval($_GET['comment_id']);
$img_id = intval($_GET['hidden_img_id']);
$comment_user = htmlentities($_GET['hidden_comment_user']);
$stmt = $conn->prepare('DELETE FROM Comments WHERE comment_id=:comment_id');
$stmt -> execute(array("comment_id"=>$com_id));
$stmt->execute();
header("Location: interact.php?img_id=".$img_id."&user_login=".$comment_user."");

?>