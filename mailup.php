<?php
// Biblioteca PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
//___________________________________________________//


header("Content-type: text/html; charset=utf-8");

// Verifica se tipo foi passado pelo método POST
if (!isset($_POST['tipo'])) {
    die("Tipo de chamado não especificado.");
}

//Atribui o valor de tipo // importante para o switch case
$tipo = $_POST['tipo'];
//___________________________________________________//

// Variaveis que nao são arrays
$setor      = $_POST['setor'] ?? '';
$requerente = $_POST['requerente'] ?? '';
$descricao  = $_POST['descricao'] ?? '';
$email      = $_POST['email'] ?? '';
$funcionario      = $_POST['funcionario'] ?? '';
$outro_servico      = $_POST['outro_servico'] ?? '';
$copy_user      = $_POST['copy_user'] ?? '';
$url_site      = $_POST['url_site'] ?? '';
// email alternativo
$email_alternativo = 'exemple@exemplo.com';
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
// 1. Verifica se o campo 'email' foi enviado (existe em $_POST)
// 2. E, mais importante, verifica se o campo 'email' NÃO está vazio
if (isset($_POST['email']) && !empty($_POST['email'])) {
    // Se existe e NÃO está vazio, usa o valor enviado
    $email = $_POST['email'];
} else {
    // Se não existe ou se está vazio, usa o email padrão
    $email = $email_alternativo;
}
//___________________________________________________//
// ============================
// 🔵 SISTEMA DE ROTAS
// ============================

switch ($tipo) {

    // --------------------------
    // 🔹 ROTA SAGICON
    // --------------------------
    case 'sagicon':
        $assunto = "Chamado SAGICON - $requerente";
        $mensagem = "
            <h2>Chamado SAGICON</h2>
            <b>Setor:</b> $setor<br>
            <b>Requerente:</b> $requerente<br>
            <b>Problema(s):</b> $problemas_selecionados<br>
            <b>Descrição:</b> $descricao<br>
            <br>
            <b>E-mail do Requerente:</b> $email<br>
        ";

    break;

    // --------------------------
    // 🔹 ROTA IMPRESSORA
    // --------------------------
    case 'impressora':
        $assunto = "Chamado IMPRESSORA/SCANNER - $requerente";
        $mensagem = "
            <h2>Chamado Impressora / Scanner</h2>
            <b>Setor:</b> $setor<br>
            <b>Requerente:</b> $requerente<br>
            <b>Problema(s):</b> $problemas_selecionados<br>
            <b>Observações:</b> $descricao<br>
            <br>

            <b>E-mail do Requerente:</b> $email<br>
        ";
    break;

     // --------------------------
    // 🔹 ROTA new_user
    // --------------------------
    case 'new_user':
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
            
            <b>E-mail do Requerente:</b> $email<br>
        ";
    break;

     // --------------------------
    // 🔹 ROTA remove_user
    // --------------------------
    case 'remove_user':
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
            
            <b>E-mail do Requerente:</b> $email<br>
        ";
    break;
    
    // --------------------------
    // 🔹 ROTA forgot_paass
    // --------------------------
    case 'forgot_paass':
        $assunto = "Chamado Esqueci Senha - $requerente";
        $mensagem = "
            <h2>Esqueci Senha</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
             <b>Serviços:</b> $servicos_selecionados<br>
            <b>Outros</b> $outro_servico<br>
            <b>Nome do usuário ou email:</b> $descricao<br>
            <br>
            
            <b>E-mail do Requerente:</b> $email<br>
        ";
    break;


    // --------------------------
    // 🔹 ROTA unlock_site
    // --------------------------
    case 'unlock_site':
        $assunto = "Chamado Liberar Site - $requerente";
        $mensagem = "
            <h2>Liberar Site</h2>
            <b>Requerente:</b> $requerente<br>
            <b>Setor:</b> $setor<br>
            <b>Link do Site:</b> $url_site<br>
            <b>Observaçãp:</b> $descricao<br>
            <br>
            
            <b>E-mail do Requerente:</b> $email<br>
        ";
    break;
    
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
    $mail->Host = 'exemple@exemplo.com'; //SMTP SERVER
    $mail->SMTPDebug  = 0;  // enables SMTP debug information (for testing)
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Username = 'exemple@exemplo.com'; // SMTP account username
    $mail->Password = 'exemplepassword';  // SMTP account password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;  // set the SMTP port for the GMAIL server

    // De e para
    $mail->setFrom('exemple@exemplo.com', 'Chamados Ticka');
    $mail->addAddress('exemple@exemplo.com'); // para destino do suporte
    $mail->addAddress($email);// para destino do requerente
    $mail->addReplyTo('exemple@exemplo.com'); // Replay To

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

    echo "Chamado enviado com sucesso!";

} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
