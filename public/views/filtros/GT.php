<?php
    require '../../../app/Models/conexion.php';

    $id = (int) $_GET['id'];
    $NPE= $_GET['nombre'];
    
    $usuarios = "SELECT * FROM usuarios WHERE rol_id = 3 AND carrera = " . $id; 
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
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Matrícula</th>
            <th>Carrera</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $estudiante):?>
        <tr data-genero="<?php echo $estudiante['carrera']; ?>">
            <td> <?php echo $estudiante['id'] ?> </td>
            <td> <?php echo $estudiante['nombre']; ?> </td>
            <td> <?php echo $estudiante['apellido_paterno'] . ' ' . $estudiante['apellido_materno']; ?> </td>
            <td> <?php echo $estudiante['matricula'] ?> </td>
            <td> <?php echo $NPE;?> </td>
            <td> <?php echo $estudiante['email'] ?> </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<script>
    // Obtener los elementos de la tabla y el filtro
    const table = document.getElementById('usuariosTable').getElementsByTagName('tbody')[0];
    const filter = document.getElementById('filterGenero');

    // Evento que se dispara cuando cambia el select
    filter.addEventListener('change', function() {
        const selectedGenero = this.value;

        // Iterar sobre todas las filas de la tabla
        for (let row of table.rows) {
            const genero = row.getAttribute('data-genero');

            // Si el valor del filtro es "0" (todos) o coincide con el género, mostrar la fila
            if (selectedGenero === "0" || genero === selectedGenero) {
                row.style.display = '';
            } else {
                // Si no coincide, ocultar la fila
                row.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>
