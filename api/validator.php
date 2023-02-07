<?php
    $errors = array();

    if (!isset($_POST['nome']) || !strlen(trim($_POST['nome']))) {
        $errors['nome'] = 'O campo "nome" é obrigatório.';
    } else if (strlen(trim($_POST['nome'])) > 100) {
        $errors['nome'] = 'O campo "nome" é longo demais.';
    }

    if (!isset($_POST['processador']) || !strlen(trim($_POST['processador']))) {
        $errors['processador'] = 'O campo "processador" é obrigatório.';
    } else if (strlen(trim($_POST['processador'])) > 100) {
        $errors['processador'] = 'O campo "processador" é longo demais.';
    }

    if (!isset($_POST['sistema']) || !strlen(trim($_POST['sistema']))) {
        $errors['sistema'] = 'O campo "sistema operacional" é obrigatório.';
    } else if (strlen(trim($_POST['sistema'])) > 100) {
        $errors['sistema'] = 'O campo "sistema operacional" é longo demais.';
    }

    if (!isset($_POST['memoria']) || !strlen(trim($_POST['memoria']))) {
        $errors['memoria'] = 'O campo "memória" é obrigatório.';
    } else if (strlen(trim($_POST['memoria'])) > 100) {
        $errors['memoria'] = 'O campo "memória" é longo demais.';
    }

    if (!isset($_POST['mac']) || !strlen(trim($_POST['mac']))) {
        $errors['mac'] = 'O campo "endereço MAC" é obrigatório.';
    } else if (strlen(trim($_POST['mac'])) > 17) {
        $errors['mac'] = 'O campo "endereço MAC" é longo demais.';
    }

    if (!isset($_POST['ip']) || !strlen(trim($_POST['ip']))) {
        $errors['ip'] = 'O campo "endereço IP" é obrigatório.';
    } else if (strlen(trim($_POST['ip'])) > 15) {
        $errors['ip'] = 'O campo "endereço IP" é longo demais.';
    }

    return $errors;
?>