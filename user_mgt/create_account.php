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

function confirmation_email($login, $email, $key) {
  $subject = "Activate your Camagru account";
  $header = "From: cnairi@student.42.fr";
  $path = explode("/", __DIR__);

  $message = 'Welcome on Camagru,

  In order to activate your account, please click on the link below
  or copy/paste it in your browser.

  http://localhost/'.$path[4].'/user_mgt/activation.php?log='.urlencode($login).'&key='.urlencode($key).'

  --------------
  This is an automated message - Please do not reply directly to this email.';

  $sent = mail($email, $subject, $message, $header);

  if($sent){
    $user_message = "Your email has been sent.";
  }else{
    $user_message = "There was a problem sending your email.";
  }
}

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != "") {
  header("Location: ../index.php");
}

if (isset($_POST['login']) && $_POST['login'] != '' && isset($_POST['passwd']) && $_POST['passwd'] != '' && isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] != '' && isset($_POST['email']) && $_POST['email'] != '' && $_POST['submit'] == "Create account")
{
  $user_login = htmlentities($_POST['login']);
  $user_password = htmlentities(hash("sha256", $_POST['passwd']));
  $user_email = htmlentities($_POST['email']);
  $user_key = md5(microtime(TRUE)*100000);
  $modal = false;
  try {
    $conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login = :user_login AND user_email = :user_email');
    $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    $st->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $st->execute();
    $st = $st->fetch();
    if ($st['COUNT(*)'] != 0)
    {
      $modal = true;
?>

  <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>User name and Email address already used<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
    }
    $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login = :user_login AND user_email != :user_email');
    $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    $st->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $st->execute();
    $st = $st->fetch();
    if ($st['COUNT(*)'] != 0)
    {
      if ($modal == false) {
        $modal = true;
?>

  <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>User name already used<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
      }
    }
    $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login != :user_login AND user_email = :user_email');
    $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    $st->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $st->execute();
    $st = $st->fetch();
    if ($st['COUNT(*)'] != 0)
    {
      if ($modal == false) {
        $modal = true;
?>

  <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Email address already used<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
      }
    }
    $st = $conn->prepare('SELECT COUNT(*) FROM MyUsers WHERE user_login = :user_login OR user_email = :user_email');
    $st->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    $st->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $st->execute();
    $st = $st->fetch();
    if ($st['COUNT(*)'] == 0)
    {
      if (test_passwd($_POST['passwd']) && $_POST['passwd'] == $_POST['confirm_passwd'] && filter_var($user_email, FILTER_VALIDATE_EMAIL) && strlen($user_login) <= 255) {
        $stmt = $conn->prepare("INSERT INTO MyUsers (user_login, user_password, user_email, user_key) 
        VALUES (:user_login, :user_password, :user_email, :user_key)");
        $stmt->bindParam(':user_login', $user_login);
        $stmt->bindParam(':user_password', $user_password);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':user_key', $user_key);
        $stmt->execute();
        confirmation_email($user_login, $user_email, $user_key);
?>

  <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>A confirmation email has been sent to your address. Please validate your account before signin in.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
      }
      else {
        if ($_POST['passwd'] != $_POST['confirm_passwd']) {
          if ($modal == false) {
            $modal = true;
          ?>
           <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>You enter two different passwords! Please, try again and use the same new password to confirm.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>
 <?php
        }
      } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
          if ($modal == false) {
            $modal = true;
        ?>

 <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please enter a valid email address<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
          }
        } else if (strlen($user_login) > 255) {
          if ($modal == false) {
            $modal = true;
        ?>

 <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Too long username! Please, choose another one shorter than 255 characters.<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
          }
        }
        else {
          if ($modal == false) {
            $modal = true;
        ?>

 <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, enter a password with a minimum length of <strong>8 characters</strong> which contains at least <strong>one special character</strong>, <strong>one uppercase letter</strong> and <strong>one number</strong><span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
}
}
      }
    }
  }
  catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
}

if ((!isset($_POST['login']) || $_POST['login'] == '' || !isset($_POST['passwd']) || $_POST['passwd'] == '' || !isset($_POST['email']) || $_POST['email'] == '') && isset($_POST['submit']) && $_POST['submit'] == "Create account")
{
?>

 <div id="myModal" class="modal-style">
   <div class="modal-cont">
      <p>Please, fill out the required fields<span class="close">&times;</span></p>
    </div>
  </div>
 <script src="/<?php echo ($path[4]); ?>/app.js" type="text/javascript"></script>

<?php
}
?>

<br>
<section class="section has-background-light">
  <div class="container">
      <h1 class="title">Create account</h1>
      <form method="post" action="">
        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="text" placeholder="User name*" name="login">
          <span class="icon is-left">
          <i class="far fa-user fa-lg"></i>
          </span>
          </p>
        </div>

        <div class="field">
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="password" placeholder="Password*" name="passwd">
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
          <p class="control has-icons-left">
          <input class="input is-medium is-size-5" type="email" placeholder="Email address*" name="email">
          <span class="icon is-left">
          <i class="fas fa-envelope fa-lg"></i>
          </span>
          </p>
        </div>
        <div class="field">
          <p class="control">
          <input class="button is-success is-size-5 has " type="submit" name="submit" value="Create account" style="background-color: #C3A239;"/>
        </p>
  </div>
</section>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>