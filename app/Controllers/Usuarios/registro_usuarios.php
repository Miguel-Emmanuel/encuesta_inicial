<?php
require '../../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$apellido_paterno = $conexion->real_escape_string($_POST['apellido_paterno']);
$apellido_materno = $conexion->real_escape_string($_POST['apellido_materno']);
$email = $conexion->real_escape_string($_POST['email']);
$pass = $conexion->real_escape_string($_POST['pass']);
$rol_id = $conexion->real_escape_string($_POST['rol_id']);

$PasswordHash = password_hash($pass, PASSWORD_BCRYPT); //Incriptando clave,
	//crea un nuevo hash de contraseña usando un algoritmo de hash fuerte de único sentido.

$sql = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, email, pass, rol_id) VALUES ('$nombre','$apellido_paterno','$apellido_materno','$email','$PasswordHash','$rol_id')";

if($conexion->query($sql)){
    $usuario_id = $conexion->insert_id;

    if($rol_id == 3){
        $matricula = $conexion->real_escape_string($_POST['matricula']);
        $telefono = $conexion->real_escape_string($_POST['telefono']);
        $grupos_v = $conexion->real_escape_string($_POST['grupos_v']);
        $genero = $conexion->real_escape_string($_POST['genero']);
        $i_genero = $conexion->real_escape_string($_POST['i_genero']);

        $sql_estudiantes = "INSERT INTO estudiantes (usuario_id, matricula, telefono, grupos_v, genero, i_genero) VALUES ('$usuario_id', '$matricula', '$telefono', '$grupos_v', '$genero', '$i_genero')";
        if ($conexion->query($sql_estudiantes)) {
            echo "Registro de estudiante guardado exitosamente.";
        } else {
            echo "Error al guardar en la tabla estudiantes: " . $conexion->error;
        }
    }elseif ($rol_id == 2){
        $clave_sp = $conexion->real_escape_string($_POST['clave_sp']);
        $telefono = $conexion->real_escape_string($_POST['telefono']);

        $sql_tutor = "INSERT INTO tutores (usuario_id, clave_sp, telefono) VALUES ('$usuario_id', '$clave_sp' ,'$telefono')";
        if ($conexion->query($sql_tutor)) {
            echo "Registro de estudiante guardado exitosamente.";
        } else {
            echo "Error al guardar en la tabla estudiantes: " . $conexion->error;
        }
    }
}

header('Location: /public/views/usuarios/index.php');

?>