<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$estudiante_id  = $conexion->real_escape_string($_POST['estudiante_id']);
$grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);
$periodo_id = $conexion->real_escape_string($_POST['periodo_id']);


$sql = "UPDATE estudiante_grupo SET estudiante_id  = '$estudiante_id', grupo_id  = '$grupo_id ',periodo_id = '$periodo_id' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/estudiante_grupo/index.php');

?>