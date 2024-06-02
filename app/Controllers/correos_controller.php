<?php
// Configuración y carga de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../../PHPMailer/Exception.php';
require '../../../PHPMailer/PHPMailer.php';
require '../../../PHPMailer/SMTP.php';

// Validación del formulario
if (!empty($_POST["btnenviarcorreo"])) {
    if (empty($_POST["email"])) {
        echo '<div class="alert alert-danger">"Por favor ingresa una credencial de acceso valida."</div>';
    } else {
        $email = $_POST["email"];
        $sql = $conexion->query("SELECT * FROM usuarios WHERE email = '$email'");
        if ($datos = $sql->fetch_object()) {
            // Generar el token de la URL
            $llaveSecreta = 'llave_secreta_aqui';
            $idUsuario = $datos->id;
            $tiempoVidaToken = 900; // 15 minutos
            $timestamp = time();
            $datos = [
                'id' => $idUsuario,
                'timestamp' => $timestamp,
            ];
            $datosCodificados = json_encode($datos);
            $token = hash_hmac('sha256', $datosCodificados, $llaveSecreta);
            $url = 'http://127.0.0.1:8000/public/views/pass/cambiarpass.php?' . http_build_query([
                'id' => $idUsuario,
                'timestamp' => $timestamp,
                'token' => $token,
            ]);

            // Configurar y enviar el correo
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Desactivar el modo debug
                $mail->isSMTP();                                         // Usar SMTP
                $mail->Host = 'smtp.gmail.com';                          // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;                                  // Habilitar autenticación SMTP
                $mail->Username = 'soporteuippe@gmail.com';              // Tu dirección de correo
                $mail->Password = 'mhrcaotksyefxybd';                    // Tu contraseña de aplicación SMTP
                $mail->SMTPSecure = 'ssl';                               // Habilitar encriptación TLS implícita
                $mail->Port = 465;                                       // Puerto TCP

                // Destinatarios
                $mail->setFrom('soporteuippe@gmail.com', 'PRUEBA');
                $mail->addAddress($email);                               // Destinatario

                // Contenido del correo
                $mail->isHTML(true);                                     // Formato HTML
                $mail->Subject = 'Solicitud de cambio de contraseña';
                ob_start();
                include 'correo_reestablecer.php';
                $mail->Body = ob_get_clean();

                $mail->send();
                echo '<script>alert("El correo se ha enviado con exito, por favor cierre esta pagina y verifique su bandeja de entrada"); window.location.href = "../sesiones/login.php";</script>';
            } catch (Exception $e) {
                echo "Hubo un error en el envío del correo, por favor intentelo de nuevo o contactese con su tutor. Código de error: {$mail->ErrorInfo}";
            }
        } else {
            echo '<div class="alert alert-danger">"Credenciales incorrectas."</div>';
        }
    }
}
?>
