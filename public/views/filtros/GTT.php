<?php
    require '../../../app/Models/conexion.php';

    $tutor_id= $_GET['it'];
    $grupo_nombre= $_GET['nombre'];
    $grupo_id = $_GET['ig'];

    $sqlAlumnos = "SELECT 
        eg.estudiante_id, 
        CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre_completo,
        e.matricula,
        p.alias AS periodo_escolar
    FROM 
        estudiante_grupo AS eg
    INNER JOIN estudiantes AS e ON e.id = eg.estudiante_id
    INNER JOIN usuarios AS u ON u.id = e.usuario_id
    INNER JOIN t_grupos AS g ON g.id = eg.grupo_id
    INNER JOIN grupo_tutor AS gt ON gt.grupo_id = g.id
    INNER JOIN periodos_escolar AS p ON p.id = gt.periodo_id
    WHERE 
        gt.tutor_id = $tutor_id 
        AND g.id = $grupo_id  
        AND eg.activo = 1";

$Alumnos = mysqli_query($conexion, $sqlAlumnos);

$TutorAlumnos = mysqli_fetch_all($Alumnos, MYSQLI_ASSOC);

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
    <h2 class="text-center">Estudiantes Grupo: <?php echo $grupo_nombre; ?></h2>
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
            <th>Periodo Escolar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($TutorAlumnos as $item): ?>
        <tr>
            <td> <?php echo $item['estudiante_id'] ?> </td>
            <td> <?php echo $item['nombre_completo'] ?> </td>
            <td> <?php echo $item['matricula'] ?> </td>
            <td> <?php echo $item['periodo_escolar'] ?> </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</body>
</html>