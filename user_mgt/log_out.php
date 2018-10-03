<?php
	
	session_start();
	if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != "") {
		$_SESSION['loggued_on_user'] = "";
		$_SESSION['user_id'] = "";
		header("Location: ../index.php");
	}

?>