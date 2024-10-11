<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, nombreig, descripcion From i_genero WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$Igenero = [];

if($rows > 0){
    $Igenero = $resultado->fetch_array();
}

echo json_encode($Igenero, JSON_UNESCAPED_UNICODE);

?>