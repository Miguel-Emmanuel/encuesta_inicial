<?php
// Conectar a la base de datos
include("../../../database/conexion.php");

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener el grupo seleccionado desde el formulario (si lo hay)
$grupoSeleccionado = isset($_POST['grupo']) ? $_POST['grupo'] : '';

// Primero, obtener la lista de grupos vulnerables para el select
$grupos = [];
$sql_grupos = "SELECT DISTINCT grupo FROM clasificacion_estudiantes";
$resultado_grupos = $conexion->query($sql_grupos);
if ($resultado_grupos) {
    while ($grupo = $resultado_grupos->fetch_assoc()) {
        $grupos[] = $grupo['grupo'];
    }
}

// Consulta para obtener la lista de estudiantes con datos generales
$sql_estudiantes = "
    SELECT DISTINCT 
        e.matricula,
        ce.grupo AS grupo_vulnerable,
        CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
        ce.fecha_clasificacion,
        COALESCE(t_gr.nomenclatura, '') AS grupo_academico,
        COALESCE(CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno), '') AS tutor,
        COALESCE(p.alias, '') AS periodo_escolar,
        COALESCE(prog.nombre, '') AS carrera
    FROM clasificacion_estudiantes ce
    JOIN estudiantes e ON ce.estudiante_id = e.id
    JOIN usuarios u ON e.usuario_id = u.id
    LEFT JOIN estudiante_grupo eg ON eg.estudiante_id = e.id
    LEFT JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
    LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
    LEFT JOIN tutores t ON gt.tutor_id = t.id
    LEFT JOIN usuarios tu ON t.usuario_id = tu.id
    LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
    LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
";

// Filtrar por grupo si se ha seleccionado (y distinto de "todos")
if ($grupoSeleccionado != '' && $grupoSeleccionado != 'todos') {
    $sql_estudiantes .= " WHERE ce.grupo = '$grupoSeleccionado'";
}
$sql_estudiantes .= " ORDER BY ce.grupo, u.nombre";

// Ejecutar la consulta de estudiantes y agrupar por grupo_vulnerable
$resultado_estudiantes = $conexion->query($sql_estudiantes);
$estudiantes = [];
if ($resultado_estudiantes && $resultado_estudiantes->num_rows > 0) {
    while ($estudiante = $resultado_estudiantes->fetch_assoc()) {
        $estudiantes[$estudiante['grupo_vulnerable']][] = $estudiante;
    }
} else {
    echo "No se encontraron estudiantes en ese grupo.";
}
// Consulta de agrupación por regla 
$sql_agr_reglas = "SELECT ce.grupo AS grupo_vulnerable, r.descripcion, COUNT(*) AS total
    FROM clasificacion_estudiantes ce
    LEFT JOIN reglas_clasificacion r ON ce.pregunta_id = r.pregunta_id AND ce.grupo = r.grupo";
if ($grupoSeleccionado != '' && $grupoSeleccionado != 'todos') {
    $sql_agr_reglas .= " WHERE ce.grupo = '$grupoSeleccionado'";
}
$sql_agr_reglas .= " GROUP BY ce.grupo, r.descripcion";

$resultado_agr = $conexion->query($sql_agr_reglas);
$agrReglas = [];
if ($resultado_agr) {
    while ($row = $resultado_agr->fetch_assoc()) {
        $agrReglas[$row['grupo_vulnerable']][] = $row;
    }
}

// **NUEVO**: Calculamos la suma total de reglas cumplidas por cada grupo
$rulesTotals = [];
foreach ($agrReglas as $grupo => $rows) {
    $sum = 0;
    foreach ($rows as $r) {
        $sum += $r['total'];
    }
    $rulesTotals[$grupo] = $sum;
}

