<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Camagru</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"
  				integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  				crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="<?php $path = explode("/", __DIR__); echo '/' . $path[4] . '/css/style_sheet.css' ?>" media="all"/>
	</head>
	<body style="margin:0;">
		<div class="page" style="display:flex; flex-direction:column; min-height: 100vh;">
			<div class="columns is-mobile is-marginless heading has-text-weight-bold">
<?php
session_start();
$path = explode("/", __DIR__);
if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != "") {
?>
	<div class="column left">
	<ul class="menu-principal mobile">
		<li>
		<a href="#">
			<figure class="navbar-item mobile image has-text-black center mobile">
				<i class="fas fa-bars mobile" style="width: 1rem; height:1rem;"></i>
			</figure>
		</a>
			<ul>
				<li><a href="<?php echo '/' . $path[4] . '/camera/camera.php' ?>" class="mobile">NEW POST</a></li>
				<li><a href="<?php echo '/' . $path[4] . '/social_wall/social_wall.php' ?>" class="mobile">SOCIAL WALL</a></li>
				<li><a href="<?php echo '/' . $path[4] . '/user_mgt/modif_account.php' ?>" class="mobile"><?php print ($_SESSION['loggued_on_user']) ?></a></li>
				<li><a href="<?php echo '/' . $path[4] . '/user_mgt/log_out.php' ?>" class="mobile">LOG OUT</a></li>
			</ul>
		</li>
	</ul>
	</div>
	<div class="column right">
		<a href="<?php echo '/' . $path[4] . '/camera/camera.php' ?>" style="text-decoration:none"><p class="navbar-item desktop has-text-black desktop">NEW POST</p></a>
		<a href="<?php echo '/' . $path[4] . '/social_wall/social_wall.php' ?>" style="text-decoration:none"><p class="navbar-item desktop has-text-black desktop">Social wall</p></a>
		<a href="<?php echo '/' . $path[4] . '/user_mgt/modif_account.php' ?>" style="text-decoration:none"><p class="navbar-item desktop has-text-black desktop"><?php print ($_SESSION['loggued_on_user']) ?></p></a>
		<a href="<?php echo '/' . $path[4] . '/user_mgt/log_out.php' ?>" style="text-decoration:none"><p class="navbar-item has-text-black">Log out</p></a>
<?php
}
else {
?>
	<div class="column left">
	<ul class="menu-principal mobile">
		<li>
		<a href="#">
			<figure class="navbar-item mobile image has-text-black center mobile">
				<i class="fas fa-bars mobile" style="width: 1rem; height:1rem;"></i>
			</figure>
		</a>
			<ul>
				<li><a href="<?php echo '/' . $path[4] . '/user_mgt/sign_in.php' ?>" class="mobile">SIGN IN</a></li>
				<li><a href="<?php echo '/' . $path[4] . '/social_wall/social_wall.php' ?>" class="mobile">SOCIAL WALL</a></li>
			</ul>
		</li>
	</ul>
	</div>
	<div class="column right">
	<a href="<?php echo '/' . $path[4] . '/social_wall/social_wall.php' ?>" style="text-decoration:none"><p class="navbar-item desktop has-text-black desktop">Social wall</p></a>
	<a href="<?php echo '/' . $path[4] . '/user_mgt/sign_in.php' ?>" style="text-decoration:none"><p class="navbar-item desktop has-text-black desktop">Sign in</p></a>
<?php
}
?>
	</div>
	</div>
	<p class="logo"><a href="<?php echo '/' . $path[4] . '/index.php' ?>"><img src="<?php echo '/' . $path[4] . '/img/camagru.png' ?>"></a></p>
	<br>
	<div class="site-content" style="flex: 1;">
	<section class="midpage-block">