<?php
    $env = require('variaveis.php');

    function prepararTexto($texto) {
        $env = require('variaveis.php');

        if ($env['PROD']) return utf8_decode($texto);

        return $texto;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.0 405');
        die();
    }

    header('Content-type: application/json');

    $errors = array();

    try {
        require_once('validation_exception.php');

        $db = require_once('conexao.php');

        $errors = require_once('validator.php');

        if (count($errors)) {
            throw new ValidationException(compact('errors'));
        }
    } catch (ValidationException $ex) {
        //header('HTTP/1.0 422');

        echo json_encode($ex->errors);

        exit;
    } catch (Exception $ex) {
        //header('HTTP/1.0 500');

        $message = $env['TESTES'] ? $ex->getMessage() : 'Server Error';

        echo json_encode(compact('message'));

        file_put_contents('error_log.txt', (date('Y-m-d H:i:s') . ': ' . $ex->getMessage() . "\n"), FILE_APPEND);

        exit;
    }

    $error = false;

    try {
        $query = $db->prepare('
            select id_maquina
            from maquina
            where upper(nome) = upper(:nome)
        ');

        $query->execute(['nome' => prepararTexto(trim($_POST['nome']))]);

        $result = $query->fetchAll();

        $id_maquina = count($result) ? $result[0]->id_maquina : null;

        $id_processador = null;

        while (true) {
            $query = $db->prepare('
                select id_processador
                from esp_processador
                where upper(descricao) = upper(:processador)
            ');

            $query->execute(['processador' => prepararTexto(trim($_POST['processador']))]);

            $result = $query->fetchAll();

            if (count($result)) {
                $id_processador = $result[0]->id_processador;

                break;
            }

            $query = $db->prepare('insert into esp_processador (descricao) values (:processador)');

            $query->execute(['processador' => prepararTexto(trim($_POST['processador']))]);
        }

        $id_so = null;

        while (true) {
            $query = $db->prepare('
                select id_so
                from esp_so
                where upper(descricao) = upper(:sistema)
            ');

            $query->execute(['sistema' => prepararTexto(trim($_POST['sistema']))]);

            $result = $query->fetchAll();

            if (count($result)) {
                $id_so = $result[0]->id_so;

                break;
            }

            $query = $db->prepare('insert into esp_so (descricao) values (:sistema)');

            $query->execute(['sistema' => prepararTexto(trim($_POST['sistema']))]);
        }

        $id_memoria = null;

        while (true) {
            $query = $db->prepare('
                select id_memoria
                from esp_memoria
                where upper(descricao) = upper(:memoria)
            ');

            $query->execute(['memoria' => prepararTexto(trim($_POST['memoria']))]);

            $result = $query->fetchAll();

            if (count($result)) {
                $id_memoria = $result[0]->id_memoria;

                break;
            }

            $query = $db->prepare('insert into esp_memoria (descricao) values (:memoria)');

            $query->execute(['memoria' => prepararTexto(trim($_POST['memoria']))]);
        }

        if ($id_maquina) {
            $query = $db->prepare("
                update maquina set
                id_memoria = :id_memoria,
                id_so = :id_so,
                id_processador = :id_processador,
                mac = :mac,
                ip = :ip,
                ultimo_contato = now()
                where id_maquina = :id_maquina
            ");

            $query->execute([
                'id_memoria' => $id_memoria,
                'id_so' => $id_so,
                'id_processador' => $id_processador,
                'mac' => prepararTexto(trim($_POST['mac'])),
                'ip' => prepararTexto(trim($_POST['ip'])),
                'id_maquina' => $id_maquina
            ]);
        } else {
            $query = $db->prepare("
                insert into maquina (
                    id_fornecedor,
                    num_pedido,
                    nota_fiscal,
                    nome,
                    responsavel,
                    tombamento,
                    email,
                    marca,
                    modelo,
                    valor,
                    id_local,
                    id_disco,
                    id_memoria,
                    id_so,
                    id_processador,
                    mac,
                    ip,
                    ultimo_contato
                ) values (
                    null,
                    '',
                    '',
                    :nome,
                    '',
                    '',
                    '',
                    '',
                    '',
                    0,
                    null,
                    null,
                    :id_memoria,
                    :id_so,
                    :id_processador,
                    :mac,
                    :ip,
                    now()
                )
            ");

            $query->execute([
                'nome' => prepararTexto(trim($_POST['nome'])),
                'id_memoria' => $id_memoria,
                'id_so' => $id_so,
                'id_processador' => $id_processador,
                'mac' => prepararTexto(trim($_POST['mac'])),
                'ip' => prepararTexto(trim($_POST['ip']))
            ]);
        }
    } catch (ValidationException $ex) {
        $error = true;

        //header('HTTP/1.0 422');

        echo json_encode($ex->errors);
    } catch (Exception $ex) {
        $error = true;

        //header('HTTP/1.0 500');

        $message = $env['TESTES'] ? $ex->getMessage() : 'Server Error';

        echo json_encode(compact('message'));

        file_put_contents('error_log.txt', (date('Y-m-d H:i:s') . ': ' . $ex->getMessage() . "\n"), FILE_APPEND);
    }

    if (!$error) {
        echo 'true';
    }
?>