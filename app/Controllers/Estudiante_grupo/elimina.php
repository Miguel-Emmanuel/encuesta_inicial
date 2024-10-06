<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "DELETE FROM estudiante_grupo  WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/estudiante_grupo/index.php');

?>