<?php
// Conectar a la base de datos
// require("../../../app/Controllers/auth.php");
include("../../../database/conexion.php");

// Variable para almacenar los resultados de la consulta
$grupos = [];
$grupoSeleccionado = isset($_POST['grupo']) ? $_POST['grupo'] : '';

// Consulta para obtener todos los grupos vulnerables (y "Todos" como opción)
$sql_grupos = "SELECT DISTINCT grupo FROM clasificacion_estudiantes";
$resultado_grupos = $conexion->query($sql_grupos);

if ($resultado_grupos) {
    // Guardar todos los grupos disponibles en un arreglo
    while ($grupo = $resultado_grupos->fetch_assoc()) {
        $grupos[] = $grupo['grupo'];
    }
}

// Verificar si se seleccionó un grupo específico
if ($grupoSeleccionado != '' && $grupoSeleccionado != 'todos') {
    // Consulta para obtener los estudiantes de un grupo específico
    $sql_estudiantes = "
        SELECT 
            ce.grupo,
            CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
            e.matricula,
            ce.fecha_clasificacion
        FROM clasificacion_estudiantes ce
        JOIN estudiantes e ON ce.estudiante_id = e.id
        JOIN usuarios u ON e.usuario_id = u.id
        WHERE ce.grupo = '$grupoSeleccionado'
        ORDER BY u.nombre
    ";
} else {
    // Consulta para obtener todos los estudiantes de todos los grupos
    $sql_estudiantes = "
        SELECT 
            ce.grupo,
            CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
            e.matricula,
            ce.fecha_clasificacion
        FROM clasificacion_estudiantes ce
        JOIN estudiantes e ON ce.estudiante_id = e.id
        JOIN usuarios u ON e.usuario_id = u.id
        ORDER BY ce.grupo, u.nombre
    ";
}

// Ejecutar la consulta de estudiantes
$resultado_estudiantes = $conexion->query($sql_estudiantes);
$estudiantes = [];

if ($resultado_estudiantes && $resultado_estudiantes->num_rows > 0) {
    while ($estudiante = $resultado_estudiantes->fetch_assoc()) {
        $estudiantes[$estudiante['grupo']][] = $estudiante;
    }
} else {
    echo "No se encontraron estudiantes en ese grupo.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clasificación de Estudiantes por Grupo Vulnerable</title>
    <style>
        table {
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 8px 12px;
        }
        h2 {
            background: #f0f0f0;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Clasificación de Estudiantes por Grupo Vulnerable</h1>

    <!-- Formulario para seleccionar el grupo vulnerable -->
    <form method="POST" action="">
        <label for="grupo">Seleccionar Grupo Vulnerable:</label>
        <select name="grupo" id="grupo">
            <option value="todos" <?php echo ($grupoSeleccionado == 'todos') ? 'selected' : ''; ?>>Todos</option>
            <?php foreach ($grupos as $grupo): ?>
                <option value="<?php echo $grupo; ?>" <?php echo ($grupoSeleccionado == $grupo) ? 'selected' : ''; ?>><?php echo htmlspecialchars($grupo); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Ver Estudiantes</button>
    </form>

    <?php if (!empty($estudiantes)): ?>
        <?php foreach ($estudiantes as $grupo => $estudiantesGrupo): ?>
            <h2>Grupo: <?php echo htmlspecialchars($grupo); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Estudiante</th>
                        <th>Fecha de Clasificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantesGrupo as $est): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($est['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($est['estudiante']); ?></td>
                            <td><?php echo htmlspecialchars($est['fecha_clasificacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se encontraron estudiantes para el grupo seleccionado.</p>
    <?php endif; ?>
</body>
</html>