// Calcular totales de estudiantes por grupo (para los porcentajes en las gráficas)
$groupTotals = [];
foreach ($estudiantes as $grupo => $lista) {
    $groupTotals[$grupo] = count($lista);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clasificación de Estudiantes</title>
    <!-- Incluir Bootstrap CSS y Chart.js desde CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Usar Chart.js v2.9.4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <!-- Usar Chart.js DataLabels plugin compatible (v0.7.0) -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }

        h2 {
            margin-top: 40px;
        }

        /* Reducir tamaño de las gráficas al 50% */
        .chart-container  canvas {

            width: 1200px;
            height: 1200px;
            /* margin-left: 30%; */
        }

        /* Estilo para el buscador dinámico */
        #searchInput {
            margin-bottom: 20px;
        }
        #chart-overall{
            width:  1600px;
            height: 1600px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Clasificación de Estudiantes</h1>
        <!-- Formulario de filtro de grupo -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="grupo">Seleccionar Grupo Vulnerable:</label>
                <select name="grupo" id="grupo" class="form-control">
                    <option value="todos" <?php echo ($grupoSeleccionado == 'todos') ? 'selected' : ''; ?>>Todos</option>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?php echo $grupo; ?>" <?php echo ($grupoSeleccionado == $grupo) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($grupo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-left: 50%;">Ver Estudiantes</button>
        </form>
        <!-- Campo de búsqueda dinámico -->
        <br>
        <div class="mb-3" style="width: 50%; ; margin-left: 30%;">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar en las tablas...">
        </div>

        <!-- Listado de estudiantes por grupo -->
        <?php if (!empty($estudiantes)): ?>
            <?php foreach ($estudiantes as $grupo => $estudiantesGrupo): ?>
                <h2>Grupo: <?php echo htmlspecialchars($grupo); ?></h2>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Matrícula</th>
                            <th>Estudiante</th>
                            <th>Grupo Académico</th>
                            <th>Tutor</th>
                            <th>Carrera</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <?php
// Verificar si la función ya existe antes de declararla
if (!function_exists('safeValue')) {
    function safeValue($value)
    {
        return htmlspecialchars($value ?? 'No disponible', ENT_QUOTES, 'UTF-8');
    }
}
?>

