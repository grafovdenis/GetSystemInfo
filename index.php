<!DOCTYPE html>
<html>
<head>
    <title>Text Exercise</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
<div id="info">
<!--TODO обработка json'а-->
</div>
<div id="get_info">
    <button type="button" onclick="getInfo(this.id)" id="full">Get info</button>
</div>
<script>
    //TODO переписать на jquery
    function getInfo(id) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (id === "full") {
                    document.getElementById("info").innerHTML = this.responseText;
                } else document.getElementById(id).innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "systemInfo.php?q=" + id, true);
        xhttp.send(id)
    }
</script>

</body>
</html>