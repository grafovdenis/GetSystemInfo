<?php include_once "systemInfo.php" ?>
<!DOCTYPE html>
<html>
<head>
    <title>Text Exercise</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<form>
    <ul>
        <li>
            <input type="checkbox" name="cpu_usage"/>CPU usage<br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['cpu_usage'])) {
                    echo '<strong>Процессор хоста загружен на </strong>' . (new cpu)->cpu_usage() . '%';
                }
            } ?>
        </li><br>
        <li>
            <input type="checkbox" name="ram_info"/>RAM info<br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['ram_info'])) {
                    echo "<strong>Всего оперативной памяти: </strong>" . (new ram)->ram_info()[0] . " МБ" . "<br>" . "<strong>Доступно оперативной памяти: </strong>" . (new ram)->ram_info()[1] . " МБ";
                }
            } ?>
        </li><br>
        <li>
            <input type="checkbox" name="rom_info"/>ROM info<br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['rom_info'])) {
                    echo (new rom)->rom_info();
                }
            } ?>
        </li><br>
        <li>
            <input type="checkbox" name="os_info"/>OS info<br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['os_info'])) {
                    echo (new os)->get_os();
                }
            } ?>
        </li><br>
        <li>
            <input type="submit" value="Отобразить">
        </li><br>
    </ul>
</form>

</body>

</html>