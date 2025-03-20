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

// Consulta para obtener la lista de estudiantes con datos generales (incluye e.genero)
$sql_estudiantes = "
    SELECT DISTINCT 
        e.matricula,
        e.genero,
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

// Ejecutar la consulta y agrupar por grupo_vulnerable
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

// Calculamos la suma total de reglas cumplidas por cada grupo
$rulesTotals = [];
foreach ($agrReglas as $grupo => $rows) {
    $sum = 0;
    foreach ($rows as $r) {
        $sum += $r['total'];
    }
    $rulesTotals[$grupo] = $sum;
}

// Consulta para obtener el desglose de género por cada regla (1 = Hombres, 2 = Mujeres)
$sql_gender = "SELECT ce.grupo AS grupo_vulnerable, r.descripcion, e.genero, COUNT(*) AS total_gender
    FROM clasificacion_estudiantes ce
    JOIN estudiantes e ON ce.estudiante_id = e.id
    LEFT JOIN reglas_clasificacion r ON ce.pregunta_id = r.pregunta_id AND ce.grupo = r.grupo";
if ($grupoSeleccionado != '' && $grupoSeleccionado != 'todos') {
    $sql_gender .= " WHERE ce.grupo = '$grupoSeleccionado'";
}
$sql_gender .= " GROUP BY ce.grupo, r.descripcion, e.genero";

$resultado_gender = $conexion->query($sql_gender);
$agrReglasByGender = [];
if ($resultado_gender) {
    while ($row = $resultado_gender->fetch_assoc()) {
        $grupo = $row['grupo_vulnerable'];
        $desc = trim($row['descripcion']) !== "" ? $row['descripcion'] : "Sin Descripción";
        // Convertir 1 a "Hombres" y 2 a "Mujeres"
        $genero = ($row['genero'] == 1) ? 'Hombres' : 'Mujeres';
        if (!isset($agrReglasByGender[$grupo][$desc])) {
            $agrReglasByGender[$grupo][$desc] = ['Hombres' => 0, 'Mujeres' => 0];
            // var_dump($agrReglasByGender);
        }
        $agrReglasByGender[$grupo][$desc][$genero] = (int)$row['total_gender'];
    }
}

// Calcular totales de estudiantes por grupo (para la gráfica global)
$groupTotals = [];
foreach ($estudiantes as $grupo => $lista) {
    $groupTotals[$grupo] = count($lista);
}

