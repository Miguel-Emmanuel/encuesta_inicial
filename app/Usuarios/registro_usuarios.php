<?php
require '../Models/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido_paterno = $conexion->real_escape_string($_POST['apellido_paterno']);
$apellido_materno = $conexion->real_escape_string($_POST['apellido_materno']);
$matricula = $conexion->real_escape_string($_POST['matricula']);
$carrera = $conexion->real_escape_string($_POST['carrera']);
$email = $conexion->real_escape_string($_POST['email']);
$pass = $conexion->real_escape_string($_POST['pass']);
$rol_id = $conexion->real_escape_string($_POST['rol_id']);

$PasswordHash = password_hash($pass, PASSWORD_BCRYPT); //Incriptando clave,
	//crea un nuevo hash de contraseña usando un algoritmo de hash fuerte de único sentido.

$sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, matricula, carrera, email, pass, rol_id) VALUES ('$nombre','$apellido_paterno','$apellido_materno','$matricula', '$carrera','$email','$PasswordHash','$rol_id')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/usuarios/index.php');

?>