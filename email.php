<?php


// This requires PHPMailer
// You can get it here: https://github.com/Synchro/PHPMailer
// either copy the folder (renamed to phpmailer in this case) to this directory
// or add it to your include_path and delete the phpmailer/ below
include("phpmailer/class.phpmailer.php");

include_once("config.php");


$mail = new PHPMailer();

$mail->IsSMTP();
$mail->Host = $smtpserver;
$mail->Port = $smtpport;
$mail->SMTPAuth = $smtpauth;
$mail->Username = $smtpuser;
$mail->Password = $smtppass;
$mail->SMTPSecure = $smtpenc;


// TODO: finish this, though not sure if I'll need it
function email_user($userid, $subject, $body) {
    //send_email($useremail, $subject, $body);
}


// because using mail function might not always be best
function send_email($email, $subject, $body) {
    //$headers = "From: noreply@drakeapps.com\r\n".
    //    "Reply-To: noreply@drakeapps.com";
    //mail($email, $subject, $body, $headers);

    global $mail, $fromemail;

    $mail->From = $fromemail;
    $mail->FromName = $fromemail;
    $mail->AddAddress($email);

    $mail->IsHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $body;

    if(!$mail->Send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        //exit;
    }

}