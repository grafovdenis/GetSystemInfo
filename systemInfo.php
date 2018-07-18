<?php
header('Content-Type: application/json');
try {
    function onError()
    {
        exit("Something went wrong");
    }

    class cpu
    {
        function cpu_usage()
        {
            $cmd = "wmic cpu get loadpercentage";
            exec($cmd, $result);
            $res = array();
            if ($result != 0) {
                $res = array('cpu usage' => $result[1]);
            } else onError();
            return $res;
        }
    }

    class ram
    {
        function ram_info()
        {
            $cmd_total = "wmic computersystem get TotalPhysicalMemory";
            $cmd_free = "wmic OS get FreePhysicalMemory /Value";
            exec($cmd_total, $total);
            exec($cmd_free, $free);
            $total = $total[1];
            $free = explode('=', $free[2])[1];
            return array('ram info' => array('total' => $total, 'free' => $free));
        }
    }

    class rom
    {
        function rom_info()
        {
            $cmd = "wmic logicaldisk get deviceid, freespace,size";
            exec($cmd, $result);
            $res =
                array('rom info' => array('devices' => array(), 'free space' => array(), 'size' => array()));
            for ($i = 0; $i < sizeof($result); $i++) {
                $result[$i] = preg_replace('/\s+/', ' ', $result[$i]);
            }
            for ($i = 1; $i < sizeof($result) - 1; $i++) {
                $tmp = explode(" ", $result[1]);
                array_push($res['rom info']['devices'], $tmp[0]);
                array_push($res['rom info']['free space'], $tmp[1]);
                array_push($res['rom info']['size'], $tmp[2]);
            }
            return $res;
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

        public function os_info()
        {
            return array('os info' =>
                array('family' => $this->os_family, 'version' => $this->version));
        }
    }

    $id = $_GET["q"];
    switch ($id) {
        case "full":
            $res = array('result' => 'true', 'data' => array());
            array_push($res['data'], (new cpu)->cpu_usage());
            array_push($res['data'], (new ram)->ram_info());
            array_push($res['data'], (new rom)->rom_info());
            array_push($res['data'], (new os)->os_info());
            echo json_encode($res);
            break;
    }

} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "\n";
}
?>