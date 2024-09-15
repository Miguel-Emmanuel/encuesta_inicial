<?php
    require '../../../app/Models/conexion.php';

    $GV = "SELECT * FROM gruposv;";
    $consulta = mysqli_query($conexion, $GV);
    $opciones = mysqli_fetch_all($consulta, MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos Vulnerables</title>
</head>
<body>
    
</body>
</html>