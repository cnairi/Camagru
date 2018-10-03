<?php

include __DIR__ . '/config/setup.php';
include __DIR__ . '/incl/header.php';

?>
			<section class="section has-background-light">
				<div class="container">
					<div class="columns">
						<div class="column">
							<article class="media notification has-text-white" style="background-color: #C3A239;">
								<figure class="media-left">
									<span class="icon is-medium">
										<i class="fas fa-globe"></i>
									</span>
								</figure>
								<div class="media-content">
									<div class="content">
										<h1 class="title is-size-4"> SHARE</h1>
										<p class="is-size-5">
											Camagru is a free platform where you can take pictures, add fun filters and share them when you're signed in! Sharing is caring :)
										</p>
									</div>
								</div>
							</article>
						</div>
						<div class="column">
							<article class="media notification has-text-white" style="background-color: #C3A239;">
								<figure class="media-left">
									<span class="icon is-medium">
										<i class="fas fa-heart"></i>
									</span>
								</figure>
								<div class="media-content">
									<div class="content">
										<h1 class="title is-size-4"> LIKE</h1>
										<p class="is-size-5">
											Sign in to express some love to your friends by liking their creation on Camagru. Let's spread friendship around the world!
										</p>
									</div>
								</div>
							</article>
						</div>
						<div class="column">
							<article class="media notification has-text-white" style="background-color: #C3A239;">
								<figure class="media-left">
									<span class="icon is-medium">
										<i class="fas fa-comments"></i>
									</span>
								</figure>
								<div class="media-content">
									<div class="content">
										<h1 class="title is-size-4"> COMMENT</h1>
										<p class="is-size-5">
											When loggued in, you can also write a few words to your beloved friends on their photos. Don't wait to become part of our community!
										</p>
									</div>
								</div>
							</article>
						</div>
					</div>
				</div>
			</section>
		<br>
			<section class="section">
				<div class="container">
					<h1 class="title is-size-4 has-text-centered">TRENDING PICS</h1>
<?php 
try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
catch (Exception $e) {die("Unsuccessful access to database.");}
$stmt = $conn->prepare("SELECT COUNT(*) FROM MyImg");
$stmt->execute();
$data = $stmt->fetch();
if ($data['COUNT(*)'] >= 5) {
?>
	<div class="tile is-ancestor">
<?php
$stmt = $conn->prepare("SELECT img_id, img_name, user_id FROM MyImg ORDER BY likes_counter DESC LIMIT 0, 5");
$stmt->execute();
while ($data = $stmt->fetch()) {
?>
<div class="tile is-parent" style="border:none;">
    <article class="tile is-child media" style="border:none;">
      <div class="media-left">
      <a href="/<?php echo ($path[7]); ?>/social_wall/interact.php?img_id=<?php echo($data['img_id'])?>&user_id=<?php echo($data['user_id'])?>">
        <figure class="image">
          <img class="reponsive" id='<?php echo($data['img_name']) ?>' src= '<?php echo ("/" . $path[7] . "/img/" . $data['img_name'])?>'>
        </figure>
      </a>
        </div>
    </article>
 </div>
<?php
}
?>
</div>
</section>
<?php
}
else {
?>
</div>
</section>
<div id="loader">
<img src="/<?php echo ($path[7]); ?>/img/cat_tail_loader.gif">
</div>
<br>
<div>
<p style="text-align:center;">Our community is still growing... Wait for it!</p>
</div>
<?php
}
require_once 'incl/footer.php';
?>