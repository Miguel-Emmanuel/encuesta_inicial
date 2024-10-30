<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);
$tutor_id  = $conexion->real_escape_string($_POST['tutor_id']);
$periodo_id = $conexion->real_escape_string($_POST['periodo_id']);


$sql = "UPDATE grupo_tutor SET  grupo_id  = '$grupo_id ',tutor_id   = '$tutor_id',periodo_id = '$periodo_id' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/grupo_tutor/index.php');

?>