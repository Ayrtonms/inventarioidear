<?php
    $data = array();

    $units = ['GB' => 0, 'MB' => 1, 'KB' => 2, 'B' => 3];

    $x = [];

    exec("wmic CPU get NAME", $x);

    if (count($x) >= 2) {
        $data['processador'] = trim($x[1]);
    }

    $x = [];

    exec("wmic os get Caption", $x);

    if (count($x) >= 2) {
        $data['sistema'] = trim($x[1]);
    }

    $total_mem = 0;

    $x = [];

    exec("wmic MEMORYCHIP get Capacity", $x);

    for ($i = 1; $i < count($x); $i++) {
        $total_mem += (int) trim($x[$i]);
    }

    $uni_mem = 'B';
    $i = 3;
    $num = $total_mem;

    while ($i > 0 && $num > 1024) {
        $num = $num / 1024;

        $i--;

        $uni_mem = array_keys($units)[$i];
    }

    $num = floor($num);

    $data['memoria'] = $num . ' ' . $uni_mem;

    $x = [];

    exec('ipconfig /all', $x);

    $find_ip = false;
    $find_mac = false;

    foreach ($x as $dados) {
        if (stripos($dados, 'Nome do host') !== false ) {
            $tmp = explode('. :', $dados);

            $data['nome'] = trim($tmp[1]);
        }

        if (stripos($dados, 'Sufixo DNS') !== false || stripos($dados, 'Connection-specific') !== false) {
            $find_ip = true;
            $find_mac = true;
        }

        if (
            $find_ip
            &&
            (
                stripos($dados, 'IPv4') !== false
                ||
                stripos($dados, 'IP.') !== false
                ||
                stripos($dados, 'IP .') !== false
                ||
                stripos($dados, 'IP Address') !== false
                ||
                stripos($dados, 'IPv4 Address') !== false
            )
        ) {
            $tmp = explode('. :', $dados);

            $tmp = trim($tmp[1]);

            $data['ip'] = explode('(', $tmp)[0];

            $find_ip = false;
        }

        if (
            $find_mac
            &&
            (
                stripos($dados, 'sico.') !== false
                ||
                stripos($dados, 'sico .') !== false
                ||
                stripos($dados, 'Physical Address') !== false
            )
        ) {
            $tmp = explode('. :', $dados);

            $data['mac'] = trim($tmp[1]);

            $find_mac = false;
        }
    }

    return $data;
?>