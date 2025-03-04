<?php
include("exportmodal.php");
include("importmodal.php");

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
                <h2>Respaldos de Base de Datos</h2>
            </tr>
            <tr>
                <th style="text-align: center;">
                    <a href="../../../database/exportar/crear_backup.php"><button class="btn btn-secondary"  style="margin-top: 5%;"><i class="bi bi-database-add"></i> Hacer Respaldo</button></a>
                    <a href="../../../database/exportar/exportar_excel.php"><button class="btn btn-success" style="margin-top: 5%;"><i class="bi bi-filetype-xlsx"></i> Descargar Excel</button></a>
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
                    <td scope="row"><?php echo $backup['fecha_creacion'] ?></td>
                    <td scope="row"><?php echo $backup['nombre'] ?></td>
                    <td scope="row" style="text-align:center;">
                    <button class="btn" type="submit" style="background-color: #388E3C;" data-bs-toggle="modal" data-bs-target="#staticBackdrop_<?php echo $backup['id'] ?>">
                        <box-icon name='download' color='#fffefe'></box-icon>
                    </button>
                    <button class="btn" type="submit" style="background-color: #1976D2;" data-bs-toggle="modal" data-bs-target="#importBackdrop_<?php echo $backup['id'] ?>"><box-icon name='upload' color='#fffefe'></box-icon></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>


</html>