<?php

require_once __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['loggued_on_user']) || $_SESSION['loggued_on_user'] == "") {
?>
<article class="message" style="background-color: #efdda5;">
  <div class="message-body" style="border-color: #C3A239; color:black;">
    Please <strong><a href="<?php echo '/' . $path[4] . '/user_mgt/sign_in.php' ?>" style="text-decoration:none;">sign in</a></strong> to join the Camagru community. 
    <br>
    You will be automatically redirected to the connexion page in a few seconds.
  </div>
</article>
<?php
		header("Refresh: 5; url=/".$path[4]."/user_mgt/sign_in.php");
		exit();
} else {
	try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {die("Unsuccessful access to database.");}

	$img_id = intval($_GET['img_id']);
	$stmt = $conn->prepare('SELECT user_id, img_name, likes_counter FROM MyImg WHERE img_id = :img_id');
    $stmt->bindValue(':img_id', $img_id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
	$img_name = $data['img_name'];
	$user_login = htmlentities($_SESSION['loggued_on_user']);
	$likes_counter = $data['likes_counter'];
	$user_id = $_SESSION['user_id'];
	$user_orig_id = $data['user_id'];

	$stmt = $conn->prepare("SELECT COUNT(*) FROM Likes WHERE user_id = :user_id AND img_id = :img_id");
	$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->bindValue(':img_id', $img_id, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetch();
	$like_active = $data['COUNT(*)'];

	$stmt = $conn->prepare("SELECT user_login FROM Myusers WHERE user_id = :user_id");
	$stmt->bindValue(':user_id', $user_orig_id, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetch();
	$user_name = $data['user_login'];
?>


<div class="container has-text-centered">
    <h1 class="title is-size-6 has-text-weight-semibold">Hey <?php echo ($_SESSION['loggued_on_user']) ?>!<br> Show @<?php echo ($user_name) ?> some love by sharing, commenting or liking this post <span class="icon"><i class="fas fa-heart"></i></span></h1>
</div>
<br>
<div id="interaction_block" style="width:100%; margin:auto;">
	<img id='<?php echo(htmlentities($_GET['img_name'])) ?>' src= '<?php echo ("/" . $path[4] . "/img/" . $img_name)?>'>
	<?php 
	if ($like_active == 0) {
	?>
	<span id="like" onclick="like()"; class="icon is-medium"><i style="color:#4a4a4a;" class="far fa-lg fa-heart"></i></span>
	<?php
	} else {
	?>
	<br>
	<span id="like" onclick="unlike()"; class="icon is-medium"><i style="color:#4a4a4a;" class="fas fa-lg fa-heart"></i></span>
	<?php
	}
	?>
	<span class="icon is-medium" onclick="comment()";><i class="far fa-lg fa-comment"></i></span></h1>

	<div id="likes_display" class="container">
    	<?php if ($likes_counter > 0) { echo ($likes_counter); } if ($likes_counter == 1) { echo (" person liked this post"); } else if ($likes_counter > 1) { echo (" people liked this post"); } ?>
	</div>
	<div id="comments-block" style="position:relative; top:5px;">
		<div style="width:100%; height:75px; overflow:auto">
	<?php
	$stmt = $conn->prepare("SELECT comment_id, user_id, comment_content FROM Comments WHERE img_id = '".intval($_GET['img_id'])."'");
	$stmt->execute();
	while ($data = $stmt->fetch()) {
		$st = $conn->prepare("SELECT user_login FROM Myusers WHERE user_id = '".$data['user_id']."'");
		$st->execute();
		$new = $st->fetch()
	?>
        <span id="<?php echo ($data['comment_id'])?>" onclick="erase(this);"><strong><?php echo ($new['user_login'])?></strong> <?php echo ($data['comment_content']) ?></span>
        <br>
	<?php
	}
	?>
</div>
	<form style="display:none;" action="comment.php" id="usrform" name="usrform" method="post">
  		<textarea style="position:relative; top:10px;" id="comment_area"  class="textarea" placeholder="Please, add your comment here..." name="comment" form="usrform"></textarea>
  		<input name="hidden_comment_user" id='hidden_comment_user' type="hidden">
  		<input name="hidden_img_id" id='hidden_img_id' type="hidden">
  		<button class="button is-link is-size-6 has-text-weight-semibold" style="background-color:#C3A239; position:relative; top:20px;" id="comment_button" value="Post comment" type="submit" name="submit">...and post it when u're ready!</button>
  	</form>
</div>
</div>

<?php
}
require_once __DIR__ . '/../incl/footer.php';
?>
<script>
	function like() {
		var img_id = '<?php echo ($img_id) ?>';
			xmlhttp = new XMLHttpRequest();
			user_id = "<?php echo ($user_id) ?>";
			like_active = '<?php echo ($like_active) ?>';
		xmlhttp.open("GET", "like.php?img_id=" + img_id + "&user_id=" + user_id, true);
    	xmlhttp.send();
    	document.getElementById('like').setAttribute( "onClick", "unlike()");
       	document.getElementById("like").innerHTML = '<i class="fas fa-lg fa-heart"></i>';
       	if (like_active == 0)
       		document.getElementById("likes_display").innerHTML = '<?php if ($likes_counter == 0) { echo ("1 person liked this post"); } else { echo ($likes_counter + 1); echo (" people liked this post"); } ?>';
       	else
       		document.getElementById("likes_display").innerHTML = '<?php if ($likes_counter <= 1) { echo ("1 person liked this post"); } else { echo ($likes_counter); echo (" people liked this post"); } ?>';
   }

   	function unlike() {
		var img_id = '<?php echo ($img_id) ?>';
			user_id = "<?php echo ($user_id) ?>";
			xmlhttp = new XMLHttpRequest();
			like_active = '<?php echo ($like_active) ?>';
		xmlhttp.open("GET", "unlike.php?img_id=" + img_id + "&user_id=" + user_id, true);
    	xmlhttp.send();
    	document.getElementById('like').setAttribute( "onClick", "like()");
       	document.getElementById("like").innerHTML = '<i class="far fa-lg fa-heart"></i>';
       	if (like_active == 0) {
       		document.getElementById("likes_display").innerHTML = '<?php if ($likes_counter <= 0) { echo (""); } else if ($likes_counter == 1) { echo ("1 person liked this post"); } else if ($likes_counter > 1) { echo ($likes_counter); echo (" people liked this post"); } ?>';
       	}
       	else {
       		document.getElementById("likes_display").innerHTML = '<?php if ($likes_counter - 1 <= 0) { echo (""); } else if ($likes_counter - 1 == 1) { echo ("1 person liked this post"); } else if ($likes_counter - 1 > 1) { echo ($likes_counter - 1); echo (" people liked this post"); } ?>';
       	}
   }

	function comment() {
		document.getElementById('usrform').style.display = "inline";
		document.getElementById('hidden_comment_user').value = "<?php echo ($user_id) ?>";
		document.getElementById('hidden_img_id').value = '<?php echo ($img_id) ?>';
	}

	function erase(element) {
      var	com = element;
      		com_id = element.getAttribute('id');
      ok=confirm("Are you sure you want to delete this comment?");
      if (ok){
        $( "#comments-block" ).load(window.location.href + " #comments-block" );
        var xmlhttp = new XMLHttpRequest();
       		xmlhttp.open("GET", "erase_comment.php?comment_id=" + com_id + "&user_login=" + "<?php echo $_SESSION['loggued_on_user'] ?>" + "&img_id=" + '<?php echo ($img_id) ?>', true);
       		xmlhttp.send();
      }
    }
</script>