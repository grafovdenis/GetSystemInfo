<?php

function cpu_usage()
{
    $cmd = "wmic cpu get loadpercentage";
    exec($cmd, $result);
    return $result[1];
}

$cpu_info = "<strong>Ваш процессор загружен на </strong>" . cpu_usage() . "%";

function ram_info()
{
    $cmd_total = "wmic computersystem get TotalPhysicalMemory";
    exec($cmd_total, $total);
    $total = intval($total[1] / pow(2, 20));
    $cmd_free = "wmic OS get FreePhysicalMemory /Value";
    exec($cmd_free, $free);
    $free = intval(explode('=', $free[2])[1] / pow(2, 10));
    return [$total, $free];
}

$ram_info = "<strong>Всего оперативной памяти: </strong>" . ram_info()[0] . " МБ" . "<br>" . "<strong>Доступно оперативной памяти: </strong>" . ram_info()[1] . " МБ";

function rom_info()
{
    //$cmd = "wmic logicaldisk list brief";
    $cmd = "wmic logicaldisk get deviceid, freespace,size";
    exec($cmd, $result);
    for ($i = 0; $i < sizeof($result); $i++) {
        $result[$i] = preg_replace('/\s+/', ' ', $result[$i]);
    }
    echo "<table>";
    foreach ($result as $str) {
        echo "<tr>";
        foreach (explode(" ", $str) as $item) {
            if (is_numeric($item)) {
                $item /= pow(2, 30);
                $item = intval($item) . " ГБ";
            } else $item = "<strong>$item</strong>";
            echo "<td>" . $item . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
function getOS()
{
    global $user_agent;
    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/webos/i' => 'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

$os_info = "<strong>Operating System: </strong>" . getOS();

?>
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