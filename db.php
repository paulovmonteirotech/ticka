<?php
require 'conf.php';

$connection = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// Verifica se a conexão falhou
if ($connection->connect_error) {
    die("Erro de conexão: " . $connection->connect_error);
}

// CORREÇÃO: Define o charset da conexão para UTF-8
if (!$connection->set_charset("utf8mb4")) {
    // utf8mb4 é preferível para suporte completo a Unicode
    printf("Erro ao carregar o conjunto de caracteres utf8mb4: %s\n", $connection->error);
    exit();
}
?>
