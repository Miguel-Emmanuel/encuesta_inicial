<?php
require '../../../database/conexion.php';

$nombreig = $conexion->real_escape_string($_POST['nombreig']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);



$sql = "INSERT INTO i_genero (nombreig, descripcion) VALUES ('$nombreig','$descripcion')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/i_genero/index.php');

?>