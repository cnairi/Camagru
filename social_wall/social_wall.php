<?php

require_once __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}
$pic_per_page = 12;
$st = $conn->prepare('SELECT COUNT(*) FROM MyImg');
$st->execute();
$st = $st->fetch();
$tot_pic = $st['COUNT(*)'];
$tot_pages = ceil($tot_pic / $pic_per_page);

if (isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $tot_pages) {
  $_GET['page'] = intval($_GET['page']);
  $current_page = intval($_GET['page']);
}
else {
  $current_page = 1;
}

$start = ($current_page - 1) * $pic_per_page;

?>
<br>
<div id="wall_pictures" width="100%" height="auto">
	<div class="tile is-ancestor">
<?php
  $i = 1;
	$stmt = $conn->prepare("SELECT * FROM MyImg ORDER BY img_id DESC LIMIT :start, :pic_per_page");
  $stmt->bindParam(':start', $start, PDO::PARAM_INT);
  $stmt->bindParam(':pic_per_page', $pic_per_page, PDO::PARAM_INT);
  $stmt->execute();
	while ($data = $stmt->fetch()) {
    if ($i <= 3) {
        $st = $conn->prepare("SELECT user_login FROM MyUsers WHERE user_id = :user_id");
        $st->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $st->execute();
        $new = $st->fetch()
?>

  <div class="tile is-parent" style="border:none;">
    <article class="tile is-child media" style="border:none;">
      <div class="media-left">
      <a href="/<?php echo($path[7])?>/social_wall/interact.php?img_id=<?php echo($data['img_id'])?>&user_id=<?php echo($data['user_id'])?>">
        <figure class="image">
          <img width="50" height="auto" id='<?php echo($data['img_name']) ?>' src= '<?php echo ("/" . $path[7] . "/img/" . $data['img_name'])?>'>
        </figure>
      </a>
        <div class="media-content">
          <div class="content">
            <p>
              <strong><?php
              echo($new['user_login']) ?></strong><small> @<?php echo($new['user_login'])?></small>
            </p>
          </div>
          
        <a href="/<?php echo($path[7])?>/social_wall/interact.php?img_id=<?php echo($data['img_id'])?>&user_id=<?php echo($data['user_id'])?>">
        <nav class="level is-mobile"> 
        <div class="level-left">

<?php
$img_id = intval($data['img_id']);
if (isset($_SESSION['user_id'])) {
$user_id = intval($_SESSION['user_id']); } else {
  $user_id = "";
}
$st = $conn->prepare('SELECT COUNT(*) FROM Likes WHERE img_id = :img_id AND user_id = :user_id');
$st->bindValue(':img_id', $img_id, PDO::PARAM_STR);
$st->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$st->execute();
$st = $st->fetch();
if ($st['COUNT(*)'] == 0) {
  ?>
            <span class="icon is-small">
              <i style="color:#C3A239;" class="far fa-heart"></i>
            </span>
            <?php
          }
          else {
            ?>
            <span class="icon is-small">
              <i style="color:#C3A239;" class="fas fa-heart"></i>
            </span>
            <?php
          }
          ?>
            <span class="icon is-small">
              <i style="color:#C3A239; position:relative; left:10px;" class="far fa-comment"></i>
            </span>
        </div>
      </nav>
    </a>
    </article>
  </div>
<?php
$i++;
}
if ($i > 3) {
?>
  </div>
  <div class="tile is-ancestor">
<?php
$i = 1;
}
}
?>
</div>
<br>
<div id="page" style="width:100%;">
<?php
  for ($j = 1; $j <= $tot_pages; $j++) {
    if ($j == $current_page) {
      echo '<p style="background-color:#C3A239; border:black;" class="pagination-link is-current">'.$j.'</p>';
    }
    else {
    echo '<a style="border:black;" class="pagination-link" href="social_wall.php?page='.$j.'">'.$j.'</a>';
    }
  }
?>
</div>
</div>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>