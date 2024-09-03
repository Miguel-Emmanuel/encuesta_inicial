<?php
include("../../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
    exit;
}
if($_SESSION["id"] != 3){
    header("location: ../sesiones/inicio.php");
    exit;
}

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Función para contar preguntas en una sección
function contarPreguntasSeccion($conexion, $seccionId) {
    $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM preguntas WHERE seccion_id = ?");
    $stmt->bind_param("i", $seccionId);
    $stmt->execute();
    $stmt->bind_result($totalPreguntas);
    $stmt->fetch();
    $stmt->close();
    return $totalPreguntas;
}

// Función para contar respuestas del usuario en una sección
function contarRespuestasUsuarioSeccion($conexion, $seccionId, $usuarioId) {
    $stmt = $conexion->prepare("SELECT COUNT(DISTINCT pregunta_id) as total FROM usuario_respuesta WHERE seccion_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $seccionId, $usuarioId);
    $stmt->execute();
    $stmt->bind_result($totalRespuestas);
    $stmt->fetch();
    $stmt->close();
    return $totalRespuestas;
}

// Función para verificar si la sección está completada
function seccionCompletada($conexion, $seccionId, $usuarioId) {
    $totalPreguntas = contarPreguntasSeccion($conexion, $seccionId);
    $totalRespuestas = contarRespuestasUsuarioSeccion($conexion, $seccionId, $usuarioId);
    return $totalPreguntas == $totalRespuestas;
}

// Obtener secciones
$sql = "SELECT id, nombre, descripcion FROM secciones";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu de Secciones</title>
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/menusecciones.css">
</head>
<body>
    <div class="contenedor rounded shadow">
        <h1>Menu de Secciones</h1>
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
                        $completado = seccionCompletada($conexion, $row["id"], $_SESSION["id"]) ? '✅' : '❌';

                        echo "<tr>";
                        echo "<td>" . $row["descripcion"] . "</td>";
                        echo "<td class='centrar'><button class='btn btn-success' onclick=\"window.location.href='seccion.php?seccion=" . urlencode($row["id"]) . "'\">Responder</button></td>";
                        echo "<td class='centrar'>" . $completado . "</td>";
                        echo "</tr>";
                    }
                } else {
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
