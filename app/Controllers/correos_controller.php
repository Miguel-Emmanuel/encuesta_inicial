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
            $row = $sql->fetch_assoc();
            $id=$datos->id;
            // Caracteres que pueden usarse para el token
            $numeros_y_letras = "0123456789qwertyuiopasdfghjklzxcvbnm";
            $solo_letras = "qwertyuiopasdfghjklzxcvbnm"; // Para el primer carácter

            // Generar el primer carácter como letra
            $token = substr($solo_letras, mt_rand(0, strlen($solo_letras) - 1), 1);

            // Generar el resto del token (27 caracteres más para un total de 28)
            $cantidad = 27;
            for ($i = 0; $i < $cantidad; $i++) {
                $char = substr($numeros_y_letras, mt_rand(0, strlen($numeros_y_letras) - 1), 1);
                $token .= $char;
            }

            // Generar la URL con el token
            $url = "http://127.0.0.1:8000/public/views/pass/cambiarpass.php?token=" . $id . $token;
            
            $conexion->query("INSERT INTO links (id_usuario, activo) VALUES ('$id', 1)");

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
                $mail->SMTPSecure = 'ssl';                               // Habilitar encriptación implícita
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
