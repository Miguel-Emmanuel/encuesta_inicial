<?php
    require '../../../app/Models/conexion.php';

    $tutorid= $_GET['t'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Programa Educativo</title>
    </head>
<body>

    <style>
        .col-auto{
            font-size: large;
        }
        .filtro{
            width: 25%;
        }
        .boton{
            align-self: right;
        }

    </style>
<div class="container py-3">
    <h2 class="text-center">Estudiantes</h2>
    <div class="row justify-content-end">
        <div class="col-auto">
                <div class="boton"> <a href="../sesiones/index.php" class="btn btn-primary">Volver a los filtros</a> </div>
        </div>
    </div>
    <table id="usuariosTable" class="table table-sm table-striped table-hover mt-4">
    <thead>
        <tr>
            <th>Estudiante</th>
            <th>Nombre</th>
            <th>Matrícula</th>
            <th>Grupo</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</body>
</html>