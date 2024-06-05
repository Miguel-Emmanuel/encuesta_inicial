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

// Crear conexión

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener secciones
$sql = "SELECT s.id, s.nombre, COUNT(ur.id) as completado
        FROM secciones s
        LEFT JOIN preguntas p ON s.id = p.seccion_id
        LEFT JOIN usuario_respuesta ur ON p.id = ur.pregunta_id AND ur.usuario_id = 1  -- Reemplaza 1 con el ID del usuario actual
        GROUP BY s.id, s.nombre";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu de Secciones</title>
</head>
<body>
    <h1>Menu de Secciones</h1>
    <ul>
    <?php
   if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $completado = $row['completado'] > 0 ? '✅' : '❌';
        // Reemplazar seccion_id con seccion
        echo "<li><a href='seccion.php?seccion=" . urlencode($row["id"]) . "'>" . $row["nombre"] . "</a> " . $completado . "</li>";
    }
} else {
    echo "0 resultados";
}

    $conexion->close();
    ?>
    </ul>
</body>
</html>
