<?php
include("../../../database/conexion.php");
require("../../../app/Controllers/auth.php");
// session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
    exit;
}

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener estudiante_id usando usuario_id de la sesión
$stmt = $conexion->prepare("SELECT id FROM estudiantes WHERE usuario_id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$stmt->bind_result($estudianteId);
$stmt->fetch();
$stmt->close();

// Verificar estudianteId
// echo "<p><strong>Estudiante ID:</strong> $estudianteId</p>";

if (!$estudianteId) {
    die("No se encontró el estudiante correspondiente al usuario actual.");
}

// Función para contar preguntas en una sección
function contarPreguntasSeccion($conexion, $seccionId) {
    $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM preguntas WHERE seccion_id = ?");
    $stmt->bind_param("i", $seccionId);
    $stmt->execute();
    $stmt->bind_result($totalPreguntas);
    $stmt->fetch();
    $stmt->close();
    // echo "<p><strong>Total de Preguntas en la Sección $seccionId:</strong> $totalPreguntas</p>";
    return $totalPreguntas;
}


// Función para contar respuestas del usuario en una sección
function contarRespuestasUsuarioSeccion($conexion, $seccionId, $estudianteId) {
    $stmt = $conexion->prepare("SELECT COUNT(DISTINCT pregunta_id) as total FROM estudiante_respuesta WHERE seccion_id = ? AND estudiante_id = ?");
    $stmt->bind_param("ii", $seccionId, $estudianteId);
    $stmt->execute();
    $stmt->bind_result($totalRespuestas);
    $stmt->fetch();
    $stmt->close();
    return $totalRespuestas; 
}

// Función para verificar si la sección está completada
function seccionCompletada($conexion, $seccionId, $estudianteId) {
    $totalPreguntas = contarPreguntasSeccion($conexion, $seccionId);
    $totalRespuestas = contarRespuestasUsuarioSeccion($conexion, $seccionId, $estudianteId);
    // echo "<p><strong>Sección $seccionId:</strong> Total Preguntas = $totalPreguntas, Total Respuestas = $totalRespuestas</p>";
    return $totalPreguntas == $totalRespuestas;
}

// Obtener secciones
$sql = "SELECT id, nombre, descripcion FROM secciones";
$result = $conexion->query($sql);
?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Secciones</title>
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/menusecciones.css">
</head>
<body>
<?php
    require("../sesiones/emailverified.php");

    if ($email_verificado == 0):
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById("myModal"));
                myModal.show();
            });
          </script>';
    endif;
     ?>
    <div class="contenedor rounded shadow">
        <center>
        <h1>ENTREVISTA</h1>
        </center>
        <br>
        <table>
            <thead>
                <th>Sección</th>
                <th>Acciones</th>
                <th>Estado</th>
            </thead>
            <tbody>
                <?php

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              // Verificar si la sección está completada
              $completado = seccionCompletada($conexion, $row["id"], $estudianteId);
              $iconoCompletado = $completado ? '✅' : '❌';
              $botonEstado = $completado ? 'disabled' : '';
      
              echo "<tr>";
              echo "<td>" . $row["id"]  . ' | ' . $row["descripcion"] . "</td>";
            //   echo "<td class='centrar'><button class='btn btn-success' onclick=\"window.location.href='seccion.php?seccion=" . urlencode($row["id"]) . "'\" >Responder</button></td>";
            //   echo "<td class='centrar'><button class='btn btn-success' onclick=\"window.location.href='seccion.php?seccion=" . urlencode($row["id"]) . "'\" $botonEstado>Responder</button></td>";
              echo "<td class='centrar'><button class='btn btn-success' onclick=\"window.location.href='seccion.php?seccion=" . urlencode($row["id"]) . "'\" >Responder</button></td>";
              echo "<td class='centrar'>" . $iconoCompletado . "</td>";
              echo "</tr>";
      
              // Depuración en HTML
            //   echo "<p>Sección ID: {$row['id']}, Completado: $completado, Icono: $iconoCompletado, Botón Desactivado: $botonEstado</p>";
          }
      }
       else {
                    echo "<tr><td colspan='3'>0 resultados</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>
    <br><br>
    <a href="../../../app/Controllers/sessiondestroy_controller.php" class="btn-cerrar-sesion">
        <center>
            <input type="submit" name="btningresar" class="btn btn-danger" value="Cerrar sesión">
        </center>   
    </a>
    
    <script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>