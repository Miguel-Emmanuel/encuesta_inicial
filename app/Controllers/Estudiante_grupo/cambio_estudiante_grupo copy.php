<?php
require '../../../database/conexion.php';

// Validación para asegurarse de que el formulario se envió correctamente
if (isset($_POST['estudiante_cambio_id'], $_POST['grupo_id'], $_POST['periodo_id'])) {
    // Extraer valores del formulario y sanitizarlos
    $estudiante_id = $_POST['estudiante_cambio_id'];
    $estudiantes_id = explode(',', $estudiante_id);  // Suponiendo que es una cadena separada por comas
    $grupo_id = $_POST['grupo_id'];
    $periodo_id = $_POST['periodo_id'];

    // Preparar consulta para obtener el tutor del grupo
    $obtener_tutor = "SELECT grupo_tutor.tutor_id FROM grupo_tutor 
                      INNER JOIN t_grupos ON grupo_tutor.grupo_id = t_grupos.id
                      WHERE t_grupos.id = ?";
    
    if ($stmt = $conexion->prepare($obtener_tutor)) {
        $stmt->bind_param("i", $grupo_id); // 'i' para entero
        $stmt->execute();
        $stmt->bind_result($tutor_id);
        
        if ($stmt->fetch()) {
            // Si se encuentra un tutor, proceder con la baja y el alta
            foreach ($estudiantes_id as $est_id) {
                // Desactivar al estudiante
                $baja = "UPDATE estudiante_grupo SET activo = 0 WHERE estudiante_id = ?";
                if ($baja_stmt = $conexion->prepare($baja)) {
                    $baja_stmt->bind_param("i", $est_id);
                    $baja_stmt->execute();
                }

                // Insertar al estudiante en el nuevo grupo
                $sql = "INSERT INTO estudiante_grupo (estudiante_id, grupo_id, tutor_id, periodo_id) 
                        VALUES (?, ?, ?, ?)";
                
                if ($insert_stmt = $conexion->prepare($sql)) {
                    $insert_stmt->bind_param("iiii", $est_id, $grupo_id, $tutor_id, $periodo_id);
                    $insert_stmt->execute();
                }
            }
        } else {
            echo "No se encontró tutor para el grupo especificado.";
        }
        
        $stmt->close(); // Cerrar la consulta
    } else {
        echo "Error en la consulta de tutor.";
    }
} else {
    echo "Faltan parámetros necesarios.";
}

header('Location: /public/views/estudiante_grupo/index.php?e=1');
?>
