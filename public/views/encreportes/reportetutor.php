<?php
session_start();
require_once('../../../database/conexion.php');  // Conexión a la base de datos
require_once('../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php');

// Obtener el ID del usuario y el rol desde la sesión
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

// Si el rol es tutor, obtenemos los grupos donde el tutor está asignado
if ($rol == 2) {
    $sqlTutor = "SELECT t.id AS tutor_id FROM tutores t 
                 INNER JOIN usuarios u ON t.usuario_id = u.id 
                 WHERE u.id = ?";
    
    // Preparar la consulta para obtener el tutor_id
    $stmt = $conexion->prepare($sqlTutor);
    $stmt->bind_param("i", $usuario_id);  // Filtrar por el ID del tutor
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el tutor está registrado, se obtiene el ID del tutor
    if ($row = $result->fetch_assoc()) {
        $tutor_id = $row['tutor_id'];
    } else {
        echo "Su usuario no está registrado como tutor.";
        exit;
    }

    // Consultar los grupos donde el tutor está asignado
    $sqlGrupos = "SELECT g.id, g.nombre FROM t_grupos g
                  INNER JOIN grupo_tutor gt ON g.id = gt.grupo_id
                  WHERE gt.tutor_id = ?";
    
    // Preparar la consulta para obtener los grupos
    $stmtGrupos = $conexion->prepare($sqlGrupos);
    $stmtGrupos->bind_param("i", $tutor_id);
    $stmtGrupos->execute();
    $resultGrupos = $stmtGrupos->get_result();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Encuesta - Tutor</title>
    <!-- Bootstrap ya está incorporado -->
</head>
<body>

<div class="container mt-5">
    <h2>Generar Reporte Encuesta - Tutor</h2>

    <form action="reporte_tutor.php" method="POST">
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Selecciona un grupo</label>
            <select name="grupo_id" id="grupo_id" class="form-select" required>
                <option value="">Seleccione un grupo</option>
                <?php while ($grupo = $resultGrupos->fetch_assoc()): ?>
                    <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <?php
        // Si el grupo ha sido seleccionado, obtenemos los estudiantes de ese grupo
        if (isset($_POST['grupo_id'])) {
            $grupo_id = $_POST['grupo_id'];

            // Consulta para obtener los estudiantes del grupo seleccionado
            $sqlEstudiantes = "SELECT e.id AS estudiante_id, e.matricula, 
                               CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante
                               FROM estudiantes e
                               JOIN usuarios u ON e.usuario_id = u.id
                               JOIN estudiante_grupo eg ON e.id = eg.estudiante_id
                               WHERE eg.grupo_id = ? AND eg.activo = 1";

            $stmtEstudiantes = $conexion->prepare($sqlEstudiantes);
            $stmtEstudiantes->bind_param("i", $grupo_id);
            $stmtEstudiantes->execute();
            $resultEstudiantes = $stmtEstudiantes->get_result();
        }
        ?>

        <?php if (isset($resultEstudiantes)): ?>
            <div class="mb-3">
                <label for="estudiante_id" class="form-label">Selecciona un estudiante</label>
                <select name="estudiante_id" id="estudiante_id" class="form-select" required>
                    <option value="">Seleccione un estudiante</option>
                    <?php while ($estudiante = $resultEstudiantes->fetch_assoc()): ?>
                        <option value="<?php echo $estudiante['estudiante_id']; ?>">
                            <?php echo $estudiante['matricula'] . ' - ' . $estudiante['estudiante']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Generar reporte</button>
    </form>
</div>

</body>
</html>
