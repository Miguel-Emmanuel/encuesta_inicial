<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido_paterno = $conexion->real_escape_string($_POST['apellido_paterno']);
$apellido_materno = $conexion->real_escape_string($_POST['apellido_materno']);
$email = $conexion->real_escape_string($_POST['email']);
$pass = $conexion->real_escape_string($_POST['pass']);
$rol_id = $conexion->real_escape_string($_POST['rol_id']);

$PasswordHash = password_hash($pass, PASSWORD_BCRYPT); //Incriptando clave,
//crea un nuevo hash de contraseña usando un algoritmo de hash fuerte de único sentido.

$sql = "UPDATE usuarios SET nombre = '$nombre', apellido_paterno = '$apellido_paterno',apellido_materno = '$apellido_materno',email = '$email',pass = '$PasswordHash', rol_id = '$rol_id' WHERE id=$id";
if ($conexion->query($sql)) {
	if ($rol_id == 2) {
		$clave_sp = $conexion->real_escape_string($_POST['clave_sp']);
		$telefono = $conexion->real_escape_string($_POST['telefono']);

		$sql_tutor = "UPDATE tutores SET clave_sp = '$clave_sp', telefono = '$telefono' WHERE usuario_id = $id";
		$conexion->query($sql_tutor);
	}
}

header('Location: /public/views/usuarios/index.php');
