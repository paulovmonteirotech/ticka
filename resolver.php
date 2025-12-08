<?php
// resolver.php - Script para marcar um ticket como resolvido

include 'db_pdo.php'; 

if (isset($_GET['id']) && isset($_GET['tabela'])) {
    $id = $_GET['id'];
    $tabela = $_GET['tabela'];
    
    // Lista de tabelas permitidas (segurança)
    $tabelas_validas = ['TICKA_MEET',
    'TICKA_SAGICON',
    'TICKA_IMPRESSORA_SCAN',
    'TICKA_NEW_USER',
    'TICKA_REMOVE_USER',
    'TICKA_FORGOT_PASS',
    'TICKA_UNLOCK_SITE',
    'TICKA_OTHER'];

    if (in_array($tabela, $tabelas_validas) && is_numeric($id)) {
        try {
            // Prepara e executa o UPDATE
            $sql = "UPDATE $tabela SET RESOLVIDO = TRUE WHERE ID = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Redireciona de volta para o dashboard após a resolução
            header("Location: view.php?status=resolvido");
            exit();
            
        } catch (PDOException $e) {
            die("Erro ao resolver o ticket: " . $e->getMessage());
        }
    } else {
        die("Parâmetros inválidos.");
    }
} else {
    header("Location: view.php");
    exit();
}
?>