<?php

require_once __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != "") {
  header("Location: ../index.php");
}

if (isset($_POST['login']) && $_POST['login'] != '' && isset($_POST['passwd']) && $_POST['passwd'] != '' && $_POST['submit'] == "Login")
{
  $user_login = htmlentities($_POST['login']);
  $user_password = htmlentities(hash('sha256', $_POST["passwd"]));
  try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (Exception $e) {die("Unsuccessful access to database.");}
  $st = $conn->prepare('SELECT user_active, user_id FROM MyUsers WHERE user_login like :user_login');
  $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
  $st->execute();
  if ($row = $st->fetch())
  {
    $active = $row['user_active'];
    $user_id = $row['user_id'];
  }
  $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login = :user_login AND user_password = :user_password');
  $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
  $st->bindValue(':user_password', $user_password, PDO::PARAM_STR);
  $st->execute();
  $st = $st->fetch();
  if ($st['COUNT(*)'] == 1)
  {
    if ($active == '1') {
    $_SESSION['loggued_on_user'] = htmlentities($_POST['login']);
    $_SESSION['user_id'] = $user_id;
    header("Location: ../index.php");
  }
  else {
  ?>
   <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, activate your account thanks to the link you receive by email before trying to sign in.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>
 <?php
  }
  }
  else {
?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Unknown User name or wrong password<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>
<?php
}
}
if (((isset($_POST['login']) && empty($_POST['login']) && isset($_POST['passwd']) && $_POST['submit'] == "Login")) || (isset($_POST['passwd']) && empty($_POST['passwd']) && isset($_POST['login']) && $_POST['submit'] == "Login")) {
?>
<div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please fill the User name and Password fields to access your account<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>
<?php
}

?>

<br>
<section class="section has-background-light">
  <div class="container">
      <h1 class="title">Sign in</h1>
      <h2 class="subtitle">or <strong><a href="/<?php echo ($path[4]); ?>/user_mgt/create_account.php" style="text-decoration:none; color: #C3A239;">create account</a></strong></h2>
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
          <p class="control">
          <input class="button is-success is-size-5 has " type="submit" name="submit" value="Login" style="background-color: #C3A239;"/>
        </p>
  </div>
  <h2 class="subtitle is-size-6"><a href="forgot_password.php" style="text-decoration:none; color: darkgray;">Forgot password ?</a></h2>
</section>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>