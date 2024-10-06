<?php
require '../../database/conexion.php';

$inicio = $conexion->real_escape_string($_POST['inicio']);
$fin = $conexion->real_escape_string($_POST['fin']);

$sql = "INSERT INTO periodos_escolar (inicio, fin) VALUES ('$inicio','$fin')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/periodos/index.php');

?>