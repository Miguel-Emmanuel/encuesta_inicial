<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento Estudiante</title>
</head>

<body>
    <?php
    require '../../../app/Models/conexion.php';


    $tipo = intval($_POST['tipo']);

    if ($tipo == 1) {

        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        $apellido_paterno = isset($_POST['ap']) ? $_POST['ap'] : '';
        $apellido_materno = isset($_POST['am']) ? $_POST['am'] : '';


        $qbusqueda = "SELECT 
                    u.id AS usuario_id,    -- ID del usuario
                    e.id AS estudiante_id, -- ID del estudiante
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    e.matricula,
                    prog.nombre AS carrera,
                    t_gr.nomenclatura AS grupo,
                    u.activo AS activo  -- Indica si el usuario está activo
                FROM estudiante_grupo eg
                INNER JOIN estudiantes e ON eg.estudiante_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE eg.activo = 1";

        // Aplicar filtros dinámicos si los campos no están vacíos
        if (!empty($nombre)) {
            $qbusqueda .= " AND u.nombre LIKE '%" . $conexion->real_escape_string($nombre) . "%'";
        }
        if (!empty($apellido_paterno)) {
            $qbusqueda .= " AND u.apellido_paterno LIKE '%" . $conexion->real_escape_string($apellido_paterno) . "%'";
        }
        if (!empty($apellido_materno)) {
            $qbusqueda .= " AND u.apellido_materno LIKE '%" . $conexion->real_escape_string($apellido_materno) . "%'";
        }
    } else if ($tipo == 2) {

        $matricula = intval($_POST['matricula']);
        $qbusqueda = "SELECT 
                    u.id AS usuario_id,    -- ID del usuario
                    e.id AS estudiante_id, -- ID del estudiante
                    CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS estudiante,
                    e.matricula,
                    prog.nombre AS carrera,
                    t_gr.nomenclatura AS grupo,
                    u.activo AS activo  -- Indica si el usuario está activo
                FROM estudiante_grupo eg
                INNER JOIN estudiantes e ON eg.estudiante_id = e.id
                INNER JOIN usuarios u ON e.usuario_id = u.id
                INNER JOIN t_grupos t_gr ON eg.grupo_id = t_gr.id
                LEFT JOIN programa_edu prog ON t_gr.programa_e = prog.id
                WHERE eg.activo = 1 AND e.matricula = $matricula";

    }

    // Ejecutar la consulta
    $busqueda = mysqli_query($conexion, $qbusqueda);
    $dataresult = mysqli_fetch_all($busqueda, MYSQLI_ASSOC);
    ?>

    <style>
        .btn-buscar,
        .btn-buscar:hover {
            background-color: #8E70D0;
            border: solid 1px;
            color: white;
        }

        .input-group {
            padding-bottom: 5%;
        }
    </style>

    <div class="container">
        <div class="pt-4 pb-5 px-6 border-bottom border-secondary-light">
            <h4 class="mb-0">Estdudiantes Resultados</h4>
        </div>
        <div class="boton"> <a href="index.php" class="btn btn-primary">Regresar</a></div>
        <div class="px-4 table-responsive">
            <table class="table mb-0 table-borderless table-striped small">
                <thead>
                    <tr class="text-secondary">
                        <th class="py-3 px-6">Estudiante</th>
                        <th class="py-3 px-6">Matrícula</th>
                        <th class="py-3 px-6">Carrera</th>
                        <th class="py-3 px-6">Grupo</th>
                        <th class="py-3 px-6">Estatus</th>
                        <th class="py-3 px-6">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataresult as $estudiante): ?>
                        <tr>
                            <td class="py-5 px-6"><?php echo $estudiante['estudiante']; ?></td>
                            <td class="py-5 px-6"><?php echo $estudiante['matricula']; ?></td>
                            <td class="py-5 px-6"><?php echo $estudiante['carrera']; ?></td>
                            <td class="py-5 px-6"><?php echo $estudiante['grupo']; ?></td>
                            <td class="py-5 px-6">
                                <?php
                                if ($estudiante['activo'] == 1) { ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php } else if ($estudiante['activo'] == 0) { ?>
                                    <span class="badge bg-danger">Baja</span>
                                <?php } ?>
                            </td>
                            <td class="py-5 px-6">
                                <form action="indexrbusqueda.php" method="POST">
                                    <input type="hidden" name="usuario" value="<?php echo $estudiante['usuario_id']; ?>">
                                    <input type="hidden" name="estudiante" value="<?php echo $estudiante['estudiante_id']; ?>">
                                    <button type="submit" class="btn btn-buscar"><i class="fa-regular fa-file-lines" style="color: #ffffff;"></i> Seguimiento</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>