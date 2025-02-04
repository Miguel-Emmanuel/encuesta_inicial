<?php
require '../../../database/conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos
//header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

if (isset($_POST['grupo_id'])) {
    $grupo_id = $_POST['grupo_id'];
    
    $sqlestudiantes ="
    SELECT e.matricula, CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre_completo,
           g.nomenclatura AS grupo, u.correo
    FROM estudiantes AS e
    INNER JOIN usuarios AS u ON e.usuario_id = u.id
    INNER JOIN estudiante_grupo AS eg ON eg.estudiante_id = e.id
    INNER JOIN t_grupos AS g ON g.id = eg.grupo_id
    WHERE e.id = ?
";
    
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
