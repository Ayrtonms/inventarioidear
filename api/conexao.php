<?php
    $url = "localhost";
    $usr = "root";
    $pas = "123";
    $dab = "inventarioidear";

    $db = null;

    try {
        $db = new PDO("mysql:host=$url;charset=utf8;dbname=$dab", $usr, $pas, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ));
    } catch (Exception $ex) {
        echo 'Erro ao acessar a base de dados.';
        //echo $ex->getMessage();
        exit;
    }

    return $db;
?>