<?php
include("../../../database/conexion.php");

if ($_POST['accion'] == 'estados') {
    $pais_id = intval($_POST['pais_id']);
    $pais_id = intval($_POST['pais_id']);
    $result = $conexion->query("SELECT * FROM estados WHERE pais = $pais_id");

    echo "<option value=''>Selecciona un estado</option>";
    while ($estado = $result->fetch_assoc()) {
        echo "<option value='{$estado['id']},{$estado['nombre']}'>{$estado['nombre']}</option>";
    }
}

if ($_POST['accion'] == 'municipios') {
    $estado_id = intval($_POST['estado_id']);
    $estado_id = intval($_POST['estado_id']);
    $result = $conexion->query("SELECT * FROM municipios WHERE estado = $estado_id");

    echo "<option value=''>Selecciona un municipio</option>";
    while ($municipio = $result->fetch_assoc()) {
        echo "<option value='{$municipio['id']},{$municipio['nombre']}'>{$municipio['nombre']}</option>";
    }
}
?>
