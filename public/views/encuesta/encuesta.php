<?php
include("../../../app/Models/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
    exit;
}
if($_SESSION["id"] != 3){
    header("location: ../sesiones/inicio.php");
    exit;
}

$sql = $conexion->query("SELECT * FROM preguntas");

// if ($preguntas = $sql -> fetch_object()) {
//     $id= $preguntas->id;
//     $preguntaa = $preguntas->pregunta;
//     $tipo = $preguntas->tipo;
//     $seccion = $preguntas->seccion;
//     $activo = $preguntas->activo;
// }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encuesta</title>
    <link rel="stylesheet" href="../../css/encuesta.css">
</head>
<body>
    <div class="container">
        <form action="../../../app/Controllers/encuesta_controller.php" method="post" class="form-encuesta">
            <?php
            while ($preguntas = $sql->fetch_object()) {
                $idPregunta = $preguntas->id;
                $preguntaTexto = $preguntas->pregunta;
                echo "<div class='pregunta'>";
                echo "<p class='pregunta-texto'>$preguntaTexto</p>";
                echo "<input type='text' name='respuestas[$idPregunta]' class='respuesta-input' placeholder='Respuesta para la pregunta'>";
                echo "<input type='hidden' name='preguntas[$idPregunta]' value='$preguntaTexto'>";
                echo "</div>";
            }
            ?>
            <input type="submit" value="Enviar respuestas" class="btn-enviar">
        </form>
        <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn-cerrar-sesion">
            <center>
                <input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión">
            </center>
        </a>
    </div>
</body>
</html>
