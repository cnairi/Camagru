<?php

include __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

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

if (isset($_GET['email']) && $_GET['email'] != "") {
  $user_email = $_GET['email'];
}

if (isset($_POST['new_passwd']) && $_POST['new_passwd'] != "" && isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] == $_POST['new_passwd'] && $_POST['submit'] == "Reset password")
{
  if (test_passwd($_POST['new_passwd'])) {
    try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
    catch (Exception $e) {die("Unsuccessful access to database.");}
    $st = $conn->query("SELECT COUNT(*) FROM MyUsers WHERE user_email='".$user_email."'")->fetch();
  	if ($st['COUNT(*)'] == 1) {
      $sql = "UPDATE MyUsers SET user_password='".hash('sha256', $_POST['new_passwd'])."' WHERE user_email='".$user_email."'"; 
      $stmt = $conn->prepare($sql);
      $stmt->execute();
?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Your password has been modified successfully!<br>You can now <strong><a href="sign_in.php" style="text-decoration:none; color:#C3A239;">sign in.</a></strong> You will be automatically redirected to the connexion page in a few seconds.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
     header("Refresh: 5; url=sign_in.php");
	 }
  }
  else {
    ?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, enter a password with a minimum length of <strong>8 characters</strong> which contains at least <strong>one special character</strong>, <strong>one uppercase letter</strong> and <strong>one number</strong><span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
  }
}
else if (isset($_POST['new_passwd']) && $_POST['new_passwd'] != "" && isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] != $_POST['new_passwd'] && $_POST['submit'] == "Reset password")
{
  ?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>You enter two different passwords! Please, try again and use the same new password to confirm.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
}
else if (((isset($_POST['new_passwd']) && $_POST['new_passwd'] == "") || (isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] == "")) && $_POST['submit'] == "Reset password")
{
  ?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, fill in the required fields.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>
 <?php
}
?>
<br>
<section class="section has-background-light">
  <div class="container">
      <h1 class="title">Reset your password</h1>
      <form method="post" action="">
        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="password" placeholder="New password*" name="new_passwd">
          <span class="icon is-left">
          <i class="fas fa-lock fa-lg"></i>
          </span>
          </p>
        </div>

        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="password" placeholder="Confirm password*" name="confirm_passwd">
          <span class="icon is-left">
          <i class="fas fa-lock fa-lg"></i>
          </span>
          </p>
        </div>
        <div class="field">
          <p class="control">
          <input class="button is-success is-size-5 has " type="submit" name="submit" value="Reset password" style="background-color: #C3A239;"/>
        </p>
  </div>
</section>