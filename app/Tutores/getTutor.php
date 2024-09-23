<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, nombre, apellido_p, apellido_m, clave_sp, correo, telefono From tutores WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$tutor = [];

if($rows > 0){
    $tutor = $resultado->fetch_array();
}

echo json_encode($tutor, JSON_UNESCAPED_UNICODE);

?>