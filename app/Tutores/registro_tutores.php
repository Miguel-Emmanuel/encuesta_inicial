<?php
require '../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido_p = $conexion->real_escape_string($_POST['apellido_p']);
$apellido_m = $conexion->real_escape_string($_POST['apellido_m']);
$clave_sp = $conexion->real_escape_string($_POST['clave_sp']);
$correo = $conexion->real_escape_string($_POST['correo']);
$telefono = $conexion->real_escape_string($_POST['telefono']);


$sql = "INSERT INTO tutores (nombre, apellido_p, apellido_m, clave_sp, correo, telefono) VALUES ('$nombre','$apellido_p','$apellido_m','$clave_sp', '$correo','$telefono')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/tutores/index.php');

?>