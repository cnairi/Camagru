<?php

include __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

function reset_password_email($email) {
  $subject = "Reset your Camagru password";
  $header = "From: cnairi@student.42.fr";
  $path = explode("/", __DIR__);

  $message = 'Hello there,

  Looks like you lost your password?

  We are here to help. Click on the link below to change your password.

  http://localhost:8100/'.$path[7].'/user_mgt/reset_password.php?email='.urlencode($email).'

  --------------
  This is an automated message - Please do not reply directly to this email.';

  mail($email, $subject, $message, $header);
}

if (isset($_POST['email']) && $_POST['email'] != "" && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && $_POST['submit'] == "Reset password")
{
  $user_email = $_POST['email'];
  try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
  catch (Exception $e) {die("Unsuccessful access to database.");}
  $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_email = :user_email');
  $st->bindValue(':user_email', $user_email, PDO::PARAM_STR);
  $st->execute();
  $st = $st->fetch();
  if ($st['COUNT(*)'] == 1) {
    reset_password_email($_POST['email']);
?>

  <div id="myModal" class="modal-style">
      <div class="modal-cont">
          <p>We've just sent you an email to reset your password!<br>You will be automatically redirected to the connexion page in a few seconds.<span class="close">&times;</span></p>
      </div>
    </div>
  <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>

<?php
    header("Refresh: 5; url=sign_in.php");
  }
  else {
?>

  <div id="myModal" class="modal-style">
      <div class="modal-cont">
          <p>We have no account registered for this address.<br>Please <strong><a href="create_account.php" style="text-decoration:none; color:#C3A239;">create an account</a></strong> to sign in!<span class="close">&times;</span></p>
      </div>
    </div>
  <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>

<?php
  }
}
if (isset($_POST['email']) && ($_POST['email'] == "" || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))  && $_POST['submit'] == "Reset password")
{
?>

  <div id="myModal" class="modal-style">
      <div class="modal-cont">
          <p>Please enter a valid email address to reset your password.<span class="close">&times;</span></p>
      </div>
    </div>
  <script src="/<?php echo ($path[7]); ?>/app.js" type="text/javascript"></script>

<?php
}

?>

<br>
<section class="section has-background-light">
  <div class="container">
      <h1 class="title">Reset password</h1>
      <h2 class="subtitle">Please enter your email address. You will receive an email to reset your password.</h2>
      <form method="post" action="">
          <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="email" placeholder="Email address*" name="email">
          <span class="icon is-left">
          <i class="fas fa-envelope fa-lg"></i>
          </span>
          </p>
        </div>
        <div class="field">
          <p class="control">
          <input class="button is-success is-size-5 has " type="submit" name="submit" value="Reset password" style="background-color: #C3A239;"/>
        </p>
  </div>
</section>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>