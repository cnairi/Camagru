<?php

include __DIR__ . '/../incl/header.php';
include __DIR__ . '/../config/database.php';

try {$conn = new PDO("$DB_DSN", $DB_USER, $DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {die("Unsuccessful access to database.");}

if (!isset($_SESSION['loggued_on_user']) ||  $_SESSION['loggued_on_user'] == '') {
    header('Location:../user_mgt/sign_in.php');
  }
?>
<br>
<div class="container has-text-centered">
  <div class="notification">
    <h1 class="title is-size-5">Hi guys!<br> Please select a filter before doing anything here...</h1>
  </div>
</div>
<br>
<br>
<div id="central_block">

<div class="columns">
  <div class="column is-two-thirds">
    <form>
      <input onclick="select_filter();" type="radio" name="filter" value="dog"> <img  src='../img/dog_filter_small.png' style="width:76px;height:120px;">
      <input onclick="select_filter();" type="radio" name="filter" value="rabbit"> <img src='../img/rabbit_filter_small.png' style="width:120px;height:120px;">
      <input onclick="select_filter();" type="radio" name="filter" value="crown"> <img src='../img/flower_crown_filter_small.png' style="width:150px;height:85px;">
    </form>
<br>
<div id="video_play"> 
  <video id="video" autoplay></video>
  <br>
</div>
<button class="button is-success " id="take_snapshot" onclick="snapshot();" style="background-color:#C3A239;" disabled>Take Snapshot</button>
<div class="file" style="display:inline-block;">
  <form action="upload_pic.php" enctype="multipart/form-data" method="post" id="uploadpic">
      <label class="file-label is-size-6 has">
        <input class="file-input" id="fileToUpload" name="fileToUpload" type="file" disabled="disabled">
        <span class="file-cta">
          <span class="file-icon">
            <i class="fas fa-upload"></i>
          </span>
          <span class="file-label">
            or choose a pic...
          </span>
        </span>
      </label> 
      <button class="button is-link is-size-6 has" style="background-color:#C3A239; position:relative; top:5px; display:none;" type="submit" value="Upload Image" name="submit" id="Upload Image">...and upload it !</button>
      <input name="add_filter" id='add_filter' type="hidden">
  </form>
</div>
<br>
<br>
	<canvas id="myCanvas" width="600" height="450"></canvas>
  <br>
  <button class="button is-success is-size-6 has" id="share" onclick="share();" type="button" style="display:none;">Share</button>
<form method="post" accept-charset="utf-8" name="form1">
  <input name="hidden_data" id='hidden_data' type="hidden">
  <input name="hidden_filter" id='hidden_filter' type="hidden">
</form>
</div>
<div class="column">
<div style="max-width:250px; height:900px; overflow:auto;" id="miniature">
<?php
$stmt = $conn->query("SELECT * FROM MyImg WHERE user_id='".$_SESSION['user_id']."' ORDER BY img_id DESC");
while ($data = $stmt->fetch()) {
?>
<img onclick="erase_pic(this.id);" id='<?php echo($data['img_name']) ?>' src= '<?php echo ("../img/" . $data['img_name'])?>'>
<?php
}
?>
</div>
</div>
</div>
<?php
require_once __DIR__ . '/../incl/footer.php';
?>

<script>

	var video = document.querySelector("#video"),
		  webcamStream,
		  canvas,
		  ctx;

  var vplay = document.getElementById("video_play");
  vplay.setAttribute('style', 'width:auto');

  var no_stream = 0;

  var counter = 0;
	if (navigator.mediaDevices.getUserMedia) {        
    	navigator.mediaDevices.getUserMedia({video: true})
  		.then(function(stream) {
    		video.srcObject = stream;
    		webcamStream = stream;
  		})
  		.catch(function(err0r) {
    		console.log("Something went wrong!");
        no_stream = 1;
  		});
  	}

    init();

    function select_filter() {
      var snapshot = document.getElementsByName("filter");
          img = document.createElement("img");
          elem = document.getElementById("active_filter");

      if (elem) {
        elem.parentNode.removeChild(elem);
      }
      if ((snapshot[0].checked == true || snapshot[1].checked == true || snapshot[2].checked) && no_stream == 1) {
        document.getElementById('fileToUpload').disabled = false;
      }
      if ((snapshot[0].checked == true || snapshot[1].checked == true || snapshot[2].checked == true) && no_stream == 0) {
        document.getElementById('take_snapshot').disabled = false;
        document.getElementById('fileToUpload').disabled = false;

      }
      if (snapshot[0].checked == true) {
        img.setAttribute("src", "../img/dog_filter_small.png");
        document.getElementById('add_filter').value = "../img/dog_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 3) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 1.3) + "px";
      }
      else if (snapshot[1].checked == true) {
        img.setAttribute("src", "../img/rabbit_filter_small.png");
        document.getElementById('add_filter').value = "../img/rabbit_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 2.3) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 1.5) + "px";
      }
      else if (snapshot[2].checked == true) {
        img.setAttribute("src", "../img/flower_crown_filter_small.png")
        document.getElementById('add_filter').value = "../img/flower_crown_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 2) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 3) + "px";
      }
      img.setAttribute("id", "active_filter");
      filter_width = parseInt(document.getElementById("video_play").offsetLeft) + parseInt(document.getElementById("video").offsetWidth) / 3;
      filter_height = parseInt(document.getElementById("video_play").offsetTop) + 10;
      img.style.left = filter_width + "px";
      img.style.top = filter_height + "px";
      img.style.position = 'absolute';
      if (no_stream != 1) {
        document.body.appendChild(img);
      }
    }
    
    window.onresize = move_filter;

    function init() {
        canvas = document.getElementById("myCanvas");
        if (no_stream && no_stream === 1) {
          canvas.style.width = document.getElementById("video").offsetWidth + "px";
          canvas.style.height = document.getElementById("video").offsetHeight + "px";
        }
        else if (no_stream === 0) {
          canvas.style.width = '100%';
          canvas.style.height = 'auto';
        }
        ctx = canvas.getContext('2d');

    }

    function move_filter() {
      if (canvas && document.getElementById('chosen_filter')) {
        var img = document.getElementById('chosen_filter');
        filter_width = document.getElementById("myCanvas").offsetLeft + parseInt(document.getElementById("myCanvas").offsetWidth) / 3;
        filter_height = document.getElementById("myCanvas").offsetTop + 10;
        img.style.left = filter_width + "px";
        img.style.top = filter_height + "px"; }
      select_filter();
    }

    function snapshot() {
      if (counter == 0) {
        init(); }
      counter = 1;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      var snapshot = document.getElementsByName("filter");
          img = document.createElement("img");
          elem = document.getElementById("chosen_filter");
          path = window.location.pathname;
          array = path.split("/");
          lastFolder = array[array.length - 3];
      if (elem) {
        elem.parentNode.removeChild(elem);
      }
      if (snapshot[0].checked == true) {
        img.setAttribute("src", "../img/dog_filter_small.png");
        document.getElementById('hidden_filter').value = "/" + lastFolder + "/img/dog_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 3) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 1.3) + "px";
      }
      else if (snapshot[1].checked == true) {
        img.setAttribute("src", "../img/rabbit_filter_small.png");
        document.getElementById('hidden_filter').value = "/" + lastFolder + "/img/rabbit_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 2.3) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 1.5) + "px";
      }
      else if (snapshot[2].checked == true) {
        img.setAttribute("src", "../img/flower_crown_filter_small.png");
        document.getElementById('hidden_filter').value = "/" + lastFolder + "/img/flower_crown_filter.png";
        img.style.width = (parseInt(document.getElementById("video").offsetWidth) / 2) + "px";
        img.style.height = (parseInt(document.getElementById("video").offsetHeight) / 3) + "px";
      }
      img.setAttribute("id", "chosen_filter");
      img.style.position = 'absolute'; 
      document.body.appendChild(img);
      move_filter();
      var dataURL = canvas.toDataURL("image/png");
      document.getElementById('hidden_data').value = dataURL;
      if (canvas) {
        document.getElementById('share').style.display = "inline";
        document.getElementById('share').style.background = "#C3A239";
      }
    }

    $(function() {
    $('#uploadpic input[type="file"]').change(function() {
      init();
      if (canvas) {
        document.getElementById('Upload Image').style.display = "inline";
        document.getElementById('share').style.display = "none";
      }

      var snapshot = document.getElementsByName("filter");
          img = document.createElement("img");
          elem = document.getElementById("chosen_filter");
          path = window.location.pathname;
          array = path.split("/");
          lastFolder = array[array.length - 3];
      if (elem) {
        elem.parentNode.removeChild(elem);
      }
      if (snapshot[0].checked == true) {
        img.setAttribute("src", "../img/dog_filter_small.png");
        img.style.width = (parseInt(document.getElementById("myCanvas").offsetWidth) / 3) + "px";
        img.style.height = (parseInt(document.getElementById("myCanvas").offsetHeight) / 1.3) + "px";
      }
      else if (snapshot[1].checked == true) {
        img.setAttribute("src", "../img/rabbit_filter_small.png");
        img.style.width = (parseInt(document.getElementById("myCanvas").offsetWidth) / 2.5) + "px";
        img.style.height = (parseInt(document.getElementById("myCanvas").offsetHeight) / 1.7) + "px";
      }
      else if (snapshot[2].checked == true) {
        img.setAttribute("src", "../img/flower_crown_filter_small.png");
        img.style.width = (parseInt(document.getElementById("myCanvas").offsetWidth) / 2) + "px";
        img.style.height = (parseInt(document.getElementById("myCanvas").offsetHeight) / 3) + "px";
      }
      img.setAttribute("id", "chosen_filter");
      img.style.position = 'absolute'; 
      document.body.appendChild(img);
      move_filter();
      var dataURL = canvas.toDataURL("image/png");
      document.getElementById('hidden_data').value = dataURL;

      ctx.clearRect(0, 0, canvas.width, canvas.height);
      var file = $(this);
          reader = new FileReader;

      reader.onload = function(e) {
        var img = new Image();
        img.onload = function() {
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file[0].files[0]);
    });
});

    function erase_pic(element) {
      var pic_id = element;
          tab = pic_id.split("/");           
          pic_remove = document.getElementById(pic_id);
          ok=confirm("Are you sure you want to delete this pic?");
      if (ok){
        pic_remove.parentNode.removeChild(pic_remove);
        var xmlhttp = new XMLHttpRequest();
        if (!tab[3]) {
          xmlhttp.open("GET", "erase_pic.php?img_name=" + pic_id, true);
        }
        else if (tab[3]) {
          xmlhttp.open("GET", "erase_pic.php?img_name=" + tab[3], true);
        }
        xmlhttp.send();
      }
    }

    function share() {
      var fd = new FormData(document.forms["form1"]);
      var xhr = new XMLHttpRequest();

      xhr.open('POST', 'upload_data.php', true);

      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          var percentComplete = (e.loaded / e.total) * 100;
          console.log(percentComplete + '% uploaded');
        }
      };

    xhr.onload = function() {
      var photo = this.responseText;
      var gallery = document.getElementById('miniature').innerHTML;
      document.getElementById('miniature').innerHTML = '<img onclick="erase_pic(this.id);" id=' + photo + ' src=' + photo + '>' + document.getElementById('miniature').innerHTML;
    };
    xhr.send(fd);
    
  };

</script>