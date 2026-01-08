<?php
// Biblioteca PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require 'conf.php';
//___________________________________________________//


header("Content-type: text/html; charset=utf-8");

// Verifica se tipo foi passado pelo método POST
if (!isset($_POST['tipo'])) {
    die("<meta http-equiv='refresh' content='1; url=https://exemplo.org.br/intra/chamados.html'>");
   
}

//Atribui o valor de tipo // importante para o switch case
$tipo = $_POST['tipo'];
//___________________________________________________//

// Variaveis que nao são arrays
$setor      = $_POST['setor'] ?? '';
$requerente = $_POST['requerente'] ?? '';
$descricao  = $_POST['descricao'] ?? '';
$r_email      = $_POST['email'] ?? '';
$funcionario      = $_POST['funcionario'] ?? '';
$outro_servico      = $_POST['outro_servico'] ?? '';
$copy_user      = $_POST['copy_user'] ?? '';
$url_site      = $_POST['url_site'] ?? '';
$conta_google      = $_POST['conta_google'] ?? '';
$data_agendamento_formatada      = $_POST['data_agendamento_formatada'] ?? '';
$hora_agendamendo      = $_POST['hora_agendamento'] ?? '';
$tema      = $_POST['tema'] ?? '';
// email alternativo
$r_email_alternativo = 'alternativo@email.org.br';
//___________________________________________________//

// ===Processa os Checkboxes ===
// Verifica se o array 'problemas' existe e não está vazio
if (isset($_POST['problemas']) && is_array($_POST['problemas'])) {
    // Junta os problemas selecionados em uma string separada por vírgulas e espaços.
    $problemas_selecionados = implode(', ', $_POST['problemas']);
} else {
    $problemas_selecionados = 'Nenhum problema selecionado ou problema não especificado.';
}

// Verifica se o array 'servicos' existe e não está vazio
if (isset($_POST['servicos']) && is_array($_POST['servicos'])) {
    // Junta os problemas selecionados em uma string separada por vírgulas e espaços.
    $servicos_selecionados = implode(', ', $_POST['servicos']);
} else {
    $servicos_selecionados = 'Nenhum servico selecionado.';
}
//___________________________________________________//


//Recurso técnico emergencial (RTE)
// 1. Verifica se o campo 'r_email' foi enviado (existe em $_POST)
// 2. E, mais importante, verifica se o campo 'r_email' NÃO está vazio
if (isset($_POST['r_email']) && !empty($_POST['r_email'])) {
    // Se existe e NÃO está vazio, usa o valor enviado
    $r_email = $_POST['r_email'];
} else {
    // Se não existe ou se está vazio, usa o email padrão
    $r_email = $r_email_alternativo;
}

