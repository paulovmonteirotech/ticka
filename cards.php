<?php
// cards.php - Lógica de busca e exibição dos cards

// --- FUNÇÕES AUXILIARES (Definidas no topo para segurança em chamadas AJAX) ---

// Função auxiliar para obter cores
function get_color($tipo) {
    $colors = [
        'meet' => '#28a745', 
        'sagicon' => '#ffc107',
        'new_user' => '#007bff',
        'remove_user' => '#dc3545',
        'forgot_pass' => '#fd7e14',
        'unlock_site' => '#6f42c1',
        'impressora_scan' => '#20c997',
        'other' => '#17a2b8',
    ];
    return $colors[$tipo] ?? '#6c757d';
}

// Função auxiliar para exibir detalhes específicos de cada tipo de chamado
function exibir_detalhes_especificos($tipo, $row) {
    // Campos que JÁ foram exibidos e devem ser ignorados na iteração abaixo
    $campos_basicos_ignorados = ['ID', 'DATA_CRIACAO', 'REQUERENTE', 'SETOR', 'R_EMAIL', 'RESOLVIDO'];

    // Itera sobre todas as colunas da linha
    foreach ($row as $coluna => $valor) {
        
        // 1. Ignora colunas já exibidas, nulas ou vazias
        if (in_array($coluna, $campos_basicos_ignorados) || $valor === null || $valor === '') {
            continue;
        }

        // 2. Formata o nome da coluna para exibição (ex: NOVO_FUNCIONARIO -> Novo Funcionário)
        $nome_formatado = ucwords(strtolower(str_replace('_', ' ', $coluna)));
        
        // 3. Exibe o parágrafo
        echo "<p><strong>$nome_formatado:</strong> " . nl2br(htmlspecialchars($valor)) . "</p>";
    }
}

// --- CONEXÃO E LÓGICA DE BUSCA ---

// Se a variável $conn não estiver definida (ou seja, se este arquivo foi chamado diretamente via AJAX),
// incluímos a conexão aqui. Se já foi incluído, pulamos esta etapa.
if (!isset($conn)) {
    // IMPORTANTE: Troque 'db_connect.php' pelo nome correto do seu arquivo de conexão
    include 'db_pdo.php'; 
}

$tabelas = [
    'TICKA_MEET',
    'TICKA_SAGICON',
    'TICKA_IMPRESSORA_SCAN',
    'TICKA_NEW_USER',
    'TICKA_REMOVE_USER',
    'TICKA_FORGOT_PASS',
    'TICKA_UNLOCK_SITE',
    'TICKA_OTHER',
];

$tickets_encontrados = 0;

foreach ($tabelas as $nome_tabela) {
    // Apenas selecionamos tickets que NÃO estão resolvidos
    $sql = "SELECT * FROM $nome_tabela WHERE RESOLVIDO = FALSE ORDER BY ID ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        $tickets_encontrados += count($results);

        // Prepara o nome do tipo para o CSS (tudo minúsculo e sem prefixo)
        $tipo_css = strtolower(str_replace('TICKA_', '', $nome_tabela));

        foreach ($results as $row) {
            // -- INÍCIO DO CARD HTML --
            echo "<div class='card tipo-$tipo_css'>";
                echo "<div class='card-header'>";
                    // Exibe o ID e o tipo do chamado
                    echo "<h3>Ticket #" . $row['ID'] . "</h3>";
                    echo "<span class='card-tipo' style='background-color: " . get_color($tipo_css) . ";'>$tipo_css</span>";
                echo "</div>";

                // Informações básicas (comuns)
                echo "<p><strong>Setor:</strong> " . htmlspecialchars($row['SETOR'] ?? 'N/A') . "</p>";
                echo "<p><strong>Requerente:</strong> " . htmlspecialchars($row['REQUERENTE'] ?? 'N/A') . "</p>";
                echo "<p><strong>Criado em:</strong> " . date('d/m/Y H:i', strtotime($row['DATA_CRIACAO'])) . "</p>";
                echo "<p><strong>E-mail:</strong> " . htmlspecialchars($row['R_EMAIL'] ?? 'N/A') . "</p>";
                
                // --- INFORMAÇÕES ESPECÍFICAS ---
                echo "<hr style='border: none; border-top: 1px dashed #ccc; margin: 15px 0;'>";
                
                exibir_detalhes_especificos($tipo_css, $row);

                // Rodapé do Card com o botão de resolver
                echo "<div class='card-footer'>";
                    // Link de ação para resolver o chamado
                    echo "<a href='resolver.php?id=" . $row['ID'] . "&tabela=$nome_tabela'>Marcar como Resolvido &raquo;</a>";
                echo "</div>";
            echo "</div>";
            // -- FIM DO CARD HTML --
        }
    }
}


// Adiciona a mensagem de fallback, mas a esconde se houver cards
if ($tickets_encontrados === 0) {
    echo '<div class="sem-tickets">🎉 Parabéns! Não há tickets pendentes.</div>';
}

// O resto do script é intencionalmente vazio, garantindo que apenas os cards sejam impressos.
?>