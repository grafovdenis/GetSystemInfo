<?php
function cpu_usage()
{
    $cmd = "wmic cpu get loadpercentage";
    exec($cmd, $result);
    return $result[1];
}

$cpu_info = "<strong>Процессор хоста загружен на </strong>" . cpu_usage() . "%";

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

$os_info = "<strong>Хост запущен на: </strong>" . explode(" ", php_uname("s"))[0] . ' ' . php_uname("r");
?>