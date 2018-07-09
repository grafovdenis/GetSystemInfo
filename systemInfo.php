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
            $cmd_total = "wmic computersystem get TotalPhysicalMemory";
            exec($cmd_total, $total);
            $total = intval($total[1] / pow(2, 20));
            $cmd_free = "wmic OS get FreePhysicalMemory /Value";
            exec($cmd_free, $free);
            $free = intval(explode('=', $free[2])[1] / pow(2, 10));
            return [$total, $free];
        }
    }

    class rom
    {
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
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "\n";
}
?>