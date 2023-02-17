<?php
    $data = array();

    $units = ['GB' => 0, 'MB' => 1, 'KB' => 2, 'B' => 3];

    $x = [];

    exec("cat /proc/cpuinfo", $x);

    foreach ($x as $dados) {
        if (mb_strtolower(substr($dados, 0, 10)) === 'model name') {
            $tmp = explode(': ', $dados);

            $data['processador'] = trim($tmp[1]);
        }
    }

    $x = [];

    exec("cat /etc/os-release", $x);

    foreach ($x as $dados) {
        if (mb_strtolower(substr($dados, 0, 11)) === 'pretty_name') {
            $tmp = explode('=', $dados);

            $data['sistema'] = str_replace('"', '', trim($tmp[1]));
        }
    }

    $total_mem = 0;

    $x = [];

    exec("grep MemTotal /proc/meminfo", $x);

    if (count($x)) {
        $tmp = explode(':', $x[0]);

        $total_mem = (int) preg_replace("/\D/", '', $tmp[1]);

        $uni_mem = 'KB';
        $i = 2;
        $num = $total_mem;

        while ($i > 0 && $num > 1024) {
            $num = $num / 1024;

            $i--;

            $uni_mem = array_keys($units)[$i];
        }

        $num = floor($num);

        $data['memoria'] = $num . ' ' . $uni_mem;
    }

    $x = [];

    exec('hostname -I', $x);

    if (count($x)) {
        $data['ip'] = trim($x[0]);
    }

    $x = [];

    exec('cat /sys/class/net/*/address', $x);

    foreach ($x as $dados) {
        if (strlen(str_replace('0', '', preg_replace("/\D/", '', $dados)))) {
            $data['mac'] = trim($dados);
        }
    }

    $x = [];

    exec('cat /proc/sys/kernel/hostname', $x);

    if (count($x)) {
        $data['nome'] = trim($x[0]);
    }

    return $data;
?>