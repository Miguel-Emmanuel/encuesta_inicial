<?php
// Incluir la conexión

include("../../../database/conexion.php");

// Verificar si se envió el grupo vulnerable
if (isset($_POST['grupo_vulnerable'])) {
    $grupoVulnerable = $_POST['grupo_vulnerable'];
    $sql = "";

    // Consultas según el grupo vulnerable
    switch ($grupoVulnerable) {
        case 'paternal':
            $sql = "
                SELECT 
                    e.matricula,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    u.email,
                    COALESCE(t_gr.nomenclatura, '') AS grupo,
                    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
                    COALESCE(p.alias, '') AS periodo_escolar,
                    COALESCE(prog.nombre, '') AS carrera,
                    er.respuesta AS respuesta,
                    pr.pregunta AS pregunta,
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas pr ON er.pregunta_id = pr.id
                JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE 
                    (er.pregunta_id = 10 AND er.respuesta IN ('Divorciado(a)', 'Viudo(a)', 'Unión libre', 'Casado(a)'))
                    OR
                    (er.pregunta_id = 11 AND er.respuesta IN ('2 hijos(as)', 'Más de 2 hijos(as)'))
                GROUP BY e.matricula, estudiante, u.email, grupo, tutor, periodo_escolar, carrera, respuesta, pregunta, er.created_at;
            ";
            break;

        case 'economico':
            $sql = "
                SELECT 
                    e.matricula,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    u.email,
                    COALESCE(t_gr.nomenclatura, '') AS grupo,
                    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
                    COALESCE(p.alias, '') AS periodo_escolar,
                    COALESCE(prog.nombre, '') AS carrera,
                    er.respuesta AS respuesta,
                    pr.pregunta AS pregunta,
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas pr ON er.pregunta_id = pr.id
                JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE 
                    (er.pregunta_id = 12 AND er.respuesta != 'no')
                    OR
                    (er.pregunta_id = 50 AND er.respuesta = 'Si')
                    OR
                    (er.pregunta_id = 60 AND er.respuesta <= 6223.20)
                    OR
                    (er.pregunta_id = 63 AND er.respuesta = 'Departamento cerca de la Universidad')
                    OR
                    (er.pregunta_id = 67 AND er.respuesta IN ('De 60 a 90 minutos', 'De 90 a 120 minutos', 'Más de 120 minutos'))
                GROUP BY e.matricula, estudiante, u.email, grupo, tutor, periodo_escolar, carrera, respuesta, pregunta, er.created_at;
            ";
            break;
            
        case 'salud':
            $sql = "
                SELECT 
                    e.matricula,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    u.email,
                    COALESCE(t_gr.nomenclatura, '') AS grupo,
                    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
                    COALESCE(p.alias, '') AS periodo_escolar,
                    COALESCE(prog.nombre, '') AS carrera,
                    er.respuesta AS respuesta,
                    pr.pregunta AS pregunta,
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas pr ON er.pregunta_id = pr.id
                JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE 
                    (er.pregunta_id = 73 AND er.respuesta IN ('Tienes alguna deficiencia auditiva', 'Problemas de movilidad motriz', 'Otro:'))
                    OR
                    (er.pregunta_id = 80 AND er.respuesta = 'Si')
                    OR
                    (er.pregunta_id = 74 AND er.respuesta = 'Si')
                    OR
                    (er.pregunta_id = 76 AND er.respuesta = 'Si')
                    OR
                    (er.pregunta_id = 77 AND er.respuesta = 'alergia')
                    OR
                    (er.pregunta_id = 78 AND er.respuesta = 'si')
                GROUP BY e.matricula, estudiante, u.email, grupo, tutor, periodo_escolar, carrera, respuesta, pregunta, er.created_at;
            ";
            break;

        case 'baja':
            $sql = "
                SELECT 
                    e.matricula,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    u.email,
                    COALESCE(t_gr.nomenclatura, '') AS grupo,
                    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
                    COALESCE(p.alias, '') AS periodo_escolar,
                    COALESCE(prog.nombre, '') AS carrera,
                    er.respuesta AS respuesta,
                    pr.pregunta AS pregunta,
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas pr ON er.pregunta_id = pr.id
                JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE 
                    (er.pregunta_id = 94 AND er.respuesta = 'Si')
                GROUP BY e.matricula, estudiante, u.email, grupo, tutor, periodo_escolar, carrera, respuesta, pregunta, er.created_at;
            ";
            break;

        case 'etnia':
            $sql = "
                SELECT 
                    e.matricula,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    u.email,
                    COALESCE(t_gr.nomenclatura, '') AS grupo,
                    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
                    COALESCE(p.alias, '') AS periodo_escolar,
                    COALESCE(prog.nombre, '') AS carrera,
                    er.respuesta AS respuesta,
                    pr.pregunta AS pregunta,
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas pr ON er.pregunta_id = pr.id
                JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE 
                    (er.pregunta_id = 72 AND er.respuesta != 'no')
                GROUP BY e.matricula, estudiante, u.email, grupo, tutor, periodo_escolar, carrera, respuesta, pregunta, er.created_at;
            ";
            break;

        default:
            echo json_encode(['error' => 'Grupo vulnerable no válido']);
            exit;
    }

    // Ejecutar consulta y enviar resultados
    $result = $conexion->query($sql);
    $data = [];

    setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'es_MX.UTF-8');
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fechaOriginal = $row['created_at'];
            $formattedDate = $fechaOriginal ? strftime("%d de %B de %Y", strtotime($fechaOriginal)) : 'Sin fecha'; // Validación de fechas
            $data[] = [
                'matricula' => $row['matricula'],
                'nombre_completo' => $row['estudiante'],
                'email' => $row['email'],
                'grupo_vulnerable' => $grupoVulnerable,
                'grupo' => $row['grupo'] ?? 'No asignado',
                'tutor' => $row['tutor'] ?? 'Sin tutor',
                'periodo_escolar' => $row['periodo_escolar'] ?? 'No definido',
                'carrera' => $row['carrera'] ?? 'No especificada',
                'pregunta' => $row['pregunta'] ?? 'Sin pregunta',
                'respuesta' => $row['respuesta'] ?? 'Sin respuesta',
                'observaciones' => $row['observaciones'] ?? 'Sin observaciones',
                'created_at' => $formattedDate
];
        }
    }

    // Enviar datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Error si no se envió un grupo vulnerable
    echo json_encode(['error' => 'No se especificó un grupo vulnerable']);
}

$conexion->close();
?>