//___________________________________________________//
// ============================
// 🔵 SISTEMA DE ROTAS
// ============================
require 'db.php';
switch ($tipo) {

    // --------------------------
    // 🔹 TICKA_SAGICON
    // --------------------------
    case 'TICKA_SAGICON':
        $assunto = "Chamado SAGICON - $requerente";
        $mensagem = "
            <h2>Chamado SAGICON</h2>
            <b>Setor:</b> $setor<br>
            <b>Requerente:</b> $requerente<br>
            <b>Problema(s):</b> $problemas_selecionados<br>
            <b>Descrição:</b> $descricao<br>
            <br>
            <b>E-mail do Requerente:</b> $r_email<br>
        ";
        $entrds = "INSERT INTO TICKA_SAGICON (
        REQUERENTE, 
        SETOR, 
        PROBLEMA, 
        DESCRICAO, 
        R_EMAIL
        ) VALUES (
        '".$requerente."', 
        '".$setor."', 
        '".$problemas_selecionados."', 
        '".$descricao."', 
        '".$r_email."'
         );"
        ;
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);
    break;

    // --------------------------
    // 🔹 ROTA TICKA_IMPRESSORA_SCAN
    // --------------------------
    case 'TICKA_IMPRESSORA_SCAN':
        $assunto = "Chamado IMPRESSORA/SCANNER - $requerente";
        $mensagem = "
            <h2>Chamado Impressora / Scanner</h2>
            <b>Setor:</b> $setor<br>
            <b>Requerente:</b> $requerente<br>
            <b>Problema(s):</b> $problemas_selecionados<br>
            <b>Observações:</b> $descricao<br>
            <br>

            <b>E-mail do Requerente:</b> $r_email<br>
        ";
        $entrds = "INSERT INTO TICKA_IMPRESSORA_SCAN (
        REQUERENTE, 
        SETOR, 
        PROBLEMA, 
        DESCRICAO, 
        R_EMAIL
        ) VALUES (
        '".$requerente."', 
        '".$setor."', 
        '".$problemas_selecionados."', 
        '".$descricao."', 
        '".$r_email."'
        );"
        ;
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);
    break;

     // --------------------------
    // 🔹 ROTA TICKA_NEW_USER
    // --------------------------
    case 'TICKA_NEW_USER':
        $assunto = "Chamado Novo Usuário - $requerente";
        $mensagem = "
            <h2>Novo Usuário</h2>
            <b>Novo Funcionário:</b> $funcionario<br>
            <b>Setor:</b> $setor<br>
            <b>Tipo de funcionario:</b> $problemas_selecionados<br>
            <b>Outro tipo:</b> $descricao<br>
            <b>Serviços:</b> $servicos_selecionados<br>
            <b>Outros </b> $outro_servico<br>
            <b>Copiar Usuário:</b> $copy_user<br>
            <b>Requerente:</b> $requerente<br>
            <br>
            
            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_NEW_USER (
        REQUERENTE, 
        SETOR, 
        NOVO_FUNCIONARIO, 
        TIPO, 
        OUTRO_TIPO, 
        SERVICOS, 
        OUTROS, 
        COPY_USER, 
        R_EMAIL
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$funcionario."',
            '".$problemas_selecionados."', 
            '".$descricao."', 
            '".$servicos_selecionados."',
            '".$outro_servico."',
            '".$copy_user."',   
            '".$r_email."'
    );";
    
    $insert = mysqli_query($connection , $entrds);
    mysqli_close($connection);

    break;

     // --------------------------
    // 🔹 ROTA TICKA_REMOVE_USER
    // --------------------------
    case 'TICKA_REMOVE_USER':
        $assunto = "Chamado Remover Usuário - $requerente";
        $mensagem = "
            <h2>Remover Usuário</h2>
            <b>Remover Funcionário:</b> $funcionario<br>
            <b>Setor:</b> $setor<br>
            <b>Tipo de funcionario:</b> $problemas_selecionados<br>
            <b>Outro tipo:</b> $descricao<br>
            <b>Serviços:</b> $servicos_selecionados<br>
            <b>Outros</b> $outro_servico<br>
            <b>Requerente:</b> $requerente<br>
            <br>
            
            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_REMOVE_USER (
        REQUERENTE, 
        SETOR, 
        REMOVER_FUNCIONARIO, 
        TIPO, 
        OUTRO_TIPO, 
        SERVICOS, 
        OUTROS, 
        R_EMAIL
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$funcionario."',
            '".$problemas_selecionados."', 
            '".$descricao."', 
            '".$servicos_selecionados."',
            '".$outro_servico."',   
            '".$r_email."'
        );";
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);

    break;
    
    // --------------------------
    // 🔹 ROTA TICKA_FORGOT_PASS
    // --------------------------
    case 'TICKA_FORGOT_PASS':
        $assunto = "Chamado Esqueci Senha - $requerente";
        $mensagem = "
            <h2>Esqueci Senha</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
             <b>Serviços:</b> $servicos_selecionados<br>
            <b>Outros</b> $outro_servico<br>
            <b>Nome do usuário ou email:</b> $descricao<br>
            <br>
            
            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_FORGOT_PASS (
        REQUERENTE, 
        SETOR, 
        DESCRICAO, 
        R_EMAIL, 
        SERVICOS, 
        OUTRO
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$descricao."', 
            '".$r_email."',
            '".$servicos_selecionados."',
            '".$outro_servico."'   
            
        );";
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);

    break;


    // --------------------------
    // 🔹 ROTA TICKA_UNLOCK_SITE
    // --------------------------
    case 'TICKA_UNLOCK_SITE':
        $assunto = "Chamado Liberar Site - $requerente";
        $mensagem = "
            <h2>Liberar Site</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
            <b>Link do Site:</b> $url_site<br>
            <b>Observaçãp:</b> $descricao<br>
            <br>
            
            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_UNLOCK_SITE (
        REQUERENTE, 
        SETOR, 
        DESCRICAO, 
        R_EMAIL, 
        URL_SITE
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$descricao."', 
            '".$r_email."',
            '".$url_site."'   
            
        );";
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);


    break;
    
    // --------------------------
    // 🔹 ROTA TICKA_OTHER
    // --------------------------
    case 'TICKA_OTHER':
        $assunto = "Chamado Outros - $requerente";
        $mensagem = "
            <h2>Outros</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
            <b>Problemas com Equipamentos?:</b>$problemas_selecionados<br>
            <b>Descreva Seu Problema:</b> $descricao<br>
            <b>Mudança de Nome?:</b> $outro_servico <br>
            <br>
            
            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_OTHER (
        REQUERENTE, 
        SETOR, 
        PROBLEMAS, 
        DESCRICAO, 
        R_EMAIL, 
        NOVO_NOME
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$problemas_selecionados."', 
            '".$descricao."', 
            '".$r_email."',
            '".$outro_servico."'   
            
        );";
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);

    break;
    
    // --------------------------
    // 🔹 ROTA TICKA_MEET
    // --------------------------
    case 'TICKA_MEET':
        $assunto = "Chamado Vídeo Conferência - $requerente";
        $mensagem = "
            <h2>Vídeo Conferência</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
            <b>Tipo de Reunião:</b>$problemas_selecionados<br>
            <b>Tema/Assunto:</b> $tema<br>
            <b>Data:</b> $data_agendamento_formatada<br> 
            <b>Horário:</b> $hora_agendamendo<br>
            <b>Outros horários?:</b> $outro_servico <br>
            <b>Alguma Observação?:</b> $descricao<br>
            <b>E-mail do Coorganizador:<b>$conta_google<br>
            <br>

            <b>E-mail do Requerente:</b> $r_email<br>
        ";

        $entrds = "INSERT INTO TICKA_MEET (
        REQUERENTE, 
        SETOR, 
        TIPO, 
        TEMA, 
        DATA_AGENDAMENTO_FORMATADA, 
        HORA_AGENDAMENTO, 
        OUTROS_H, 
        DESCRICAO, 
        CONTA_GOOGLE, 
        R_EMAIL
        ) VALUES (
            '".$requerente."', 
            '".$setor."', 
            '".$problemas_selecionados."', 
            '".$tema."',
            '".$data_agendamento_formatada."',
            '".$hora_agendamendo."',
            '".$outro_servico."',    
            '".$descricao."',
            '".$conta_google."',  
            '".$r_email."'   
            
        );";
        $insert = mysqli_query($connection , $entrds);
        mysqli_close($connection);

    break;

    /*// --------------------------
    // 🔹 ROTA exemple
    // --------------------------
    case 'exemple':
        $assunto = "Chamado Exemple - $requerente";
        $mensagem = "
            <h2> Exemple</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
            <b>Exemple:</b>$problemas_selecionados<br>
            <b>Exemple 2:</b> $outro_servico <br>
            <b>Exemple 3:</b> $descricao<br>
            <br>

            <b>E-mail do Requerente:</b> $r_email<br>
        ";
    break;*/


    // --------------------------
    default:
        die("Tipo de chamado inválido.");
}
//___________________________________________________//

