<?php
// db_connect.php - Configuração e conexão com o banco de dados

require 'conf.php';

try {
    $conn = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8", $db_username, $db_password);
    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Falha na conexão com o banco de dados: " . $e->getMessage());
}

?>