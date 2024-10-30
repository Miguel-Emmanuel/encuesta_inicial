<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "DELETE FROM i_genero  WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/i_genero/index.php');

?>