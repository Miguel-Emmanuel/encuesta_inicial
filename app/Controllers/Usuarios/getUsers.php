<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, u.pass, u.rol_id,
 IFNULL(e.matricula,'')AS matricula, IFNULL(e.telefono, '')AS telefono, IFNULL(e.grupos_v,'') AS grupos_v, IFNULL(e.genero, '') AS genero, IFNULL(e.i_genero, '') AS i_genero,
 IFNULL(t.clave_sp, '') AS clave_sp, IFNULL(t.telefono, '')AS tele From usuarios u
 LEFT JOIN estudiantes e ON u.id = e.usuario_id AND u.rol_id = 3
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