<tbody>
    <?php foreach ($estudiantesGrupo as $est): ?>
        <tr>
            <td><?php echo safeValue($est['matricula']); ?></td>
            <td><?php echo safeValue($est['estudiante']); ?></td>
            <td><?php echo safeValue($est['grupo_academico']); ?></td>
            <td><?php echo safeValue($est['tutor']); ?></td>
            <td><?php echo safeValue($est['carrera']); ?></td>
            <td>
                <button class="btn btn-info ver-detalles" data-id="<?php echo safeValue($est['matricula']); ?>">
                    Ver Detalles
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

                </table>
                <!-- Gráfica de pastel para las reglas de clasificación de este grupo -->
                <div class="chart-container">
                    <canvas id="chart-<?php echo str_replace(' ', '_', $grupo); ?>"></canvas>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron estudiantes para el grupo seleccionado.</p>
        <?php endif; ?>

        <!-- Gráfica general de distribución de grupos vulnerables (solo si se selecciona "Todos") -->
        <?php if ($grupoSeleccionado == 'todos' || $grupoSeleccionado == ''): ?>
            <div class="mt-5">
                <h2>Distribución de Grupos Vulnerables</h2>
                <canvas id="chart-overall"></canvas>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de detalles (Bootstrap) -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="modalNombre"></span></p>
                    <!-- Sección de detalles de grupos vulnerables para el estudiante -->
                    <div id="detallesGrupo"></div>
                    <p><strong>Tutor:</strong> <span id="modalTutor"></span></p>
                    <p><strong>Grupo Académico:</strong> <span id="modalGrupoAcademico"></span></p>
                    <p><strong>Periodo Escolar:</strong> <span id="modalPeriodo"></span></p>
                    <p><strong>Carrera:</strong> <span id="modalCarrera"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery, Popper.js y Bootstrap JS desde CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gráfica de pastel para cada grupo vulnerable
            Chart.plugins.register(ChartDataLabels);
            // Búsqueda dinámica: filtrar filas de todas las tablas
            $('#searchInput').on('input', function() {
                const value = $(this).val().toLowerCase();
                // Filtrar todas las filas de los <tbody> de todas las tablas
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Evento para ver los detalles del estudiante
            document.querySelectorAll(".ver-detalles").forEach(button => {
                button.addEventListener("click", function() {
                    let matricula = this.getAttribute("data-id");
                    fetch("http://localhost:8001/public/views/Grupos_V/detalles_estudiante.php?matricula=" + matricula)
                        .then(response => response.json())
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                document.getElementById("modalNombre").textContent = data[0].estudiante;
                                document.getElementById("modalTutor").textContent = data[0].tutor;
                                document.getElementById("modalGrupoAcademico").textContent = data[0].grupo_academico;
                                document.getElementById("modalPeriodo").textContent = data[0].periodo;
                                document.getElementById("modalCarrera").textContent = data[0].carrera;
                                let detallesHTML = "";
                                data.forEach(detalle => {
                                    detallesHTML += `<div class="mb-3">
                                <p><strong>Grupo Vulnerable:</strong> ${detalle.grupo_vulnerable}</p>
                                <p><strong>Pregunta:</strong> ${detalle.pregunta}</p>
                                <p><strong>Respuesta:</strong> ${detalle.respuesta}</p>
                                <p><strong>Descripción:</strong> ${detalle.descripcion}</p>
                                <hr>
                            </div>`;
                                });
                                document.getElementById("detallesGrupo").innerHTML = detallesHTML;
                                $('#modalDetalles').modal('show');
                            } else {
                                alert("No se encontraron detalles para este estudiante.");
                            }
                        })
                        .catch(error => console.error("Error al obtener detalles:", error));
                });
            });

            // Crear las gráficas de pastel para cada grupo (por reglas de clasificación)
            var groupedRules = <?php echo json_encode($agrReglas); ?>;
            // **NUEVO**: obtén los totales de reglas
            var rulesTotals = <?php echo json_encode($rulesTotals); ?>;

            // (Si sigues usando groupTotals para otra gráfica, no lo borres)
            // var groupTotals = <?php // echo json_encode($groupTotals); 
                                    ?>;

            // Crear las gráficas por cada grupo
            for (var grupo in groupedRules) {
                var rules = groupedRules[grupo];
                var labels = [];
                var dataValues = [];

                // Tomamos la suma total de “activaciones” de reglas en este grupo
                var sumOfAllRules = rulesTotals[grupo] || 0;

                rules.forEach(function(rule) {
                    var desc = rule.descripcion.trim() !== "" ? rule.descripcion : "Sin Descripción";
                    labels.push(desc);

                    // Ahora calculamos el porcentaje de la regla respecto al total de reglas cumplidas
                    var porcentaje = 0;
                    if (sumOfAllRules > 0) {
                        porcentaje = (rule.total / sumOfAllRules) * 100;
                    }
                    dataValues.push(parseFloat(porcentaje.toFixed(2)));
                });

                // ... Crear el gráfico
                var canvasId = "chart-" + grupo.replace(/\s+/g, '_');
                var ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40'
                            ]
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                formatter: function(value) {
                                    return value + '%';
                                },
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Reglas (Descripción)'
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.labels[tooltipItem.index] || '';
                                    return label + ': ' + data.datasets[0].data[tooltipItem.index] + '%';
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Resto de tu código para la gráfica “TODOS”, etc.
            // Verificar si la gráfica general es visible
if (<?php echo $grupoSeleccionado == 'todos' || $grupoSeleccionado == '' ? 'true' : 'false'; ?>) {
    // Datos de distribución general (por grupos)
    var groupTotals = <?php echo json_encode($groupTotals); ?>;
    
    // Definir los labels (grupos) y los datos (totales de estudiantes por grupo)
    var labels = Object.keys(groupTotals);
    var dataValues = Object.values(groupTotals);

    // Crear la gráfica de distribución general (si se ha seleccionado "Todos")
    var ctx = document.getElementById('chart-overall').getContext('2d');
    new Chart(ctx, {
        type: 'pie',  // Puedes cambiar el tipo de gráfico si lo prefieres
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                    '#FF0000', '#00FF00', '#0000FF', '#FF7F50'
                ]
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: function(value) {
                        return value + ' estudiantes';
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            title: {
                display: true,
                text: 'Distribución General de Grupos Vulnerables'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        return label + ': ' + data.datasets[0].data[tooltipItem.index] + ' estudiantes';
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

        });
    </script>
</body>

</html>