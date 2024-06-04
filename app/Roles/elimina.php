<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "DELETE FROM roles  WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/roles/index.php');

?>