<script src="https://kit.fontawesome.com/198c26d5ef.js" crossorigin="anonymous"></script>
<?php
require '../../../app/Models/conexion.php';

$estudiante = intval($_POST['estudiante']);
$usuario = intval($_POST['usuario']);

$usuarios = "SELECT 
                    e.id AS id,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    e.matricula,
                    t_gr.nomenclatura AS grupo,
                    CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno) AS tutor,
                    p.alias AS periodo_escolar,
                    prog.nombre AS carrera,
                    eg.activo AS activo
                FROM estudiante_grupo eg
                INNER JOIN estudiantes e ON eg.estudiante_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id  -- Para obtener el nombre completo del tutor
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE e.id = $estudiante";  // Filtra por el id del estudiante


$consulta = mysqli_query($conexion, $usuarios);
$data = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento</title>
</head>

<body>
    <div class="pt-4 pb-5 px-6 border-bottom border-secondary-light">
        <h4 class="mb-0">Seguimiento del Estudiante.</h4>
    </div>
    <div class="boton"> <a href="index.php" class="btn btn-primary">Regresar</a></div>
    <div class="container py-3">
        <div class="table-responsive">
            <table id="usuariosTable" class="table table-sm table-striped table-hover mt-4">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Nombre</th>
                        <th>Matrícula</th>
                        <th>Carrera</th>
                        <th>Grupo</th>
                        <th>Tutor</th>
                        <th>Periodo Escolar</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $estudiante): ?>
                        <tr>
                            <td><?php echo $estudiante['id']; ?></td>
                            <td><?php echo $estudiante['estudiante']; ?></td>
                            <td><?php echo $estudiante['matricula']; ?></td>
                            <td><?php echo $estudiante['carrera']; ?></td>
                            <td><?php echo $estudiante['grupo']; ?></td>
                            <td><?php echo $estudiante['tutor']; ?></td>
                            <td><?php echo $estudiante['periodo_escolar']; ?></td>
                            <td class="py-5 px-6">
                                <?php
                                if ($estudiante['activo'] == 1) { ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php } else if ($estudiante['activo'] == 0) { ?>
                                    <span class="badge bg-danger">Baja</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>