<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail_pml {

    /**
     * Enviar mensaje de correo electrónico
     * 2024-07-27
     */
    public function send($settings)
    {
        //Valor por defecto, sin error
        $data = ['status' => 1, 'error' => ''];

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = NOTIFIER_EMAIL;
        $mail->Password = NOTIFIER_PW;
        $mail->setFrom(NOTIFIER_EMAIL, APP_NAME);
        $mail->addReplyTo(NOTIFIER_EMAIL, APP_NAME);
        $mail->addAddress($settings['to'], $settings['to']);
        if ( isset($setings['bcc']) ) $mail->addBCC($settings['bcc'],$settings['bcc']);
        $mail->Subject = $settings['subject'];
        $mail->msgHTML($settings['html_message']);
        
        if (!$mail->send()) {
            $data['status'] = 0;
            $data['error'] = $mail->ErrorInfo;
        }

        return $data;
    }
}
