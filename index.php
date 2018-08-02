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
    //TODO отобразить все ядра, все диски, все интерфейсы
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
                        $('#info').html('<strong>Ваш процессор загружен на </strong>' + result['data']['cpu_usage']['total'] + "%</br>" +
                            '<strong>Ваша OC: </strong>' + result['data']['os_info'] + "</br>" +
                            '<strong>Оперативная память: </strong>' + "</br>" +
                            'Всего: ' + Math.round(result['data']['ram_info']['total'] / Math.pow(2, 20)) + " МБ</br>" +
                            'Доступно физически: ' + Math.round(result['data']['ram_info']['free'] / Math.pow(2, 10)) + " МБ</br>" +
                            '<strong>Постоянная память: </strong>' + "</br>" +
                            '<strong>' + result['data']['rom_info'][0]['device'] + '</strong>' + " Всего: " + Math.round(result['data']['rom_info'][0]['size'] / Math.pow(2, 20)) + ' МБ ' +
                            "Доступно: " + Math.round(result['data']['rom_info'][0]['free_space'] / Math.pow(2, 20)) + ' МБ</br>' +
                            '<strong>Трафик:</strong></br>' +
                            'Интерфейс: ' + result['data']['net_info'][0]['name'] + "</br>" +
                            'Получено: ' + Math.round(result['data']['net_info'][0]['bytes']['received'] / Math.pow(2, 20)) + ' МБ</br>' +
                            'Отправленно: ' + Math.round(result['data']['net_info'][0]['bytes']['sent'] / Math.pow(2, 20)) + ' МБ</br>' +
                            '<strong><i>Одноадрессные пакеты: </i></br></strong>' +
                            'Получено: ' + result['data']['net_info'][0]['unicast_packets']['received'] + '</br>' +
                            'Отправленно: ' + result['data']['net_info'][0]['unicast_packets']['sent'] + '</br>'
                        );
                    } else $('#' + id).innerHTML = JSON.stringify(result);
                }
            }
        })
    });
</script>
</body>
</html>