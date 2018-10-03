<?php

include __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
catch (Exception $e) {die("Unsuccessful access to database.");}

function test_passwd($str) {
  $spec = false;
  $upper = false;
  $num = false;

  if (strlen($str) >= 8) {
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str))
      $spec = true;
    if (preg_match("/[0-9]+/", $str))
      $num = true;
    if (preg_match("/[A-Z]+/", $str))
      $upper = true;
  }
  else
    return(false);
  if ($spec == true && $num == true && $upper == true)
    return (true);
  else
    return (false);
}

if (((isset($_POST['login']) && $_POST['login'] != "") || (isset($_POST['passwd']) && $_POST['passwd'] != "") || (isset($_POST['email']) && $_POST['email'] != "") || (isset($_POST['notif']))) && $_POST['submit'] == "Modify")
{
  $new_login = htmlentities($_POST['login']);
  $user_login = $_SESSION['loggued_on_user'];
  $new_password = htmlentities(hash('sha256', $_POST['passwd']));
  $new_email = htmlentities($_POST['email']);
	$modal = false;
  	if (isset($_POST['login']) && $_POST['login'] != "")
  	{
      $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login = :new_login');
      $st->bindValue(':new_login', $new_login, PDO::PARAM_STR);
      $st->execute();
      $st = $st->fetch();
  		if ($st['COUNT(*)'] == 1) {
  			$modal = true;
 ?>
 	<div id="myModal" class="modal-style">
   		<div class="modal-cont">
      		<p>User name already used, please chose another one<span class="close">&times;</span></p>
    	</div>
  	</div>
 	<script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
  		}
  		else {
        if (strlen($new_login) <= 255) {
        $stmt = $conn->prepare("UPDATE MyUsers SET user_login = :new_login WHERE user_login = :user_login");
        $stmt->bindParam(':new_login', $new_login);
        $stmt->bindParam(':user_login', $user_login);
        $stmt->execute();
       	$_SESSION['loggued_on_user'] = $new_login;
        $user_login = $new_login;
       } else {
          if ($modal == false) {
            $modal = true;
?>

  <div id="myModal" class="modal-style">
      <div class="modal-cont">
          <p>Too long username! Please, choose another one shorter than 255 characters.<span class="close">&times;</span></p>
      </div>
    </div>
  <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
<?php
      }
  	}
  }
}
    if (isset($_POST['passwd']) && $_POST['passwd'] != "")
  	{
  		if (test_passwd($_POST['passwd']) && $_POST['passwd'] == $_POST['confirm_passwd']) {
        $stmt = $conn->prepare("UPDATE MyUsers SET user_password = :new_password WHERE user_login = :user_login");
        $stmt->bindParam(':new_password', $new_password);
        $stmt->bindParam(':user_login', $user_login);
        $stmt->execute();
		} else {
      if ($modal == false) {
				$modal = true;
        if ($_POST['passwd'] == $_POST['confirm_passwd']) {
?> 
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, enter a password with a minimum length of <strong>8 characters</strong> which contains at least <strong>one special character</strong>, <strong>one uppercase letter</strong> and <strong>one number</strong><span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
}
else {
  ?>

<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>You enter two different passwords! Please, try again and use the same new password to confirm.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>

<?php
}
			}
		}
	}
	if (isset($_POST['email']) && $_POST['email'] != "") {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      if ($modal == false) {
        $modal = true;
?>

  <div id="myModal" class="modal-style">
      <div class="modal-cont">
          <p>Please enter a valid email address<span class="close">&times;</span></p>
      </div>
    </div>
  <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>

<?php
        }
      }
      $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_email = :new_email');
      $st->bindValue(':new_email', $new_email, PDO::PARAM_STR);
      $st->execute();
      $st = $st->fetch();
  		if ($st['COUNT(*)'] == 1) {
  			if ($modal == false) {
  				$modal = true;
?>
 	<div id="myModal" class="modal-style">
   		<div class="modal-cont">
      		<p>Email address already used, please use another one<span class="close">&times;</span></p>
    	</div>
  	</div>
 	<script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
  		}
  	}
  		else if ($st['COUNT(*)'] == 0 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("UPDATE MyUsers SET user_email = :new_email WHERE user_login = :user_login");
        $stmt->bindParam(':new_email', $new_email);
        $stmt->bindParam(':user_login', $user_login);
        $stmt->execute();
  		}
 	}
  if (isset($_POST['notif']))
    {
      if ($_POST['notif'] == "on") {
        $stmt = $conn->prepare("UPDATE MyUsers SET notifications = 1 WHERE user_login = :user_login");
        $stmt->bindParam(':user_login', $user_login);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE MyUsers SET notifications = 0 WHERE user_login = :user_login");
        $stmt->bindParam(':user_login', $user_login);
        $stmt->execute();
    }
  }
	if ($modal == false) {
?>
<div id="myModal" class="modal-style">
	<div class="modal-cont">
		<p>Information successfully modified<span class="close">&times;</span></p>
    </div>
  </div>
<script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
<?php
}
}
?>
<br>
<section class="section has-background-light">
  <div class="container">
      <h1 class="title">Modify your account information</h1>
      <h2 class="subtitle is-6">You can change one or more information. If you need to change your password, please confirm before saving it.</h2>
      <form method="post" action="">
        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="text" placeholder="User name" name="login">
          <span class="icon is-left">
          <i class="far fa-user fa-lg"></i>
          </span>
          </p>
        </div>

        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="password" placeholder="Password" name="passwd">
          <span class="icon is-left">
          <i class="fas fa-lock fa-lg"></i>
          </span>
          </p>
        </div>

        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="password" placeholder="Confirm password" name="confirm_passwd">
          <span class="icon is-left">
          <i class="fas fa-lock fa-lg"></i>
          </span>
          </p>
        </div>

        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="email" placeholder="Email address" name="email">
          <span class="icon is-left">
          <i class="fas fa-envelope fa-lg"></i>
          </span>
          </p>
        </div>

<?php
      $user_login = htmlentities($_SESSION['loggued_on_user']);
      $st = $conn->prepare('SELECT notifications FROM MyUsers WHERE user_login = :user_login');
      $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
      $st->execute();
      $st = $st->fetch();
      if ($st['notifications'] == 1) {
?>
        <div class="field">
          <label class="label">Notifications</label>
          <div class="control">
            <div class="select">
              <select name="notif">
                <option value="on" selected>Notifications on</option>
                <option value="off">Notifications off</option>
              </select>
            </div>
          </div>
        </div>
<?php
      } else if ($st['notifications'] == 0) {
?>
        <div class="field">
          <label class="label">Notifications</label>
          <div class="control">
            <div class="select">
              <select name="notif">
                <option value="on">Notifications on</option>
                <option value="off" selected>Notifications off</option>
              </select>
            </div>
          </div>
        </div>
<?php
        }
?>

        <div class="field">
          <p class="control">
          <input class="button is-success is-size-5 has " type="submit" name="submit" value="Modify" style="background-color: #C3A239;"/>
        </p>
  </div>
</section>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>