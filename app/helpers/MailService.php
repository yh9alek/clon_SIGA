<?php

namespace app\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use app\config\Server;

use \Exception, \stdClass;

trait MailerConfig {
    
    private static function initMailer(): PHPMailer {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = "localhost";
            $mail->SMTPAuth   = false;
            $mail->Username   = "";
            $mail->Password   = "";
            #$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 1025;

            $mail->setFrom("chanchito@outlook.mx", "Chanchito Felíz");
            $mail->isHTML(true);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'email_error_logs.txt',
                "CONFIG MAILER ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $mail;
    }
}

class MailService {

    use MailerConfig;

    public static function sendMail(

        string  $asunto,
        string  $message,
        array   $para,
        array   $cc  = [],
        array   $bcc = [],
        array   $archivos = [],
        ?string $buttonText  = null,  # Texto del botón opcional
        ?string $buttonLink  = null   # Enlace del botón opcional

    ): stdClass {

        $mail     = self::initMailer();
        $response = new stdClass;
        $response->success = true;

        try {
            # Obtener la estructura HTML del correo
            $body = self::getEmailBody($asunto, $message, $buttonText, $buttonLink);

            # Configurar destinatarios
            foreach ($para  as $recipient) $mail->addAddress($recipient);
            foreach ($cc    as $recipient) $mail->addCC($recipient);
            foreach ($bcc   as $recipient) $mail->addBCC($recipient);

            # Adjuntar archivos
            foreach ($archivos as $file)
                if (!empty($file))
                    $mail->addAttachment($file);

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            # Asunto y contenido
            $mail->Subject = $asunto;
            $mail->Body    = $body;

            # Agregar imagen embebida (opcional)
            $mail->AddEmbeddedImage(__DIR__.'/../../public/assets/src/imgs/mail-logo.png', 'mail-logo.png', 'mail-logo.png', 'base64', 'image/png');

            # Enviar correo
            if (!$mail->send())
                throw new Exception($mail->ErrorInfo);

        } catch (Exception $e) {
            $response->success = false;
            $response->msg     = "Error al enviar el correo: " . $e->getMessage();

            file_put_contents(
                Server::ERROR_LOGS_PATH.'email_error_logs.txt',
                "SEND EMAIL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $response;
    }

    /**
     * Genera el cuerpo del correo con estructura fija pero contenido dinámico.
     * @param string  $asunto     Título del email.
     * @param string  $message    Contenido del mensaje.
     * @param ?string $buttonText Texto de botón opcional.
     * @param ?string $buttonLink href del botón opcional.
     * @return string Body del email.
     */
    private static function getEmailBody(string $asunto, string $message, ?string $buttonText = null, ?string $buttonLink = null): string {
        $buttonHTML = "";

        if ($buttonText && $buttonLink)
            $buttonHTML = sprintf(
                '<tr>
                    <td align="center" style="padding: 10px;">
                        <a href="%s" style="background-color: #406296; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; display: inline-block;">%s</a>
                    </td>
                </tr>',
                htmlspecialchars($buttonLink),
                htmlspecialchars($buttonText)
            );

        return sprintf('
            <table width="100%%" bgcolor="#f4f4f4" style="padding: 20px;">
                <tr>
                    <td align="center">
                        <table width="700" bgcolor="#ffffff" style="border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
                            
                            <!-- Encabezado -->
                            <tr>
                                <td align="center" bgcolor="#406296" style="border-radius: 8px 8px 0 0; padding: 10px 15px; display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
                                    <img src="cid:mail-logo.png" alt="Logo" width="50">
                                    <h1 style="color: #ffffff; margin: 10px 0; font-size: 20px;">%s</h1>
                                </td>
                            </tr>

                            <!-- Contenido -->
                            <tr>
                                <td style="padding: 20px; color: #333; font-size: 16px; line-height: 1.5">
                                    %s
                                </td>
                            </tr>

                            %s

                            <!-- Separador -->
                            <tr>
                                <td align="center" style="padding: 10px 0;">
                                    <hr style="width: 80%%; border: 0; border-top: 1px solid #ddd;">
                                </td>
                            </tr>

                            <!-- Pie de Página -->
                            <tr>
                                <td align="center" style="padding: 10px; font-size: 12px; color: #777;">
                                    <p>Sistema</p>
                                    <p>No responda a este mensaje. Este es un envío automático.</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>',
            htmlspecialchars($asunto),   # Título del email
            $message,                     # Contenido dinámico del mensaje
            $buttonHTML                   # Botón opcional si se envía
        );
    }
}