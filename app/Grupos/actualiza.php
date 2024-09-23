<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$programa_e = $conexion->real_escape_string($_POST['programa_e']);
$nomenclatura = $conexion->real_escape_string($_POST['nomenclatura']);
$tutor = $conexion->real_escape_string($_POST['tutor']);
$periodo_e = $conexion->real_escape_string($_POST['periodo_e']);


$sql = "UPDATE t_grupos SET nombre = '$nombre', programa_e = '$programa_e',nomenclatura = '$nomenclatura',tutor = '$tutor',periodo_e = '$periodo_e' WHERE id = $id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/grupos/index.php');

?>