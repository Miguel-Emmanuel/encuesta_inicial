<?php
// db.php
$host = 'localhost';
$db = 'encuesta_01';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


// $host = '162.240.99.108';
// $db = 'desarrollosutvt_encuesta';
// $user = 'desarrollosutvt_mike';
// $pass = 'AIOM020605';
// $charset = 'utf8mb4';
try {
    $conexion = new mysqli($host, $user, $pass, $db);
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    // Establecer la codificación de caracteres UTF-8
    $conexion->set_charset($charset);

} catch (\Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

$sql = "SELECT pregunta_id, depende_de_pregunta_id FROM dependencias_preguntas";
$result = $conexion->query($sql);

$verificacion = $conexion->query("DELETE FROM links WHERE created_at < (NOW() - INTERVAL 15 MINUTE);");

$dependencias = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dependencias[] = $row;
    }
}

// echo json_encode($dependencias);
?>