// ============================
// 🔵 Envio do e-mail (PHPMailer)
// ============================

$mail = new PHPMailer(true);

try {
    // CONFIGURE AQUI SEU SMTP
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $smtp_server; //SMTP SERVER
    $mail->SMTPDebug  = 0;  // enables SMTP debug information (for testing)
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Username = $remetente; // SMTP account username
    $mail->Password = $senha;  // SMTP account password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;  // set the SMTP port for the GMAIL server

    // De e para
    $mail->setFrom($from_adress, $from_name);
    $mail->addAddress($suporte_email); // para destino do suporte
    $mail->addAddress($r_email);// para destino do requerente
    $mail->addReplyTo($reply_email); //Replay To
    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;
    // Processa o Anexo 
    if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] == UPLOAD_ERR_OK) {
        $upload_file = $_FILES['anexo']['tmp_name'];
        $file_name   = $_FILES['anexo']['name'];
        
        // Adiciona o anexo
        // A função addAttachment recebe o caminho temporário do arquivo e, opcionalmente, o nome
        // com o qual ele deve aparecer no email.
        $mail->addAttachment($upload_file, $file_name);
    }
    // =============================

    $mail->send();

    echo "<meta http-equiv='refresh' content='5; url=https://exemplo.org.br/ticka/sucesso.html'>";

} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
