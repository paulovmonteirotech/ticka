<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

header("Content-type: text/html; charset=utf-8");

if (!isset($_POST['tipo'])) {
    die("Tipo de chamado não especificado.");
}

$tipo = $_POST['tipo'];

// Dados básicos
$setor      = $_POST['setor'] ?? '';
$requerente = $_POST['requerente'] ?? '';
$descricao  = $_POST['descricao'] ?? '';
$email      = $_POST['email'] ?? '';
$funcionario      = $_POST['funcionario'] ?? '';


// ===Processa os Checkboxes ===
// Verifica se o array 'problemas' existe e não está vazio
if (isset($_POST['problemas']) && is_array($_POST['problemas'])) {
    // Junta os problemas selecionados em uma string separada por vírgulas e espaços.
    $problemas_selecionados = implode(', ', $_POST['problemas']);
} else {
    $problemas_selecionados = 'Nenhum problema selecionado ou problema não especificado.';
}
// ===================================
if (isset($_POST['email']) && is_string($_POST['problemas'])) {
    
    $email = ($_POST['email']);
} else {
    $email = 'exemple@exemplo.com';
}

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
            <b>E-mail:</b> $email<br>
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
            <b>E-mail:</b> $email<br>
        ";
    break;

     // --------------------------
    // 🔹 ROTA IMPRESSORA
    // --------------------------
    case 'new_user':
        $assunto = "Chamado Novo Usuário - $requerente";
        $mensagem = "
            <h2>Novo Usuário</h2>
            <b>Setor:</b> $setor<br>
            <b>Requerente:</b> $requerente<br>
            <b>Problema(s):</b> $problemas_selecionados<br>
            <b>Observações:</b> $descricao<br>
            <b>E-mail:</b> $email<br>
        ";
    break;

    // --------------------------
    default:
        die("Tipo de chamado inválido.");
}


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
    $mail->addAddress($email);
    $mail->addReplyTo('exemple@exemplo.com'); // para destino do requerente

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
