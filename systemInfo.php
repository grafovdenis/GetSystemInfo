<?php
try {
    class cpu
    {
        function cpu_usage()
        {
            if (explode(" ", php_uname("s"))[0] == "Windows") {
                $cmd = "wmic cpu get loadpercentage";
                exec($cmd, $result);
                if ($result[1] == 0) return "Oooops";
                return $result[1];
            } else {
                $result = sys_getloadavg();
                return $result[0];
            }
        }
    }

    class ram
    {
        function ram_info()
        {
            if (explode(" ", php_uname("s"))[0] == "Windows") {
                $cmd_total = "wmic computersystem get TotalPhysicalMemory";
                exec($cmd_total, $total);
                $total = intval($total[1] / pow(2, 20));
                $cmd_free = "wmic OS get FreePhysicalMemory /Value";
                exec($cmd_free, $free);
                $free = intval(explode('=', $free[2])[1] / pow(2, 10));
                return [$total, $free];
            } else {
                $fh = fopen('/proc/meminfo', 'r');
                $total = 0;
                $free = 0;
                while ($line = fgets($fh)) {
                    $pieces = array();
                    if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                        $total = intval($pieces[1] / 1024);
                    }
                    if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
                        $free = intval($pieces[1] / 1024);
                        break;
                    }
                }
                fclose($fh);

                return [$total, $free];
            }
        }
    }

    class rom
    {
        function rom_info()
        {
            if (explode(" ", php_uname("s"))[0] == "Windows") {
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
            } else {
                echo "Всего: " . intval(disk_total_space(".") / pow(2, 30)) . " ГБ" . "<br>";
                echo "Доступно: " . intval(disk_free_space(".") / pow(2, 30)) . " ГБ";
            }
        }
    }

    class os
    {
        public $os_family = "";
        public $version = "";

        public function __construct()
        {
            $this->os_family = explode(" ", php_uname("s"))[0];
            $this->version = php_uname("r");
        }

        public function get_os()
        {
            return "<strong>Хост запущен на: </strong>" . $this->os_family . " " . $this->version;
        }
    }

    $id = $_GET["q"];
    switch ($id) {
        case "cpu":
            echo '<strong>Процессор хоста загружен на </strong>' . (new cpu)->cpu_usage() . '%';
            break;
        case "os":
            echo (new os)->get_os();
            break;
        case "ram":
            echo "<strong>Всего оперативной памяти: </strong>" . (new ram)->ram_info()[0] . " МБ"
                . "<br>" . "<strong>Доступно оперативной памяти: </strong>" . (new ram)->ram_info()[1] . " МБ";
            break;
        case "rom":
            echo (new rom)->rom_info();
            break;
    }

} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "\n";
}
?>