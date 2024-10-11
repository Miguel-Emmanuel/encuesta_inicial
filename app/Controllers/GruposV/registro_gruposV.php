<?php
require '../../../database/conexion.php';

$nombregv = $conexion->real_escape_string($_POST['nombregv']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);



$sql = "INSERT INTO gruposv (nombregv, descripcion) VALUES ('$nombregv','$descripcion')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/gruposV/index.php');

?>