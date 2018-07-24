<!DOCTYPE html>
<html>
<head>
    <title>Text Exercise</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>
</head>

<body>
<div id="info">

</div>
<div id="get_info">
    <button type="button" id="full">Get info</button>
</div>
<script>
    $('#full').click(function () {
        let id = this.id;
        $.ajax({
            url: 'systemInfo.php',
            data: {"type": id},
            type: 'get',
            success: function (result) {
                if (result['result']) {
                    if (id === "full") {
                        console.log(JSON.stringify(result));
                        $('#info').html('Ваш процессор загружен на ' + result['data']['cpu_usage'] + "%</br>" +
                            'Ваша OC: ' + result['data']['os_info'] + "</br>" +
                            '<strong>Оперативная память: </strong>' + "</br>" +
                            'Всего: ' + Math.round(result['data']['ram_info']['total'] / Math.pow(2, 20)) + " МБ</br>" +
                            'Доступно: ' + Math.round(result['data']['ram_info']['free'] / Math.pow(2, 10)) + " МБ</br>" +
                            '<strong>Постоянная память: </strong>' + "</br>" +
                            '<strong>' + result['data']['rom_info']['device'] + '</strong>' + " Всего: " + Math.round(result['data']['rom_info']['size'] / Math.pow(2, 20)) + ' МБ ' +
                            "Доступно: " + Math.round(result['data']['rom_info']['free_space'] / Math.pow(2, 20)) + ' МБ'
                        );
                    } else $('#' + id).innerHTML = JSON.stringify(result);
                }
            }
        })
    });
</script>

</body>
</html>