// Calcular el desglose global de género por grupo usando la consulta de estudiantes
$globalGenderData = [];
foreach ($estudiantes as $grupo => $lista) {
    $globalGenderData[$grupo] = ['Hombres' => 0, 'Mujeres' => 0];
    foreach ($lista as $estudiante) {
        if ($estudiante['genero'] == 1) {
            $globalGenderData[$grupo]['Hombres']++;
        } elseif ($estudiante['genero'] == 2) {
            $globalGenderData[$grupo]['Mujeres']++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clasificación de Estudiantes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <!-- Chart.js DataLabels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        h2 {
            margin-top: 40px;
        }
        .chart-container canvas {
            width: 600px;
            height: 600px;
        }
        #chart-overall {
            width: 800px;
            height: 800px;
        }
        /* Paginación con Bootstrap */
        .pagination-container {
            margin: 15px 0;
        }
        /* Ajuste para que la búsqueda no empuje todo */
        #searchInput {
            max-width: 400px;
            margin: 0 auto 20px auto;
            display: block;
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
    <br>
    <!-- Campo de búsqueda -->
    <input type="text" id="searchInput" class="form-control" placeholder="Buscar en las tablas...">

    <!-- Listado de estudiantes por grupo -->
    <?php if (!empty($estudiantes)): ?>
        <?php foreach ($estudiantes as $grupo => $estudiantesGrupo): ?>
            <h2>Grupo: <?php echo htmlspecialchars($grupo); ?></h2>
            <table class="table table-bordered table-hover paginated">
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
                if (!function_exists('safeValue')) {
                    function safeValue($value) {
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
            <!-- Contenedor de la gráfica para este grupo -->
            <div class="chart-container mb-5">
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

<!-- jQuery, Popper.js, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // ---- BÚSQUEDA DINÁMICA ----
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    // ---- DETALLES DEL ESTUDIANTE (MODAL) ----
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

    // ---- VARIABLES PARA LAS GRÁFICAS ----
    var groupedRules = <?php echo json_encode($agrReglas); ?>;
    var rulesTotals = <?php echo json_encode($rulesTotals); ?>;
    var genderData = <?php echo json_encode($agrReglasByGender); ?>;
    <?php if ($grupoSeleccionado == 'todos' || $grupoSeleccionado == ''): ?>
    var groupTotals = <?php echo json_encode($groupTotals); ?>;
    var globalGenderData = <?php echo json_encode($globalGenderData); ?>;
    <?php endif; ?>

    // ---- GRÁFICAS POR GRUPO ----
    Chart.plugins.register(ChartDataLabels);
    for (var grupo in groupedRules) {
        var rules = groupedRules[grupo];
        var labels = [];
        var dataValues = [];
        var sumOfAllRules = rulesTotals[grupo] || 0;

        rules.forEach(function(rule) {
            var desc = rule.descripcion.trim() !== "" ? rule.descripcion : "Sin Descripción";
            labels.push(desc);
            var porcentaje = (sumOfAllRules > 0) ? (rule.total / sumOfAllRules) * 100 : 0;
            dataValues.push(parseFloat(porcentaje.toFixed(2)));
        });

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
                        font: { weight: 'bold' }
                    }
                },
                title: {
                    display: true,
                    text: 'Distribución de Reglas (Descripción)'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var ruleLabel = data.labels[tooltipItem.index] || '';
                            var percentage = data.datasets[0].data[tooltipItem.index];
                            var genderInfo = (genderData[grupo] && genderData[grupo][ruleLabel]) ? genderData[grupo][ruleLabel] : {Hombres: 0, Mujeres: 0};
                            var total = genderInfo.Hombres + genderInfo.Mujeres;
                            var malePerc = total > 0 ? ((genderInfo.Hombres / total) * 100).toFixed(1) : 0;
                            var femalePerc = total > 0 ? ((genderInfo.Mujeres / total) * 100).toFixed(1) : 0;
                            return ruleLabel + ': ' + percentage + '%' +
                                   "\nHombres: " + genderInfo.Hombres + " (" + malePerc + "%)" +
                                   "\nMujeres: " + genderInfo.Mujeres + " (" + femalePerc + "%)" +
                                   "\nTotal: " + total;
                        }
                    }
                },
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        var element = elements[0];
                        var index = element._index;
                        var ruleLabel = this.data.labels[index];
                        var percentage = this.data.datasets[0].data[index];
                        var genderInfo = (genderData[grupo] && genderData[grupo][ruleLabel]) ? genderData[grupo][ruleLabel] : {Hombres: 0, Mujeres: 0};
                        var total = genderInfo.Hombres + genderInfo.Mujeres;
                        var malePerc = total > 0 ? ((genderInfo.Hombres / total) * 100).toFixed(1) : 0;
                        var femalePerc = total > 0 ? ((genderInfo.Mujeres / total) * 100).toFixed(1) : 0;
                        alert("Descripción: " + ruleLabel +
                              "\nPorcentaje: " + percentage + "%" +
                              "\nHombres: " + genderInfo.Hombres + " (" + malePerc + "%)" +
                              "\nMujeres: " + genderInfo.Mujeres + " (" + femalePerc + "%)" +
                              "\nTotal: " + total);
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // ---- GRÁFICA GLOBAL (TODOS) ----
    <?php if ($grupoSeleccionado == 'todos' || $grupoSeleccionado == ''): ?>
    var labelsGlobal = Object.keys(groupTotals);
    var dataValuesGlobal = Object.values(groupTotals);
    var ctxOverall = document.getElementById('chart-overall').getContext('2d');
    new Chart(ctxOverall, {
        type: 'pie',
        data: {
            labels: labelsGlobal,
            datasets: [{
                data: dataValuesGlobal,
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
                    font: { weight: 'bold' }
                }
            },
            title: {
                display: true,
                text: 'Distribución General de Grupos Vulnerables'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var groupLabel = data.labels[tooltipItem.index] || '';
                        var count = data.datasets[0].data[tooltipItem.index];
                        var genderInfo = (globalGenderData[groupLabel]) ? globalGenderData[groupLabel] : {Hombres: 0, Mujeres: 0};
                        var total = genderInfo.Hombres + genderInfo.Mujeres;
                        var malePerc = total > 0 ? ((genderInfo.Hombres / total) * 100).toFixed(1) : 0;
                        var femalePerc = total > 0 ? ((genderInfo.Mujeres / total) * 100).toFixed(1) : 0;
                        return groupLabel + ': ' + count + ' estudiantes' +
                               "\nHombres: " + genderInfo.Hombres + " (" + malePerc + "%)" +
                               "\nMujeres: " + genderInfo.Mujeres + " (" + femalePerc + "%)" +
                               "\nTotal: " + total;
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    var element = elements[0];
                    var index = element._index;
                    var groupLabel = this.data.labels[index];
                    var count = this.data.datasets[0].data[index];
                    var genderInfo = (globalGenderData[groupLabel]) ? globalGenderData[groupLabel] : {Hombres: 0, Mujeres: 0};
                    var total = genderInfo.Hombres + genderInfo.Mujeres;
                    var malePerc = total > 0 ? ((genderInfo.Hombres / total) * 100).toFixed(1) : 0;
                    var femalePerc = total > 0 ? ((genderInfo.Mujeres / total) * 100).toFixed(1) : 0;
                    alert("Grupo: " + groupLabel +
                          "\nTotal: " + count + " estudiantes" +
                          "\nHombres: " + genderInfo.Hombres + " (" + malePerc + "%)" +
                          "\nMujeres: " + genderInfo.Mujeres + " (" + femalePerc + "%)" +
                          "\nTotal: " + total);
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
    <?php endif; ?>

    // ---- PAGINACIÓN CON FLECHAS LATERALES ----
    var tables = document.querySelectorAll("table.paginated");
    tables.forEach(function(table) {
        var tbody = table.querySelector("tbody");
        if (!tbody) return;

        var rows = Array.from(tbody.querySelectorAll("tr"));
        var rowsPerPage = 10; // Ajusta cuántas filas se muestran por página
        var totalRows = rows.length;
        var totalPages = Math.ceil(totalRows / rowsPerPage);
        var currentPage = 1;

        // Contenedor para la paginación
        var paginationDiv = document.createElement("div");
        paginationDiv.className = "pagination-container";

        // Crear lista de paginación (Bootstrap)
        var paginationUl = document.createElement("ul");
        paginationUl.classList.add("pagination", "justify-content-center");

        // Botón "Anterior"
        var prevLi = document.createElement("li");
        prevLi.classList.add("page-item");
        var prevLink = document.createElement("a");
        prevLink.classList.add("page-link");
        prevLink.href = "#";
        prevLink.innerHTML = "&laquo;"; // Flecha
        prevLink.addEventListener("click", function(e) {
            e.preventDefault();
            showPage(currentPage - 1);
        });
        prevLi.appendChild(prevLink);
        paginationUl.appendChild(prevLi);

        // Crear botones de página
        for (var i = 1; i <= totalPages; i++) {
            (function(page) {
                var li = document.createElement("li");
                li.classList.add("page-item");
                var a = document.createElement("a");
                a.classList.add("page-link");
                a.href = "#";
                a.textContent = page;
                a.addEventListener("click", function(e) {
                    e.preventDefault();
                    showPage(page);
                });
                li.appendChild(a);
                paginationUl.appendChild(li);
            })(i);
        }

        // Botón "Siguiente"
        var nextLi = document.createElement("li");
        nextLi.classList.add("page-item");
        var nextLink = document.createElement("a");
        nextLink.classList.add("page-link");
        nextLink.href = "#";
        nextLink.innerHTML = "&raquo;"; // Flecha
        nextLink.addEventListener("click", function(e) {
            e.preventDefault();
            showPage(currentPage + 1);
        });
        nextLi.appendChild(nextLink);
        paginationUl.appendChild(nextLi);

        paginationDiv.appendChild(paginationUl);
        // Insertar la paginación debajo de la tabla
        table.parentNode.insertBefore(paginationDiv, table.nextSibling);

        // Función para mostrar una página concreta
        function showPage(page) {
            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;

            // Ocultar/mostrar filas
            rows.forEach(function(row, index) {
                row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
            });

            // Quitar "active" de todos los botones
            paginationUl.querySelectorAll("li.page-item").forEach(function(li, idx) {
                li.classList.remove("active");
            });

            // Marcar activo el botón de la página actual (se omite los botones "prev" y "next")
            var pageButtons = paginationUl.querySelectorAll("li.page-item");
            if (pageButtons[currentPage]) {
                pageButtons[currentPage].classList.add("active");
            }
        }

        // Mostrar la primera página
        showPage(1);
    });
});
</script>
</body>
</html>
