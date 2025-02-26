<?php

$respaldos = "SELECT * FROM respaldos";
$consulta = mysqli_query($conexion, $respaldos);
$rrespaldos = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center;">
                    <a href="../../../database/exportar/exportar_dump.php"><button class="btn btn-secondary">Hacer Respaldo</button></a>
                    <button class="btn btn-success"><i class="bi bi-filetype-xlsx"></i> Descargar Excel</button>
                </th>
            </tr>
        </thead>
    </table>

    <table class="table table-bordered">`
        <thead>
            <tr style="text-align:center;">
                <th scope="col">Fecha</th>
                <th scope="col">Nombre del Archivo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($rrespaldos as $backup): ?>
                <tr>
                    <th scope="row"><?php echo $backup['fecha_creacion'] ?></th>
                    <th scope="row"><?php echo $backup['nombre'] ?></th>
                    <th scope="row" style="text-align:center;">
                        <button><i class="fa-solid fa-download"></i></button>
                        <button class="btn" style="background-color: #6f42c1;"><i class="bi bi-box-arrow-in-up"></i></button>
                    </th>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>


</html>