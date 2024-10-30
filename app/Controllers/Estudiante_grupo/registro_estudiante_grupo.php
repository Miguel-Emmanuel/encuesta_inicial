<?php
require '../../../database/conexion.php';

// $estudiante_id  = $conexion->real_escape_string($_POST['estudiante_id']);
if (isset($_POST['estudiante_id'])) {
    $estudiante_id = $_POST['estudiante_id'];
    $estudiantes_id = explode(',', $estudiante_id[0]);


    for ($i = 0; $i < count($estudiantes_id); $i++) {
        $grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);
        $tutor_id  = $conexion->real_escape_string($_POST['tutor_id']);
        $periodo_id = $conexion->real_escape_string($_POST['periodo_id']);


        $sql = "INSERT INTO estudiante_grupo (estudiante_id , grupo_id, tutor_id , periodo_id) VALUES ('$estudiantes_id[$i]','$grupo_id ','$tutor_id','$periodo_id')";
        if ($conexion->query($sql)) {
            $id = $conexion->insert_id;
        }
    }
} else {
    echo "Error en alguno de los elementos.";
}

header('Location: /public/views/estudiante_grupo/index.php?e=1');
