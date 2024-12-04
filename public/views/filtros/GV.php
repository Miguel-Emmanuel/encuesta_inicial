<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Respuestas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-4">
        <h2 class="text-center">GRUPOS VULNERABLES</h2>
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <form id="searchForm">
                    <div class="mb-4">
                        <select id="grupoVulnerable" name="grupoVulnerable" class="form-select" required>
                            <option value="" disabled selected>Selecciona un grupo</option>
                            <option value="paternal">Paternal</option>
                            <option value="economico">Económico</option>
                            <option value="salud">Salud</option>
                            <option value="baja">Deserción Académica</option>
                            <option value="etnia">Población Indígena Linguística</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                </div>
                <table class="table table-striped" id="resultTable">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Grupo Vulnerable</th>
                            <th>Acciones</th> <!-- Nueva columna para el botón -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se cargarán los resultados -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalles -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Detalles del Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <p><strong>Matrícula:</strong> <span id="modalMatricula"></span></p>
<p><strong>Nombre Completo:</strong> <span id="modalNombre"></span></p>
<p><strong>Email:</strong> <span id="modalEmail"></span></p>
<p><strong>Grupo Vulnerable:</strong> <span id="modalGrupo"></span></p>
<p><strong>Grupo:</strong> <span id="modalGrupoAsignado"></span></p> <!-- Grupo -->
<p><strong>Tutor:</strong> <span id="modalTutor"></span></p> <!-- Tutor -->
<p><strong>Carrera:</strong> <span id="modalCarrera"></span></p> <!-- Carrera -->
<p><strong>Periodo Escolar:</strong> <span id="modalPeriodo"></span></p> <!-- Periodo Escolar -->
<p><strong>Pregunta:</strong> <span id="modalPregunta"></span></p>
<p><strong>Respuesta:</strong> <span id="modalRespuesta"></span></p>
<p><strong>Observaciones:</strong> <span id="modalObservaciones"></span></p>
<p><strong>Fecha de Respuesta:</strong> <span id="modalFecha"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
$(document).ready(function () {
    // Manejar el envío del formulario
    $('#searchForm').on('submit', function (e) {
        e.preventDefault();

        const grupoVulnerable = $('#grupoVulnerable').val();

        if (!grupoVulnerable) {
            alert('Por favor selecciona un grupo vulnerable.');
            return;
        }
        jQuery.noConflict();
        $.ajax({
            url: '../../../app/Controllers/GruposV/buscar_respuestas.php',
            type: 'POST',
            data: { grupo_vulnerable: grupoVulnerable },
            dataType: 'json',
            success: function (data) {
    console.log(data); // Inspecciona la respuesta
    const tbody = $('#resultTable tbody');
    tbody.empty();

    if (data.length > 0) {
        data.forEach(function (respuesta) {
            tbody.append(`
                <tr>
                    <td>${respuesta.matricula}</td>
                    <td>${respuesta.nombre_completo}</td>
                    <td>${respuesta.email}</td>
                    <td>${respuesta.grupo_vulnerable}</td>
                    <td>
                      <button class="btn btn-info btn-sm view-details"
    data-matricula="${respuesta.matricula}"
    data-nombre="${respuesta.nombre_completo}" 
    data-email="${respuesta.email}" 
    data-grupovulnerable="${respuesta.grupo_vulnerable}" 
    data-grupoasignado="${respuesta.grupo}" 
    data-tutor="${respuesta.tutor}" 
    data-carrera="${respuesta.carrera}" 
    data-periodo="${respuesta.periodo_escolar}" 
    data-pregunta="${respuesta.pregunta}" 
    data-respuesta="${respuesta.respuesta}" 
    data-observaciones="${respuesta.observaciones}" 
    data-fecha="${respuesta.created_at}">
    Ver Detalles
</button>

                    </td>
                </tr>
            `);
        });
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">No se encontraron resultados</td></tr>');
    }
},
  error: function (xhr, status, error) {
                alert('Error al buscar respuestas: ' + error + '. Detalles: ' + xhr.responseText);
            }
        });
    });

    // Manejar clic en "Ver Detalles" con delegación de eventos
    $('#resultTable').on('click', '.view-details', function () {
        $('#resultTable').on('click', '.view-details', function () {
            $('#modalMatricula').text($(this).data('matricula'));
    $('#modalNombre').text($(this).data('nombre'));
    $('#modalEmail').text($(this).data('email'));
    $('#modalGrupo').text($(this).data('grupovulnerable'));
    $('#modalGrupoAsignado').text($(this).data('grupoasignado')); 
    $('#modalTutor').text($(this).data('tutor'));
    $('#modalCarrera').text($(this).data('carrera'));
    $('#modalPeriodo').text($(this).data('periodo'));
    $('#modalPregunta').text($(this).data('pregunta'));
    $('#modalRespuesta').text($(this).data('respuesta'));
    $('#modalObservaciones').text($(this).data('observaciones'));
    $('#modalFecha').text($(this).data('fecha'));

    
            jQuery.noConflict();
    jQuery(document).ready(function ($) {
        // Usa $ de forma explícita dentro de esta función
        $('#detailsModal').modal('show');
    });
});

        // Mostrar el modal

      
    });

    // Filtrar tabla por búsqueda
    $('#searchInput').on('input', function () {
        const value = $(this).val().toLowerCase();
        $('#resultTable tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
    </script>
</body>
</html>
