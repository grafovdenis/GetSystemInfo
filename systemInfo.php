<?php
header('Content-Type: application/json');

class get_info
{
    public $os_family = "";
    public $version = "";

    static function cpu_usage()
    {
        $cmd = "wmic cpu get loadpercentage";
        exec($cmd, $result);
        $res = array();
        if ($result != 0) {
            $res = $result[1];
        } else onError();
        return $res;
    }


    static function ram_info()
    {
        $cmd_total = "wmic computersystem get TotalPhysicalMemory";
        $cmd_free = "wmic OS get FreePhysicalMemory /Value";
        exec($cmd_total, $total);
        exec($cmd_free, $free);
        $total = $total[1];
        $free = explode('=', $free[2])[1];
        return array('total' => $total, 'free' => $free);
    }

    static function rom_info()
    {
        $cmd = "wmic logicaldisk get deviceid, freespace,size";
        exec($cmd, $result);
        $res = array('device' => "", 'free_space' => "", 'size' => "");
        for ($i = 0; $i < sizeof($result); $i++) {
            $result[$i] = preg_replace('/\s+/', ' ', $result[$i]);
        }
        for ($i = 1; $i < sizeof($result) - 1; $i++) {
            $tmp = explode(" ", $result[1]);
            $res['device'] = $tmp[0];
            $res['free_space'] = $tmp[1];
            $res['size'] = $tmp[2];
        }
        return $res;
    }


    static function os_info()
    {
        $os_family = explode(" ", php_uname("s"))[0];
        $version = php_uname("r");
        return $os_family . ' ' . $version;
    }

    static function net_info()
    {
        $runCMD = "netstat -e";
        exec($runCMD, $result);
        $result = preg_replace('/\s+/', ' ', $result);
        $bytes = explode(" ", iconv("CP866", "UTF-8", $result[4]));
        $unicast_pockets = explode(" ", iconv("CP866", "UTF-8", $result[5]));
        $non_unicast_pockets = explode(" ", iconv("CP866", "UTF-8", $result[6]));
        return array('bytes' => array('received' => $bytes[1], 'sent' => $bytes[2]),
            'unicast_pockets' => array('received' => $unicast_pockets[2], 'sent' => $unicast_pockets[3]),
            'non_unicast_pockets' => array('received' => $non_unicast_pockets[2], 'sent' => $non_unicast_pockets[3]));
    }
}

function onError()
{
    exit("Something went wrong");
}


$id = $_GET["type"];
switch ($id) {
    case "full":
        $res = array('result' => 'true', 'data' => array());
        $res['data']['cpu_usage'] = get_info::cpu_usage();
        $res['data']['ram_info'] = get_info::ram_info();
        $res['data']['rom_info'] = get_info::rom_info();
        $res['data']['os_info'] = get_info::os_info();
        $res['data']['net_info'] = get_info::net_info();
        echo json_encode($res);
        break;
}
?>