<?php
header('Content-Type: application/json');

class get_info
{
    public $os_family = "";
    public $version = "";

    static function cpu_usage()
    {
        //почему-то относительные адреса не работают
        $cores_info = shell_exec('C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe -executionpolicy bypass C:\OSPanel\domains\test\cores.ps1 2>&1');
        $cores_info = explode("</br>", $cores_info);
        $total = explode(" ", $cores_info[0])[1];
        if ($total == 0) {
            onError('Zero CPU usage');
        }
        $cores = array();
        for ($i = 0; $i < sizeof($cores_info) - 2; $i++) {
            $cores[$i] = explode(" ", $cores_info[$i + 1])[1];
        }
        return array('total' => $total, 'cores' => (object)$cores);
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
        $res = array();
        for ($i = 0; $i < sizeof($result); $i++) {
            $result[$i] = preg_replace('/\s+/', ' ', $result[$i]);
        }
        for ($i = 1; $i < sizeof($result) - 1; $i++) {
            $tmp = explode(" ", $result[$i]);
            $res[$i - 1]['device'] = $tmp[0];
            $res[$i - 1]['free_space'] = $tmp[1];
            $res[$i - 1]['size'] = $tmp[2];
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
        $stats = shell_exec('C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe Get-NetAdapterStatistics 2>&1');
        $stats = iconv("CP866", "UTF-8", $stats);
        $stats = array_slice(explode("\n", $stats), 3, sizeof($stats) - 4);
        $result = array();
        $i = 0;
        foreach ($stats as $el) {
            preg_match("/\D+/", $el, $tmp);
            $result[$i]['name'] = trim($tmp[0]);
            preg_match_all("!\d+!", $el, $nums);
            $result[$i]['bytes']['received'] = $nums[0][0];
            $result[$i]['bytes']['sent'] = $nums[0][2];
            $result[$i]['unicast_packets']['received'] = $nums[0][1];
            $result[$i]['unicast_packets']['sent'] = $nums[0][3];
            $i++;
        }
        return $result;
    }
}

function onError($msg)
{
    echo json_encode(array('result' => false, 'error' => "error: " . ($msg != "") ? $msg : 'Something went wrong'));
    exit();
}


$id = $_GET["type"];
switch ($id) {
    case "full":
        $time_start = microtime(true);
        $res = array('result' => true, 'data' => array());
        $res['data']['os_info'] = get_info::os_info();
        $res['data']['cpu_usage'] = get_info::cpu_usage();
        $res['data']['ram_info'] = get_info::ram_info();
        $res['data']['rom_info'] = get_info::rom_info();
        $res['data']['net_info'] = get_info::net_info();
        $res['debug']['response_time'] = microtime(true) - $time_start;
        echo json_encode($res);
        break;
    case "update":
        $time_start = microtime(true);
        $res = array('result' => true, 'data' => array());
        $res['data']['os_info'] = get_info::os_info();
        $res['data']['cpu_usage'] = get_info::cpu_usage();
        $res['data']['ram_info'] = get_info::ram_info();
        $res['data']['rom_info'] = get_info::rom_info();
        $res['data']['net_info'] = get_info::net_info();
        $res['debug']['response_time'] = microtime(true) - $time_start;
        echo json_encode($res);
        break;
}
?>