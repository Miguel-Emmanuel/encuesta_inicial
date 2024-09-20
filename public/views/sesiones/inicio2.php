<?php
require '../../../app/Models/conexion.php';

$sqlCarreras = "SELECT id, nombre FROM programa_edu";
$carreras = $conexion->query($sqlCarreras);

$sqlRespuestas = "SELECT id, usuario_id, pregunta_id, seccion_id, respuesta_texto
FROM usuario_respuesta
WHERE pregunta_id = 8 OR pregunta_id = 2 OR pregunta_id = 15
ORDER BY usuario_id";
$respuestas = $conexion->query($sqlRespuestas);

$data = array(); // Array para almacenar los resultados

// Procesar los resultados de la consulta
if ($respuestas->num_rows > 0) {
    while ($fila = $respuestas->fetch_assoc()) {
        $data[] = $fila;
    }
}

?>

<div class="container row">
    <div class="col-12">
        <h3>Carreras</h3>
    </div>
    <div class="col-6">
        <select class="form-select" name="carreras">
            <option value="0">Seleccione una carrera</option>
            <?php while ($row_carreras = $carreras->fetch_assoc()) { ?>
                <option value="<?= $row_carreras['id'] ?>" onclick="muestreo(this.value)"><?= $row_carreras['nombre'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-12 p-4" id="resultadosCarrera"></div>
    <div class="col-6">
        <label for="rangoEdades" class="form-label">Edad</label>
        <input type="range" class="form-range" min="0" max="5" step="0.5" id="rangoEdades" value="">
        <div id="edad" class="form-text">Aquí colocamos la edad</div>
    </div>
</div>

<script>
    const resultadosSQL = <?php echo json_encode($data); ?>

    function muestreo(value) {
        // Variables
        container = document.querySelector('#resultadosCarrera');
        barraEdades = document.querySelector('#rangoEdades');
        divEdad = document.querySelector('#edad');
        var cantidadFemenino = 0;
        var cantidadMasculino = 0;
        var usuariosEdades = [];

        // Filtrar usuarios que son de la carrera seleccionada
        const usuariosCarrera = resultadosSQL.filter(carrera => carrera.respuesta_texto === value);
        var cantidadTotal = usuariosCarrera.length;

        //  Actualización de la cantidad máxima de la barra
        barraEdades.max = cantidadTotal - 1;
        barraEdades.step = 1;

        //  Conteo de acuerdo a la carrera (Femenino)
        Object.values(resultadosSQL).forEach(resultado => {

            Object.values(usuariosCarrera).forEach(usuario => {
                if (resultado.usuario_id == usuario.usuario_id && resultado.pregunta_id == 8 && resultado.respuesta_texto === 'Femenino') {
                    cantidadFemenino++;
                }
            });

        });

        //  Conteo de acuerdo a la carrera (Masculino)
        Object.values(resultadosSQL).forEach(resultado => {

            Object.values(usuariosCarrera).forEach(usuario => {
                if (resultado.usuario_id == usuario.usuario_id && resultado.pregunta_id == 8 && resultado.respuesta_texto === 'Masculino') {
                    cantidadMasculino++;
                }
            });

        });

        //  Filtrado de edades de los usuarios
        Object.values(resultadosSQL).forEach(resultado => {

            Object.values(usuariosCarrera).forEach(usuario => {
                if (resultado.usuario_id == usuario.usuario_id && resultado.pregunta_id == 15) {
                    usuariosEdades = [...usuariosEdades, resultado.respuesta_texto];
                    usuariosEdades.sort();
                }
            });

        });

        //  Impresión de resultados
        container.innerHTML = ` <p> <strong>Total:</strong> ${cantidadTotal} </p> <hr> <p> <strong>Hombres:</strong> ${cantidadMasculino} </p> <hr> <p> <strong>Mujeres:</strong> ${cantidadFemenino} </p>`;
        barraEdades.addEventListener('input', event => {
            divEdad.textContent = usuariosEdades[event.target.value];
        });

        // console.log(usuariosEdades);
        // console.log(Object.values(usuariosCarrera));
    }
</script>