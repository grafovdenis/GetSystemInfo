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
<div id="info"></div>
<div id="get_info">
    <button type="button" id="full">Get info</button>
</div>
<script type="text/javascript">
    $('#full').click(function () {
        let id = this.id;
        get_data(id);
    });

    function get_data(id) {
        $.ajax({
            url: 'systemInfo.php',
            data: {"type": id},
            type: 'get',
            success: function (result) {
                if (result['result']) {
                    if (id === "full" || id === "update") {
                        let cores = result['data']['cpu_usage']['cores'].join("%, ") + "%";
                        let rom_info = result['data']['rom_info'];
                        let rom = '';
                        for (let el of rom_info) {
                            rom += '<strong>' + el['device'] + '</strong>' +
                                ' Всего: ' + Math.round(el['size'] / Math.pow(2, 20)) + ' МБ ' +
                                'Доступно: ' + Math.round(el['free_space'] / Math.pow(2, 20)) + ' МБ</br>'
                        }
                        let net_info = result['data']['net_info'];
                        let interfaces = '';
                        for (let el of net_info) {
                            interfaces += '<strong>Интерфейс: <i>' + el['name'] + "</i></strong></br>" +
                                'Получено: ' + Math.round(el['bytes']['received'] / Math.pow(2, 20)) + ' МБ</br>' +
                                'Отправленно: ' + Math.round(el['bytes']['sent'] / Math.pow(2, 20)) + ' МБ</br>' +
                                '<i>Одноадрессные пакеты: </i></br>' +
                                'Получено: ' + el['unicast_packets']['received'] + '</br>' +
                                'Отправленно: ' + el['unicast_packets']['sent'] + '</br>';
                        }
                        $('#info').html(
                            '<strong>Ваш процессор загружен на </strong>' + result['data']['cpu_usage']['total'] + "%</br>" +
                            '<strong>Ядра: </strong>' + cores + '</br>' +
                            '<strong>Ваша OC: </strong>' + result['data']['os_info'] + "</br>" +
                            '<strong>Оперативная память: </strong>' + "</br>" +
                            'Всего: ' + Math.round(result['data']['ram_info']['total'] / Math.pow(2, 20)) + " МБ</br>" +
                            'Доступно физически: ' + Math.round(result['data']['ram_info']['free'] / Math.pow(2, 10)) + " МБ</br>" +
                            '<strong>Постоянная память: </strong>' + "</br>" + rom +
                            '<strong>Трафик:</strong></br>' + interfaces
                        );
                        if (id !== "update") {
                            setInterval(function () {
                                get_data("update");
                            }, 10000);
                            $('#' + id).html('Update').attr('id', "update");
                        }
                    }
                } else console.log(result['error']);
            }
        })
    }
</script>
</body>
</html>