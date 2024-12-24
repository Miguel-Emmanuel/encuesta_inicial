<?php
require("../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php");

if($rol == 2):
    $idU = intval($idUsuario);

// Consulta para obtener el ID del tutor relacionado
$tutor = "SELECT t.id AS tutor_id 
          FROM tutores AS t
          INNER JOIN usuarios AS u ON t.usuario_id = u.id
          WHERE u.id = $idU";

// Ejecutar la consulta
$tres = mysqli_query($conexion, $tutor);

if ($tres && $row = mysqli_fetch_assoc($tres)) {
    $tutor_id = $row['tutor_id']; // Aquí obtienes el ID del tutor como número
} else {
    echo "Su usuario no esta registrado como tutor";
}
endif;
if($rol == 1):
$sqlGrupos = "SELECT * FROM t_grupos";
$grupos = $conexion->query($sqlGrupos);
elseif($rol == 2):
    $sqlGrupos = "SELECT g.* 
              FROM t_grupos AS g
              INNER JOIN grupo_tutor AS gt ON gt.grupo_id = g.id
              WHERE gt.tutor_id = $tutor_id";
    $grupos = $conexion->query($sqlGrupos);
endif;


$sqlPeriodos = "SELECT * FROM periodos_escolar";
$periodos = $conexion->query($sqlPeriodos);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Grupo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        th.sel {
            width: 5%;
            font-size: smaller;
        }

        .check {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 0.25rem;
            border: 2px solid #198754;
            display: block;
            margin: 0 auto;
        }

        .check:checked {
            background-color: #198754;
            border-color: #145a32;
            box-shadow: 0 0 5px #145a32;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Reporte de la encuesta</h1>
        <form action="../../../app/Controllers/Estudiante_grupo/cambio_estudiante_grupo.php" method="POST">
            <div class="mb-3">
                <label for="grupo" class="form-label">Seleccionar Grupo</label>
                <select class="form-select" id="grupo" name="grupo" required>
                    <option value="" selected disabled>Seleccione un grupo</option>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nomenclatura']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="sel">
                                Seleccionar Todo
                                <br>
                                <input class="check" type="checkbox" id="selectAll">
                            </th>
                            <th>Id Estudiante</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-estudiantes">
                        <!-- Las filas de estudiantes se agregarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var grupoSelect = document.getElementById('grupo');
            var tablaEstudiantes = document.getElementById('tabla-estudiantes');
            var selectAllCheckbox = document.getElementById('selectAll');

            grupoSelect.addEventListener('change', function() {
                var grupoId = this.value;

                if (grupoId) {
                    fetch('../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'grupo_id=' + encodeURIComponent(grupoId)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta de la red.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        tablaEstudiantes.innerHTML = '';

                        data.forEach(estudiante => {
                            var row = document.createElement('tr');

                            var cellSelect = document.createElement('td');
                            var checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'estudiantes[]';
                            checkbox.value = estudiante.estudiante_id;
                            checkbox.classList.add('check');
                            cellSelect.appendChild(checkbox);
                            row.appendChild(cellSelect);

                            var cellId = document.createElement('td');
                            cellId.textContent = estudiante.estudiante_id;
                            row.appendChild(cellId);

                            var cellName = document.createElement('td');
                            cellName.textContent = estudiante.estudiante;
                            row.appendChild(cellName);

                            tablaEstudiantes.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error:', error));
                }
            });

            selectAllCheckbox.addEventListener('change', function() {
                var checkboxes = tablaEstudiantes.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });
    </script>
</body>
</html>
