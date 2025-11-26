<?php

namespace Farmadec\Application\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Servicio de Correo Electrónico
 */
class MailService
{
    /** @var array */
    private $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../Config/mail.php';
    }
    
    /**
     * Enviar correo de certificado
     */
    public function sendCertificateEmail($recipientEmail, $recipientName, $courseName, $certificateCode, $pdfPath = null)
    {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = $this->config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->SMTPSecure = $this->config['encryption'];
            $mail->Port = $this->config['port'];
            $mail->CharSet = 'UTF-8';
            
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($recipientEmail, $recipientName);
            
            $mail->isHTML(true);
            $mail->Subject = 'Felicitaciones - Has completado el curso: ' . $courseName;
            
            $mail->Body = $this->getCertificateEmailTemplate($recipientName, $courseName, $certificateCode);
            
            if ($pdfPath && file_exists($pdfPath)) {
                $mail->addAttachment($pdfPath, 'certificado.pdf');
            }
            
            $mail->send();
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $mail->ErrorInfo];
        }
    }

    /**
     * Enviar correo de restablecimiento de contraseña
     */
    public function sendPasswordResetEmail($recipientEmail, $recipientName, $resetUrl)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->SMTPSecure = $this->config['encryption'];
            $mail->Port = $this->config['port'];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($recipientEmail, $recipientName);

            $mail->isHTML(true);
            $mail->Subject = 'Restablece tu contraseña';
            $mail->Body = $this->getPasswordResetTemplate($recipientName, $resetUrl);

            $mail->send();
            return ['success' => true];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $mail->ErrorInfo];
        }
    }
    
    /**
     * Plantilla HTML del correo de certificado
     */
    private function getCertificateEmailTemplate($name, $courseName, $code)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .certificate-code { background: #fff; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0; font-family: monospace; font-size: 18px; }
                .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>¡Felicitaciones!</h1>
                </div>
                <div class="content">
                    <p>Estimado/a <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    
                    <p>Nos complace informarte que has completado exitosamente el curso:</p>
                    <h2 style="color: #667eea;">' . htmlspecialchars($courseName) . '</h2>
                    
                    <p>Tu constancia ha sido generada con el siguiente código de verificación:</p>
                    <div class="certificate-code">' . htmlspecialchars($code) . '</div>
                    
                    <p>Este documento certifica que has cumplido satisfactoriamente con todos los requisitos del curso.</p>
                    
                    <p><strong>¡Sigue aprendiendo y creciendo profesionalmente!</strong></p>
                    
                    <div class="footer">
                        <p>Farmadec LMS - Sistema de Gestión de Aprendizaje</p>
                        <p>Este es un correo automático, por favor no responder.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    private function getPasswordResetTemplate($name, $resetUrl)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #004186; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f7fbff; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #004186; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 15px; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Recupera tu acceso</h2>
                </div>
                <div class="content">
                    <p>Hola <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    <p>Hemos recibido una solicitud para restablecer tu contraseña. Si fuiste tú, haz clic en el siguiente botón:</p>
                    <p><a class="button" href="' . htmlspecialchars($resetUrl) . '">Crear nueva contraseña</a></p>
                    <p>O copia y pega este enlace en tu navegador:</p>
                    <p><a href="' . htmlspecialchars($resetUrl) . '">' . htmlspecialchars($resetUrl) . '</a></p>
                    <p>Este enlace expira en 1 hora. Si no solicitaste el cambio, puedes ignorar este mensaje.</p>
                </div>
                <div class="footer">
                    <p>Farmadec LMS</p>
                    <p>No respondas a este correo. Si necesitas ayuda, contacta al administrador.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }
}
