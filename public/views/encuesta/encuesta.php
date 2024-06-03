<?php
include("../../../app/Models/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
}
if($_SESSION["id"] != 3){
    header("location: ../sesiones/inicio.php");
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

<form action="../../../app/Controllers/encuesta_controller.php" method="post">
    <?php
    // Iterar sobre todas las preguntas
    // echo $preguntaa;
    while ($preguntas = $sql->fetch_object()) {
        $idPregunta = $preguntas->id;
        $preguntaTexto = $preguntas->pregunta;
        // Imprimir la pregunta
        echo "<p>$preguntaTexto</p>";
        // Crear un campo de texto para la respuesta
        echo "<input type='text' name='respuestas[$idPregunta]' placeholder='Respuesta para la pregunta'>";
        // Agregar un campo oculto para guardar el ID de la pregunta
        echo "<input type='hidden' name='preguntas[$idPregunta]' value='$preguntaTexto'>";
    }
    ?>
    <input type="submit" value="Enviar respuestas">
</form>

<a href="../../../app/Controllers/sessiondestroy_controller.php">
    <center>
        <input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión">
    </center>
</a>