<?php
    require '../../../app/Models/conexion.php';

    $id = (int) $_GET['id'];
    $NT= $_GET['nombre'];
    
    $usuarios = "SELECT 
                    t.id AS tutor_id,
                    t.usuario_id,
                    CONCAT(u_tutor.nombre, ' ', u_tutor.apellido_paterno, ' ', u_tutor.apellido_materno) AS nombre_tutor,
                    e.id AS estudiante_id,
                    CONCAT(u_est.nombre, ' ', u_est.apellido_paterno, ' ', u_est.apellido_materno) AS nombre_estudiante,
                    tg.nomenclatura AS grupo,
                    e.matricula AS matricula
                FROM tutores t
                INNER JOIN grupo_tutor gt ON t.id = gt.tutor_id
                INNER JOIN t_grupos tg ON gt.grupo_id = tg.id
                INNER JOIN estudiante_grupo eg ON tg.id = eg.grupo_id
                INNER JOIN estudiantes e ON eg.estudiante_id = e.id
                INNER JOIN usuarios u_tutor ON t.usuario_id = u_tutor.id  
                INNER JOIN usuarios u_est ON e.usuario_id = u_est.id  
                WHERE t.id = $id;  
            ";
    $consulta = mysqli_query($conexion, $usuarios);
    $data = mysqli_fetch_all($consulta, MYSQLI_ASSOC);  
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
    <h2 class="text-center">Estudiantes por: <?php echo $NT; ?></h2>
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
        <?php foreach ($data as $estudiante):?>
        <tr>
            <td> <?php echo $estudiante['estudiante_id'] ?> </td>
            <td> <?php echo $estudiante['nombre_estudiante']; ?> </td>
            <td> <?php echo $estudiante['matricula'] ?> </td>
            <td> <?php echo $estudiante['grupo'] ?> </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
</body>
</html>
