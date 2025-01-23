<?php
require("../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php");

// Si es tutor, obtenemos el ID de tutor
if ($rol == 2) {
    $idU = intval($usuario_id);
    $tutorQuery = "SELECT t.id AS tutor_id FROM tutores AS t INNER JOIN usuarios AS u ON t.usuario_id = u.id WHERE u.id = $idU";
    $tutorResult = mysqli_query($conexion, $tutorQuery);
    if ($tutorResult && $row = mysqli_fetch_assoc($tutorResult)) {
        $tutor_id = $row['tutor_id'];
    } else {
        echo "Su usuario no está registrado como tutor";
        exit;
    }
}

// Consulta para obtener los grupos según el rol
if ($rol == 1) {
    // Si es director, trae todos los grupos
    $sqlGrupos = "SELECT * FROM t_grupos WHERE activo = 1";
} elseif ($rol == 2) {
    // Si es tutor, solo los grupos que le correspondan
    $sqlGrupos = "SELECT g.* FROM t_grupos AS g INNER JOIN grupo_tutor AS gt ON gt.grupo_id = g.id WHERE gt.tutor_id = $tutor_id";
}
$grupos = $conexion->query($sqlGrupos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Encuesta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .w-50 { max-width: 50%; }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Generar Reporte</h1>
        <form action="../../../app/Controllers/reportes/encuestareporte.php" method="POST">
            <div class="mb-3 w-50">
                <label for="grupo" class="form-label">Seleccionar Grupo</label>
                <select class="form-select" id="grupo" name="grupo" required>
                    <option value="" selected disabled>Seleccione un grupo</option>
                    <?php while ($grupo = $grupos->fetch_assoc()): ?>
                        <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nomenclatura']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3 w-50">
                <label for="estudiante" class="form-label">Seleccionar Estudiante</label>
                <select class="form-select" id="estudiante" name="estudiante" required>
                    <option value="" selected disabled>Seleccione un estudiante</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var grupoSelect = document.getElementById('grupo');
            var estudianteSelect = document.getElementById('estudiante');

            grupoSelect.addEventListener('change', function() {
                var grupoId = this.value;
                if (grupoId) {
                    fetch('../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'grupo_id=' + encodeURIComponent(grupoId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        estudianteSelect.innerHTML = '<option value="" selected disabled>Seleccione un estudiante</option>';
                        data.forEach(estudiante => {
                            var option = document.createElement('option');
                            option.value = estudiante.estudiante_id;
                            option.textContent = estudiante.matricula + ' - ' + estudiante.estudiante;
                            estudianteSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</body>
</html>
