<?php
require 'conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupo_id = $_POST['grupo']; // ID del grupo seleccionado
    $estudiantes_ids = $_POST['estudiantes']; // Array de IDs de los estudiantes seleccionados

    // Aquí puedes procesar la información como necesites, por ejemplo, actualizar la base de datos
    foreach ($estudiantes_ids as $id) {
        // Procesar cada ID de estudiante
        // Ejemplo: Actualizar algún registro en la base de datos
    }

    // Redireccionar o mostrar un mensaje de éxito
    echo "Estudiantes procesados correctamente.";
} else {
    echo "Faltan parámetros necesarios.";
}

header('Location: /public/views/estudiante_grupo/index.php?e=1');
?>
