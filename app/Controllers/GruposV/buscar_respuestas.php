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
            $grupoVulnerable = 'Paternal';
            $sql = "
                SELECT 
                    e.matricula,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.email,
                    er.respuesta AS respuesta,
                     p.pregunta AS pregunta, -- Incluimos la pregunta
                    er.created_at
                FROM respuestas er
                JOIN estudiantes e ON er.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas p ON er.pregunta_id = p.id -- Relación con preguntas
                WHERE 
                    (er.pregunta_id = 10 AND er.respuesta IN ('Divorciado(a)', 'Viudo(a)', 'Unión libre'))
                    OR
                    (er.pregunta_id = 11 AND er.respuesta IN ('2 hijos(as)', 'Más de 2 hijos(as)'))
                GROUP BY e.id;
            ";
            break;

            case 'economico':
                $grupoVulnerable = 'Económico';
                $sql = "
                SELECT 
                    e.matricula,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.email,
                    r.respuesta AS respuesta,
                        p.pregunta AS pregunta, -- Incluimos la pregunta
                    r.created_at
     
                FROM respuestas r
                JOIN estudiantes e ON r.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas p ON r.pregunta_id = p.id -- Relación con preguntas
                WHERE 
                    (
                        -- Pregunta 12: Cualquier respuesta diferente a 'No' se considera vulnerable
                        (r.pregunta_id = 12 AND r.respuesta != 'no')
                    )
                    OR
                    (
                        -- Pregunta 50: Respuesta 'Sí'
                        (r.pregunta_id = 50 AND r.respuesta = 'Si')
                    )
                    OR
                    (
                        -- Pregunta 60: Ingreso familiar mensual, comparado con el umbral.
                        (r.pregunta_id = 60 AND r.respuesta <= 6223.20)  -- Consideramos el umbral de un salario mínimo
                    )
                    OR
                    (
                        -- Pregunta 63: Respuesta 'Departamento cerca de la Universidad'
                        (r.pregunta_id = 63 AND r.respuesta = 'Departamento cerca de la Universidad')
                    )
                    OR
                    (
                        -- Pregunta 67: Respuestas que indican un tiempo de transporte largo
                        (r.pregunta_id = 67 AND r.respuesta IN ('De 60 a 90 minutos', 'De 90 a 120 minutos', 'Más de 120 minutos'))
                    )
                GROUP BY e.id;
            ";
            
                break;
                
            case 'salud':
                $grupoVulnerable = 'Salud';

                $sql = "
                SELECT 
                    e.matricula,
                    u.nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    u.email,
                    r.respuesta AS respuesta,
                        p.pregunta AS pregunta, -- Incluimos la pregunta
                    r.created_at
                FROM respuestas r
                JOIN estudiantes e ON r.estudiante_id = e.id
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN preguntas p ON r.pregunta_id = p.id -- Relación con preguntas
                WHERE 
                    (
                        -- Pregunta 73: Tienes alguna deficiencia auditiva, problemas de movilidad motriz, o 'Otro'
                        (r.pregunta_id = 73 AND r.respuesta IN ('Tienes alguna deficiencia auditiva', 'Problemas de movilidad motriz', 'Otro:'))
                    )
                    OR
                    (
                        -- Pregunta 80: Respuesta 'Sí'
                        (r.pregunta_id = 80 AND r.respuesta = 'Si')
                    )
                    OR
                    (
                        -- Pregunta 74: Respuesta 'Sí'
                        (r.pregunta_id = 74 AND r.respuesta = 'Si')
                    )
                GROUP BY e.id;
            ";
            
            
                break;

                case 'baja':
                $grupoVulnerable = 'Deserción Académic';

                    $sql = "
    SELECT 
        e.matricula,
        u.nombre,
        u.apellido_paterno,
        u.apellido_materno,
        u.email,
        r.respuesta AS respuesta,
            p.pregunta AS pregunta, -- Incluimos la pregunta
        r.created_at
    FROM respuestas r
    JOIN estudiantes e ON r.estudiante_id = e.id
    JOIN usuarios u ON e.usuario_id = u.id
    JOIN preguntas p ON r.pregunta_id = p.id -- Relación con preguntas
    WHERE 
        (
            -- Pregunta 94: Respuesta 'Sí' indica posible deserción académica
            (r.pregunta_id = 94 AND r.respuesta = 'Si')
        )
    GROUP BY e.id;
";

                    break;

                    case 'etnia':
                        $grupoVulnerable = 'Indígena';

                        $sql = "
                        SELECT 
                            e.matricula,
                            u.nombre,
                            u.apellido_paterno,
                            u.apellido_materno,
                            u.email,
                            r.respuesta AS respuesta,
                                p.pregunta AS pregunta, -- Incluimos la pregunta
                            r.created_at
                        FROM respuestas r
                        JOIN estudiantes e ON r.estudiante_id = e.id
                        JOIN usuarios u ON e.usuario_id = u.id
                        JOIN preguntas p ON r.pregunta_id = p.id -- Relación con preguntas
                        WHERE 
                            (
                                -- Pregunta 72: Cualquier respuesta diferente a 'no' indica que el estudiante habla una lengua indígena
                                (r.pregunta_id = 72 AND r.respuesta != 'no')
                            )
                        GROUP BY e.id;
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
                'nombre_completo' => $row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'],
                'email' => $row['email'],
                'grupo_vulnerable' => $grupoVulnerable, // Agregar el grupo vulnerable seleccionado
                'pregunta' => $row['pregunta'], // Respuesta clave que indica el criterio del grupo vulnerable
                'respuesta' => $row['respuesta'], // Respuesta clave que indica el criterio del grupo vulnerable
                'observaciones' => isset($row['observaciones']) ? $row['observaciones'] : null, // Observaciones o comentarios adicionales si existen
                'created_at'       => $formattedDate        
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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion->close();
?>
