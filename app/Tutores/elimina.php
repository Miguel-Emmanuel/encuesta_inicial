<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "DELETE FROM tutores  WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/tutores/index.php');

?>