<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido_p = $conexion->real_escape_string($_POST['apellido_p']);
$apellido_m = $conexion->real_escape_string($_POST['apellido_m']);
$clave_sp = $conexion->real_escape_string($_POST['clave_sp']);
$correo = $conexion->real_escape_string($_POST['correo']);
$telefono = $conexion->real_escape_string($_POST['telefono']);

$sql = "UPDATE tutores SET nombre = '$nombre', apellido_p = '$apellido_p',apellido_m = '$apellido_m',clave_sp = '$clave_sp',correo = '$correo',telefono = '$telefono' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/tutores/index.php');

?>