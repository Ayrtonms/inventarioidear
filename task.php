<?php
    $env = include((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'variaveis.php'));

    $url = $env['API'] . '/cadastro.php';

    $data = array();

    if (substr(mb_strtoupper(PHP_OS), 0, 3) === 'WIN') {
        $data = include((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'windows' . DIRECTORY_SEPARATOR . 'dados_computador.php'));
    } else {
        $data = include((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'linux' . DIRECTORY_SEPARATOR . 'dados_computador.php'));
    }

    if (!count(array_keys($data))) {
        echo 'Houve um erro ao buscar os dados da máquina.';
        exit;
    }

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) { 
        echo 'Houve um erro ao se conectar com a api.';
        exit;
    } else {
        echo $result;
    }
?>