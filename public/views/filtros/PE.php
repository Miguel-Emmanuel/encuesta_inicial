<?php
    require '../../../app/Models/conexion.php';

    $id = (int) $_GET['id'];
    $NPE= $_GET['nombre'];
    
    $usuarios = "SELECT 
                    e.id AS id,
                    u.nombre AS nombre,
                    u.apellido_paterno,
                    u.apellido_materno,
                    e.matricula,
                    p.nombre AS programa_edu,
                    t_gr.nomenclatura AS grupo,
                    u.email
                FROM estudiantes e
                JOIN usuarios u ON e.usuario_id = u.id
                JOIN estudiante_grupo eg ON e.id = eg.estudiante_id
                JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                JOIN programa_edu p ON t_gr.programa_e = p.id
                WHERE p.id = $id
            ";
    $consulta = mysqli_query($conexion, $usuarios);
    $data = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

    $pe = "SELECT * FROM programa_edu";
    $consulta2 = mysqli_query($conexion, $pe);
    $datape = mysqli_fetch_all($consulta2, MYSQLI_ASSOC);
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
    <h2 class="text-center">Estudiantes por: <?php echo $NPE; ?></h2>
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
            <th>Apellidos</th>
            <th>Matrícula</th>
            <th>Carrera</th>
            <th>Email</th>
            <th>Grupo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $estudiante):?>
        <tr>
            <td> <?php echo $estudiante['id'] ?> </td>
            <td> <?php echo $estudiante['nombre']; ?> </td>
            <td> <?php echo $estudiante['apellido_paterno'] . ' ' . $estudiante['apellido_materno']; ?> </td>
            <td> <?php echo $estudiante['matricula'] ?> </td>
            <td> <?php echo $estudiante['programa_edu'] ?> </td>
            <td> <?php echo $estudiante['email'] ?> </td>
            <td> <?php echo $estudiante['grupo'] ?> </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
</body>
</html>
