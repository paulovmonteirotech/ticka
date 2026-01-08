<?php
session_start();

// Grupo que esta aplicação (STI) exige
$grupoRequerido = "CN=STI,CN=Users,DC=crfrj,DC=ad";

// 1. Se não estiver logado
if (!isset($_SESSION['user'])) {
    header("Location: https://servicos.crf-rj.org.br/intra/sti/login.html");
    exit;
}

// 2. Se a sessão de grupos não foi criada ou não contém o grupo necessário
$gruposDoUsuario = $_SESSION['user_groups'] ?? [];

if (!in_array($grupoRequerido, $gruposDoUsuario)) {
    // Limpa a sessão (opcional, mas recomendado)
    session_unset();
    session_destroy();
    
    // Redireciona para login
    header("Location: https://exemplo.org.br/ticka/login.html");
    exit;
}

// Se passou por tudo, acesso liberado para STI ✅
?>