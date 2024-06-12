<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, nombre, apellido_paterno, apellido_materno, matricula, carrera, email, pass, rol_id From usuarios WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$users = [];

if($rows > 0){
    $users = $resultado->fetch_array();
}

echo json_encode($users, JSON_UNESCAPED_UNICODE);

?>