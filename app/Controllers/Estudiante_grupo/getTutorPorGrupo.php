<?php
require '../../../database/conexion.php';

if (isset($_POST['grupo_id'])) {
    $grupo_id = $_POST['grupo_id'];

    $sql = "SELECT t.id, u.nombre, u.apellido_paterno, u.apellido_materno 
    FROM grupo_tutor gt 
    INNER JOIN tutores t ON gt.tutor_id = t.id 
    INNER JOIN usuarios u ON t.usuario_id = u.id 
    WHERE gt.grupo_id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $grupo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        echo json_encode([
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido_paterno' => $row['apellido_paterno'],
            'apellido_materno' => $row['apellido_materno']
        ]);
    }else{
        echo json_encode(['tutor_nombre' => 'Tutor no encontrado']);
    }

    $stmt->close();
}
?>