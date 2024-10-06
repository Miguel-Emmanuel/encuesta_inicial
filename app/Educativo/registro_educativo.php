<?php
require '../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$clave = $conexion->real_escape_string($_POST['clave']);
$grado = $conexion->real_escape_string($_POST['grado']);

$sql = "INSERT INTO programa_edu ( grado, nombre, clave ) VALUES ('$grado', '$nombre', '$clave')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/educativo/index.php');

?>