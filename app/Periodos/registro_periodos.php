<?php
require '../../database/conexion.php';

$inicio = $conexion->real_escape_string($_POST['inicio']);
$fin = $conexion->real_escape_string($_POST['fin']);

setlocale(LC_TIME, 'es_ES.UTF-8');

// Convertir a formato de fecha
$fechaInicio = date_create($inicio);
$fechaFin = date_create($fin);

// Obtener mes y año
$mesInicio = strftime('%B', $fechaInicio->getTimestamp());
$mesFin = strftime('%B', $fechaFin->getTimestamp());
$anio = date_format($fechaFin, 'Y');

// Concatenar el alias en el formato deseado
$alias = $mesInicio . ' ' . $mesFin . ' ' . $anio;


$sql = "INSERT INTO periodos_escolar (alias, inicio, fin) VALUES ('$alias','$inicio','$fin')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/periodos/index.php');

?>