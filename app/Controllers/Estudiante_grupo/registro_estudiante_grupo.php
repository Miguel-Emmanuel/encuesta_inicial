<?php
require '../../../database/conexion.php';

$estudiante_id  = $conexion->real_escape_string($_POST['estudiante_id']);
$grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);
$periodo_id = $conexion->real_escape_string($_POST['periodo_id']);


$sql = "INSERT INTO estudiante_grupo (estudiante_id , grupo_id , periodo_id) VALUES ('$estudiante_id ','$grupo_id ','$periodo_id')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/estudiante_grupo/index.php');

?>