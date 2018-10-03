var modal = document.getElementById('myModal');

var close_modal = document.getElementsByClassName("close")[0];

var path = document.location.href;
var file_name = path.substring(path.lastIndexOf( "/" )+1);

close_modal.onclick = function() {
    modal.style.display = "none";
    document.location.href= file_name;
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.location.href= file_name;
    }
}