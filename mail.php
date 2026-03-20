<?php

session_start();
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if (isset($_POST['nosutit'])) {

    try {
        //Server settings
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'markovsdaniels@gmail.com';                     //SMTP username
        $mail->Password   = 'wicu rbsa sbqs buno';                            //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('markovsdaniels@gmail.com', 'IT atbalsta sistēma');
        $mail->addAddress('markovsdaniels@gmail.com', 'ērikam');     //Add a recipient

        // //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Markovs - Jauna ziņa no IT atbalsta sistēmas kontaktformas';
        $mail->Body    = 'Ziņas sūtītāja e-pasts: <b>'.$_POST['epasts'].'</b><br>
                          Ziņas sūtītāja vards: <b>'.$_POST['vards'].'</b><br>
                          Ziņojums: <b>'.$_POST['zinojums'].'</b><br><br>
                          
                          Automātiskā sistēmas ziņa';

        $mail->send();


        $_SESSION["pazinojums"] = '<span data-lang-key="pazinojums"></span>';
    } catch (Exception $e) {
        $_SESSION["pazinojums"] = 'Ziņu nevar nosūtīt! Sistēmas kļūda: {$mail->ErrorInfo}';
    }
}

header("location: ./")

?>