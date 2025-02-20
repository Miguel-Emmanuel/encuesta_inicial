<?php
// Si es tutor, obtenemos el ID de tutor
if ($rol == 2) {
    $idU = intval($idUsuario); // Asegurar que el ID del usuario es un entero válido

    // Verificar si la conexión está establecida
    if (!$conexion) {
        die("Error de conexión a la base de datos: " . mysqli_connect_error());
    }

    // Consulta para obtener el ID del tutor correspondiente al usuario
    $tutorQuery = "SELECT id FROM tutores WHERE usuario_id = ?";
    
    $stmt = $conexion->prepare($tutorQuery);
    if ($stmt) {
        $stmt->bind_param("i", $idU);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $tutor_id = $row['id']; // Guardamos el ID del tutor
        } else {
            echo "El usuario no está registrado como tutor.";
            exit;
        }
        $stmt->close();
    } else {
        die("Error en la consulta: " . $conexion->error);
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
        .search-input {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .no-results {
            color: red;
            font-style: italic;
            display: none;
        }
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
                <input type="text" id="searchEstudiante" class="search-input" placeholder="Buscar estudiante..." style="display: none;">
                <select class="form-select" id="estudiante" name="estudiante" required>
                    <option value="" selected disabled>Seleccione un estudiante</option>
                </select>
                <div id="noResults" class="no-results">No se encontraron coincidencias</div>
            </div>
            <button type="submit" class="btn btn-primary" formaction="../../../app/Controllers/reportes/PDF/encuestareporte.php" >Generar Reporte PDF</button>
            <button type="submit" class="btn btn-success" formaction="../../../app/Controllers/reportes/Excel/encuestareporte_excel.php" >Generar Reporte Excel</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var grupoSelect = document.getElementById('grupo');
            var estudianteSelect = document.getElementById('estudiante');
            var searchInput = document.getElementById('searchEstudiante');
            var noResultsMessage = document.getElementById('noResults');

            grupoSelect.addEventListener('change', function() {
                var grupoId = this.value;
                if (grupoId) {
                    fetch('../../../app/Controllers/reportes/PDF/obtener_estudiante.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'grupo_id=' + encodeURIComponent(grupoId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        estudianteSelect.innerHTML = '<option value="" selected disabled>Seleccione un estudiante</option>';
                        if (data.length > 0) {
                            data.forEach(estudiante => {
                                var option = document.createElement('option');
                                option.value = estudiante.estudiante_id;
                                option.textContent = estudiante.matricula + ' - ' + estudiante.estudiante;
                                estudianteSelect.appendChild(option);
                            });

                            // Mostramos el campo de búsqueda
                            searchInput.style.display = 'block';

                            // Filtrado de estudiantes mientras escriben
                            searchInput.addEventListener('input', function() {
                                var searchTerm = this.value.toLowerCase();
                                var options = estudianteSelect.querySelectorAll('option');
                                var matchFound = false;

                                options.forEach(function(option) {
                                    var text = option.textContent.toLowerCase();
                                    if (text.includes(searchTerm)) {
                                        option.style.display = 'block';  // Mostrar opciones que coinciden
                                        matchFound = true;
                                    } else {
                                        option.style.display = 'none';   // Ocultar las que no coinciden
                                    }
                                });

                                // Mostrar o ocultar el mensaje de no resultados
                                if (!matchFound) {
                                    noResultsMessage.style.display = 'block';
                                } else {
                                    noResultsMessage.style.display = 'none';
                                }
                            });
                        } else {
                            // Si no hay estudiantes
                            noResultsMessage.style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</body>
</html>
