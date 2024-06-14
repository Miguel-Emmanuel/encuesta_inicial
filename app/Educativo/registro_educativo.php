<?php
require '../Models/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);

$sql = "INSERT INTO programa_edu (nombre ) VALUES ('$nombre')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/educativo/index.php');

?>