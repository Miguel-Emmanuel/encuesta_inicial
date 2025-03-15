<?php

include("importmodal.php");
include("exportmodal.php");
?>

<style>
    body{
        padding: 5% 15% 15% 15%;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respaldos de Base de Datos</title>`
</head>
<body>
    <table style="width: 100%;">
        <thead>
            <tr>
                <h2>Respaldos de Base de Datos</h2>
            </tr>
        </thead>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr style="text-align:center;">
                <th scope="col">Fecha</th>
                <th scope="col">Nombre del Archivo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include("../../../database/mongo_conexion.php");
            // Obtener los respaldos desde MongoDB
            $respaldos = $collection->find([], ['sort' => ['fecha_creacion' => -1]]);
            foreach ($respaldos as $backup): ?>
                <tr>
                    <td scope="row"><?php echo $backup['fecha_creacion'] ?></td>
                    <td scope="row"><?php echo $backup['nombre'] ?></td>
                    <td scope="row" style="text-align:center;">
                        <button class="btn" type="submit" style="background-color: #388E3C;" 
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop_<?php echo $backup['_id'] ?>">
                            <box-icon name='download' color='#fffefe'></box-icon>
                        </button>
                        <button class="btn" type="submit" style="background-color: #1976D2;" 
                            data-bs-toggle="modal" data-bs-target="#importBackdrop_<?php echo $backup['_id'] ?>">
                            <box-icon name='upload' color='#fffefe'></box-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
