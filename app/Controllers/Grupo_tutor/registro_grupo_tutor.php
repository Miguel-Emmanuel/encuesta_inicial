<?php
require '../../../database/conexion.php';


$grupo_id  = $conexion->real_escape_string($_POST['grupo_id']);
$tutor_id  = $conexion->real_escape_string($_POST['tutor_id']);
$periodo_id = $conexion->real_escape_string($_POST['periodo_id']);


$sql = "INSERT INTO grupo_tutor (grupo_id ,tutor_id, periodo_id) VALUES ('$grupo_id ','$tutor_id','$periodo_id')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/grupo_tutor/index.php');

?>