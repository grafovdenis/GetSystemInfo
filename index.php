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
            <input type="submit" name="cpu_usage" value="CPU usage"><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['cpu_usage'])) {
                    echo $cpu_info;
                }
            } ?>
        </li>
        <li>
            <input type="submit" name="ram_info" value="RAM info"><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['ram_info'])) {
                    echo $ram_info;
                }
            } ?>
        </li>
        <li>
            <input type="submit" name="rom_info" value="ROM info"><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['rom_info'])) {
                    echo rom_info();
                }
            } ?>
        </li>
        <li>
            <input type="submit" name="os_info" value="OS info"/><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['os_info'])) {
                    echo $os_info;
                }
            } ?>
        </li>
    </ul>

</form>

</body>

</html>