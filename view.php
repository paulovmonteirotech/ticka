<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tickets Abertos - Dashboard</title>
    <style>

        /* style.css */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f6;
    color: #333;
    padding: 20px;
    margin: 0;
}

header {
    text-align: center;
    margin-bottom: 40px;
}

h1 { color: #007bff; }
h2 { color: #6c757d; font-size: 1.2em; font-weight: 400; }

#dashboard-container {
    /* Mantém o grid responsivo */
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
    gap: 25px;
    max-width: 1200px;
    margin: 0 auto;
}

.card {
    background-color: #fff;
    height: auto; 
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-left: 5px solid; /* Linha de cor lateral para destaque */
    transition: transform 0.2s;
}

.card p {
    margin: 5px 0;
    font-size: 0.9em;
    /* Adicione estas duas linhas para lidar com palavras muito longas (URLs, descrições sem espaço) */
    word-wrap: break-word; 
    overflow-wrap: break-word;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.card-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.card-header h3 {
    margin: 0;
    font-size: 1.1em;
    font-weight: 600;
}

.card-tipo {
    font-size: 0.8em;
    padding: 3px 8px;
    border-radius: 4px;
    color: #fff;
    font-weight: bold;
    display: inline-block;
    margin-top: 5px;
}

/* Cores específicas por tipo de chamado */
.tipo-meet { border-color: #28a745; } /* Verde */
.tipo-sagicon { border-color: #ffc107; } /* Amarelo */
.tipo-new_user { border-color: #007bff; } /* Azul */
.tipo-impressora_scan { border-color: #dc3545; } /* Vermelho */
.tipo-other { border-color: #17a2b8; } /* Ciano */

.card p {
    margin: 5px 0;
    font-size: 0.9em;
}

.card strong {
    font-weight: 600;
    color: #000;
}

.card-footer {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #eee;
    text-align: right;
}

.card-footer a {
    text-decoration: none;
    color: #007bff;
    font-weight: 600;
}

.sem-tickets {
    text-align: center;
    grid-column: 1 / -1;
    padding: 50px;
    font-size: 1.5em;
    color: #28a745;
    background: #e9f7ef;
    border-radius: 8px;
    margin: 20px auto;
}
    </style> 
</head>
<body>
    <header>
        <h1>📋 Chamados TICKA Pendentes</h1>
        <h2>Apenas tickets com status "Não Resolvido"</h2>
    </header>

    <main id="dashboard-container">
        <?php 

        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   
            // 1. Conexão com o Banco de Dados (DB_connect.php)
            include 'db_pdo.php'; 
            
            // 2. Lógica para buscar e exibir os cards (PHP_cards.php)
            include 'cards.php'; 
        ?>
        
        <?php if (empty($tickets_encontrados)): ?>
            <div class="sem-tickets">
                🎉 Parabéns! Não há tickets pendentes.
            </div>
        <?php endif; ?>
    </main>
    <script>
    // URL do script PHP que renderiza APENAS os cards
    const urlBuscaCards = 'cards.php?apenas_cards=true';
    // O ID do container onde os cards estão
    const container = document.getElementById('dashboard-container');
    // Armazena o número atual de tickets para comparação
    let contadorTicketsAntigo = 0;
    
    // Função para emitir um som
    function emitirSomNovoTicket() {
        // Você pode usar qualquer som. Aqui está um beep simples.
        const audio = new Audio('alerta.mp3'); 
        // Troque 'caminho/para/seu/som_alerta.mp3' por um arquivo de som real!
        audio.play().catch(e => console.log('Não foi possível tocar o som (autoplay block)', e));
    }

    // Função principal que busca e atualiza os cards
    function atualizarCards() {
        fetch(urlBuscaCards)
            .then(response => response.text())
            .then(htmlNovo => {
                // 1. Cria um elemento temporário para contar os tickets
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = htmlNovo;
                const cardsNovos = tempDiv.querySelectorAll('.card').length;
                
                // 2. Compara com o contador antigo (se for a primeira vez, apenas define o valor)
                if (contadorTicketsAntigo > 0 && cardsNovos > contadorTicketsAntigo) {
                    emitirSomNovoTicket();
                }
                
                // 3. Atualiza o conteúdo HTML e o contador
                container.innerHTML = htmlNovo;
                contadorTicketsAntigo = cardsNovos;
            })
            .catch(error => {
                console.error('Erro ao buscar tickets:', error);
            });
    }

    // Executa a função imediatamente ao carregar
    atualizarCards();

    // Executa a função a cada 30 segundos (30000 milissegundos)
    // Ajuste o tempo conforme a necessidade do seu escritório
    setInterval(atualizarCards, 30000); 
</script>
</body>
</html>