<?php
// For testing
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('default_charset', "utf-8");
//--------------------------------------



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

//-------------------------------------
$tipochamado = "test";
$emailSuporte = "exemple@exemple.com";
$email = "exemple@exemple.com";
$saida = 1;
$mail = new PHPMailer();



if($saida != false | $saida != '0'){
// Settin
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';

$mail->Host       = "exemple";    // SMTP server example
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "exemple";            // SMTP account username example
$mail->Password   = "exemple";            // SMTP account password example

// Content
$mail->setFrom('exemple@exemple.com');   
$mail->addAddress("$email");
$mail->addAddress("$emailSuporte");

$mail->isHTML(true);
$mail->Subject = "$tipochamado";
$mail->Body    = " teste
";

if($mail->send()){
	
	$elemail = substr_replace($email, '*****', 3, strpos($email, '@') - 5);
	echo json_encode($confirma = [ 'status' => 'ok' , 'email' => $elemail ]);	
}else{
	echo "Erramos aqui/ Error, sorry!";
}
}else{
	echo "Nao encotrei seu email/ email not found!";
}

?>