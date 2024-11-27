<?php
require '../../../database/conexion.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupo_id = $_POST['grupo']; // Grupo actual
    $nuevo_grupo = $_POST['nuevo_grupo']; // Nuevo grupo al que se moverán
    $periodo = $_POST['periodo']; // Periodo educativo
    $estudiantes_ids = $_POST['estudiantes']; // IDs de los estudiantes seleccionados

    if (!empty($grupo_id) && !empty($nuevo_grupo) && !empty($periodo) && !empty($estudiantes_ids)) {
        // Desactivar los registros de los estudiantes en el grupo actual
        $stmt_baja = $conexion->prepare("UPDATE estudiante_grupo SET activo = 0 WHERE grupo_id = ? AND estudiante_id = ?");
        
        // Ejecutar la actualización de baja para cada estudiante en el grupo actual
        foreach ($estudiantes_ids as $estudiante_id) {
            $stmt_baja->bind_param('ii', $grupo_id, $estudiante_id);
            if (!$stmt_baja->execute()) {
                echo "Error al desactivar el estudiante con ID $estudiante_id: " . $stmt_baja->error;
                exit; // Salir si hay error en la actualización
            }
        }

        // Obtener el tutor asociado al nuevo grupo
        $stmt_tutor = $conexion->prepare("SELECT grupo_tutor.tutor_id 
                                          FROM grupo_tutor 
                                          INNER JOIN t_grupos ON grupo_tutor.grupo_id = t_grupos.id
                                          WHERE t_grupos.id = ?");
        $stmt_tutor->bind_param('i', $nuevo_grupo);
        $stmt_tutor->execute();
        $result_tutor = $stmt_tutor->get_result();
        $tutor = $result_tutor->fetch_assoc();
        $tutor_id = $tutor['tutor_id'] ?? null; // Asegúrate de manejar el caso de tutor no encontrado

        if ($tutor_id) {
            // Preparar la consulta de inserción para los nuevos registros
            $stmt_insert = $conexion->prepare("INSERT INTO estudiante_grupo (estudiante_id, grupo_id, tutor_id, periodo_id, activo) 
                                               VALUES (?, ?, ?, ?, 1)");

            foreach ($estudiantes_ids as $estudiante_id) {
                // Ejecutar la consulta para cada estudiante
                $stmt_insert->bind_param('iiii', $estudiante_id, $nuevo_grupo, $tutor_id, $periodo);
                if (!$stmt_insert->execute()) {
                    echo "Error al insertar estudiante con ID $estudiante_id: " . $stmt_insert->error;
                }
            }

            // Redireccionar con éxito
            header('Location: /public/views/estudiante_grupo/index.php?e=1');
            exit;
        } else {
            echo "No se encontró un tutor para el grupo seleccionado.";
        }
    } else { ?>
    
    <script>
        alert("Faltan parámetros necesarios para procesar la solicitud.");
        window.location.href = '/public/views/estudiante_grupo/index.php';

    </script>
        <?php
    }
} else {
    echo "Método no permitido.";
}
?>
