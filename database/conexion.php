<?php
// db.php
$host = 'localhost';
$db = 'encuesta_01';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $conexion = new mysqli($host, $user, $pass, $db);
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
} catch (\Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

$sql = "SELECT pregunta_id, depende_de_pregunta_id FROM dependencias_preguntas";
$result = $conexion->query($sql);

$dependencias = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dependencias[] = $row;
    }
}


// echo json_encode($dependencias);
?>
