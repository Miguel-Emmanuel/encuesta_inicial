<?php
require '../../../database/conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos
header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

if (isset($_POST['grupo_id'])) {
    $grupo_id = $_POST['grupo_id'];
    
    $sqlestudiantes = "SELECT 
                        e.id AS estudiante_id,
                        e.matricula AS matricula,
                        CONCAT(u.apellido_paterno, ' ', u.apellido_materno, ' ', u.nombre) AS estudiante
                      FROM 
                        estudiantes e
                      JOIN 
                        usuarios u ON e.usuario_id = u.id
                      JOIN 
                        estudiante_grupo eg ON e.id = eg.estudiante_id
                      WHERE 
                        eg.grupo_id = ? AND eg.activo = 1";
    
    $stmt = $conexion->prepare($sqlestudiantes);
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $estudiantes = array();
    while ($row = $result->fetch_assoc()) {
        $estudiantes[] = $row;
    }
    
    echo json_encode($estudiantes);
}
?>
