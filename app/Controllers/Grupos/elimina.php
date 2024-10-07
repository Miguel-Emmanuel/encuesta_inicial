<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "DELETE FROM t_grupos  WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/grupos/index.php');

?>