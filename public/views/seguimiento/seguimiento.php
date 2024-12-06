<script src="https://kit.fontawesome.com/198c26d5ef.js" crossorigin="anonymous"></script>
<script>
    const myModal = document.getElementById('myModal')
    const myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', () => {
        myInput.focus()
    })
</script>
<?php
require '../../../app/Models/conexion.php';

$usuarios = "SELECT 
                    e.id AS id,
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    e.matricula, e.activo,
                    t_gr.nomenclatura AS grupo,
                    CONCAT(tu.nombre, ' ', tu.apellido_paterno, ' ', tu.apellido_materno) AS tutor,
                    p.alias AS periodo_escolar,
                    prog.nombre AS carrera,
                    eg.activo AS egactivo
                FROM estudiante_grupo eg
                INNER JOIN estudiantes e ON eg.estudiante_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN grupo_tutor gt ON t_gr.id = gt.grupo_id AND eg.periodo_id = gt.periodo_id
                LEFT JOIN tutores t ON gt.tutor_id = t.id
                LEFT JOIN usuarios tu ON t.usuario_id = tu.id  -- Para obtener el nombre completo del tutor
                LEFT JOIN periodos_escolar p ON eg.periodo_id = p.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE eg.activo = 1";


$consulta = mysqli_query($conexion, $usuarios);
$data = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    .btn-buscar,
    .btn-buscar:hover {
        background-color: #8E70D0;
        border: solid 1px;
    }
</style>

<body>

<h2 class="text-center">Seguimiento de Estudiantes</h2> 
    <div class="container py-3">
        <div class="row justify-content-center">
            <form action="indexresultados.php" method="POST">
                <input type="hidden" value="2" name="tipo">
                <div class="input-group mb-3 w-25">
                    <span class="input-group-text" id="inputGroup-sizing-default">Matrícula</span>
                    <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="basic-addon2" name="matricula" placeholder="222111***">
                    <button type="submit" class="btn btn-buscar"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></button>
                </div>
                <div class="form-text">
                    Es necesaria una matrícula completa para obtener un resultado.
                </div>
            </form>
            <form action="indexresultados.php" method="POST">
                <div class="input-group group1">
                    <span class="input-group-text w-25">Nombre y Apellidos</span>
                    <input type="hidden" value="1" name="tipo">
                    <input type="text" aria-label="Nombre" name="nombre" class="form-control" placeholder="Nombre: Fer">
                    <input type="text" aria-label="ApellidoP" name="ap" class="form-control" placeholder="Apellido Paterno: San">
                    <input type="text" aria-label="ApellidoM" name="am" class="form-control" placeholder="Apellido Materno: Guz">
                    <button type="submit" class="btn btn-buscar"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></button>
                </div>
            </form>
        </div>
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
                        <th>Usuario Activo</th>
                        <th>Grupo Activo</th>
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
                            <td>
                                <?php
                                if ($estudiante['activo'] == 1) { ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php } else if ($estudiante['activo'] == 0) { ?>
                                    <span class="badge bg-danger">Baja</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                if ($estudiante['egactivo'] == 1) { ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php } else if ($estudiante['egactivo'] == 0) { ?>
                                    <span class="badge bg-danger">Baja</span>
                                <?php } ?>
                            </td>
                        </tr>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>