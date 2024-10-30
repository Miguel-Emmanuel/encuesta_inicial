<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, u.pass, u.rol_id,
 IFNULL(t.clave_sp, '') AS clave_sp, IFNULL(t.telefono, '')AS tele From usuarios u
 LEFT JOIN tutores t ON u.id = t.usuario_id AND rol_id = 2
WHERE u.id = $id LIMIT 1";

$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$users = [];

if($rows > 0){
    $users = $resultado->fetch_array();
}

echo json_encode($users, JSON_UNESCAPED_UNICODE);

?>