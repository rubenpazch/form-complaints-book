<?php
/*
THIS FILE USES PHPMAILER INSTEAD OF THE PHP MAIL() FUNCTION
AND ALSO SMTP TO SEND THE EMAILS
*/

require 'PHPMailer-master/PHPMailerAutoload.php';

/*
*  CONFIGURE EVERYTHING HERE
*/

// an email address that will be in the From field of the email.
$fromEmail = 'rubenpazchuspe@outlook.com';
$fromName = '';

// an email address that will receive the email with the output of the form
$sendToEmail = 'khuillca@hospitalantoniolorena.gob.pe';
$sendToName = 'Reclamos Hospital Antonio Lorena';

// subject of the email
$subject = 'Reclamo registrado por el usuario ';

// smtp credentials and server

$smtpHost = 'smtpdelservicioquerecive';
$smtpUsername = 'correodelrecibidor';
$smtpPassword = 'passwordelquerecive';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array(
    'txtfechareclamo' => 'FechaReclamo', 
    'rbopcionTipoReclamo' => 'Tipo de Reclamante',
    'txtnombre' => 'Nombre', 
    'txtapellidos' => 'Apellidos', 
    'rbTipoDocumento' => 'Tipo de Documento', 
    'nrodocumento' => 'Nro. Documento', 
    'txtdireccion' => 'Direccion', 
    'txtubigeodistrito' => 'Distrito, Provincia, Departamento', 
    'txtemail' => 'Email', 
    'txttelefono' => 'Telefono', 
    'txtmensaje' => 'Mensaje', 
    'rbAutorizacion' => 'Autorizacion');

// message that will be displayed when everything is OK :)
$okMessage = 'El reclamo fue registrado correctamente';

// If something goes wrong, we will display this message.
$errorMessage = 'Se produjo un error en el registro del reclamo.';

/*
*  LET'S DO THE SENDING
*/

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try {
    if (count($_POST) == 0) {
        throw new \Exception('El formulario esta vacio');
    }
    
    $emailTextHtml = "<h1> Se ha registrado un nuevo reclamo </h1><hr>";
    $emailTextHtml .= "<table>";
    
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            if ($fields[$key] == "Email") {
                $fromEmail = $value;
            }
            if ($fields[$key] == "Nombre") {
                $subject .= " ".$value;
                $fromName .= " ".$value;
            }
            if ($fields[$key] == "Apellidos") {
                $subject .= " ".$value;
                $fromName .= " ".$value;
            }

            if ($fields[$key] == "Tipo de Reclamante") {
                if ($value == 1){
                    $value = "Paciente (Usuario Afectado)";
                }else {
                    $value = "Representante o si el paciente es menor de edad";
                }
            }
            if ($fields[$key] == "Tipo de Documento") {
                if ($value == 1){
                    $value = "DNI";
                }else if ($value == 2){
                    $value = "CARNET DE EXTRANJERIA";
                }else if ($value == 3){
                    $value = "PASAPORTE";
                }else {
                    $value = "RUC";
                }
            }
            if ($fields[$key] == "Autorizacion") {
                if ($value == 1){
                    $value = "SI";
                } else {
                    $value = "NO";
                }
            }

            $emailTextHtml .= "<tr><th style='text-align: left;'>$fields[$key]</th><td>$value</td></tr>";
        }
    }
    $emailTextHtml .= "</table><hr>";
    $emailTextHtml .= "Hospital Antonio Lorena del Cusco,<br>Al servicio de la población";
    
    $mail = new PHPMailer;
    
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($sendToEmail, $sendToName); // you can add more addresses by simply adding another line with $mail->addAddress();
    $mail->addReplyTo($from);
    
    $mail->isHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body    = $emailTextHtml;
    $mail->msgHTML($emailTextHtml); // this will also create a plain-text version of the HTML email, very handy
    
    
    $mail->isSMTP();
    
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    
    //Set the hostname of the mail server
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    $mail->Host = gethostbyname($smtpHost);
    
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = $smtpUsername;
    
    //Password to use for SMTP authentication
    $mail->Password = $smtpPassword;
    
    if (!$mail->send()) {
        throw new \Exception('No se pudo enviar en el correo.' . $mail->ErrorInfo);
    }
    
    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
    // $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
