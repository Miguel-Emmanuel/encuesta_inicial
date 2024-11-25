<?php
require("../../../app/Controllers/Estudiante_grupo/obtener_estudiante.php");
$sqlGrupos = "SELECT * FROM t_grupos";
$grupos = $conexion->query($sqlGrupos);
?>

<style>
    th.sel {
        width: 5%;
        font-size: smaller;
    }

    /* Ocultar columna por defecto */
    .hidden-column {
        display: none;
    }

    .check {
        width: 1.5rem;
        /* Aumenta el tamaño del checkbox */
        height: 1.5rem;
        border-radius: 0.25rem;
        /* Bordes redondeados */
        border: 2px solid #198754;
    }

    /* Efecto cuando está seleccionado */
    .check:checked {
        background-color: #198754;
        /* Color de fondo verde al seleccionar */
        border-color: #145a32;
        /* Cambia el color del borde */
        box-shadow: 0 0 5px #145a32;
        /* Añade un efecto de brillo */
    }
</style>

<!-- Modal Cambio Grupo -->
<div class="modal fade" id="cambioGrupoModal" tabindex="-1" aria-labelledby="cambioGrupoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Clase modal-lg para un modal grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cambioGrupoModalLabel">Cambio de Grupo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario dentro del modal -->
                <form action="enviar_estudiantes.php" method="POST">
                    <div class="mb-3">
                        <label for="grupo" class="form-label">Seleccionar Grupo</label>
                        <select class="form-select" id="grupo" name="grupo" required>
                            <option value="" selected disabled>Seleccione un grupo</option>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nomenclatura']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="sel hidden-column">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nuevo_grupo" class="form-label">Mover al Grupo:</label>
                            <select class="form-select" id="nuevo_grupo" name="nuevo_grupo" required>
                                <option value="" selected disabled>Seleccione un grupo</option>
                                <?php foreach ($grupos as $grupo): ?>
                                    <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nomenclatura']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="periodo" class="form-label">Periodo Educativo:</label>
                            <select class="form-select" id="periodo" name="periodo" required>
                                <option value="" selected disabled>Seleccione un periodo</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?php echo $periodo['id']; ?>"><?php echo $periodo['alias']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var grupoSelect = document.getElementById('grupo');
        if (grupoSelect) {
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
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            var tablaEstudiantes = document.getElementById('tabla-estudiantes');
                            tablaEstudiantes.innerHTML = ''; // Limpia la tabla antes de agregar nuevos datos

                            data.forEach(estudiante => {
                                var row = document.createElement('tr');

                                var cellSelect = document.createElement('td');
                                var checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'estudiantes[]';
                                checkbox.value = estudiante.estudiante_id;
                                checkbox.classList.add('check'); // Agrega la clase personalizada aquí
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

                            // Seleccionar/Deseleccionar todos los checkboxes
                            var selectAllCheckbox = document.getElementById('selectAll');
                            selectAllCheckbox.addEventListener('change', function() {
                                var allCheckboxes = tablaEstudiantes.querySelectorAll('input[type="checkbox"]');
                                allCheckboxes.forEach(function(checkbox) {
                                    checkbox.checked = selectAllCheckbox.checked;
                                });
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var grupoSelect = document.getElementById('grupo');
        var tablaEstudiantes = document.getElementById('tabla-estudiantes');
        var selectAllHeader = document.querySelector('th.sel'); // Selecciona el encabezado "Seleccionar Todo"

        // Oculta la columna inicialmente
        selectAllHeader.classList.add('hidden-column');

        grupoSelect.addEventListener('change', function() {
            var grupoId = this.value;

            // Si se selecciona un grupo, muestra la columna
            if (grupoId) {
                selectAllHeader.classList.remove('hidden-column');

                // Muestra también las celdas dinámicamente generadas
                var filas = tablaEstudiantes.querySelectorAll('tr');
                filas.forEach(fila => {
                    var celdaSeleccion = fila.querySelector('td:first-child');
                    if (celdaSeleccion) {
                        celdaSeleccion.classList.remove('hidden-column');
                    }
                });
            } else {
                // Si no hay grupo seleccionado, oculta la columna
                selectAllHeader.classList.add('hidden-column');
            }
        });
    });
</script>