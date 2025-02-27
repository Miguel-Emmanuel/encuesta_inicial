<?php
include("../../../database/conexion.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
if (!isset($_GET['matricula'])) {
    echo json_encode(["error" => "Matrícula no proporcionada"]);
    exit;
}

$matricula = $conexion->real_escape_string($_GET['matricula']);

// Ejecutar la consulta
$sql = "SELECT 
    e.matricula,
    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
    ce.grupo AS grupo_vulnerable,
    p.pregunta,
    ce.respuesta,
    r.operador,
    r.valor,
    r.descripcion,
    COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
    COALESCE(t_gr.nomenclatura, '') AS grupo_academico,
    COALESCE(p_es.alias, '') AS periodo,
    COALESCE(prog.nombre, '') AS carrera
FROM clasificacion_estudiantes ce
JOIN estudiantes e ON ce.estudiante_id = e.id
JOIN usuarios u ON e.usuario_id = u.id
JOIN preguntas p ON ce.pregunta_id = p.id
LEFT JOIN reglas_clasificacion r 
    ON ce.pregunta_id = r.pregunta_id 
    AND ce.grupo = r.grupo
LEFT JOIN estudiante_grupo eg 
    ON eg.estudiante_id = e.id
LEFT JOIN t_grupos t_gr 
    ON eg.grupo_id = t_gr.id
LEFT JOIN grupo_tutor gt 
    ON t_gr.id = gt.grupo_id 
    AND eg.periodo_id = gt.periodo_id
LEFT JOIN tutores t 
    ON gt.tutor_id = t.id
LEFT JOIN usuarios tu 
    ON t.usuario_id = tu.id
LEFT JOIN periodos_escolar p_es 
    ON eg.periodo_id = p_es.id
LEFT JOIN programa_edu prog 
    ON t_gr.programa_e = prog.id
WHERE e.matricula = '$matricula'
ORDER BY ce.grupo;";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $datos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
} else {
    echo json_encode([]);
}

$conexion->close();
?>