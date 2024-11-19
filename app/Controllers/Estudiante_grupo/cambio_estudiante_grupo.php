<?php
require '../../../database/conexion.php';

// $estudiante_id  = $conexion->real_escape_string($_POST['estudiante_id']);
if (isset($_POST['estudiante_cambio_id'])) {
    $estudiante_id = $_POST['estudiante_cambio_id'];
    $estudiantes_id = explode(',', $estudiante_id[0]);


    for ($i = 0; $i < count($estudiantes_id); $i++) {
        $grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);

        $obtener_tutor = "SELECT 
                                grupo_tutor.tutor_id
                            FROM 
                                grupo_tutor
                            INNER JOIN 
                                t_grupos ON grupo_tutor.grupo_id = t_grupos.id
                            WHERE 
                                t_grupos.id = $grupo_id";

        $result = $conexion->query($obtener_tutor);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tutor_id = $row['tutor_id']; // Extraer el tutor_id correctamente

            $baja = "UPDATE estudiante_grupo SET activo = 0 WHERE estudiante_id = $estudiantes_id[$i]";
            $baja2 = $conexion->query($baja);

        } else {
            echo "No se encontró tutor para el grupo especificado.";
        }


        $periodo_id = $conexion->real_escape_string($_POST['periodo_id']);

        
        
        $sql = "INSERT INTO estudiante_grupo (estudiante_id , grupo_id, periodo_id) VALUES ('$estudiantes_id[$i]','$grupo_id ','$periodo_id')";
        if ($conexion->query($sql)) {
            $id = $conexion->insert_id;
        }
    }
} else {
    echo "Error en alguno de los elementos.";
}

header('Location: /public/views/estudiante_grupo/index.php?e=1');
