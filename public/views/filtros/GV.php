<?php
    require '../../../app/Models/conexion.php';

    $id = (int) $_GET['id'];
    $NGV= $_GET['nombre'];
    
    $usuarios = "SELECT * FROM usuarios WHERE rol_id = 3 AND grupos_v = " . $id;
    $consulta = mysqli_query($conexion, $usuarios);
    $data = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

    $ig = "SELECT * FROM i_genero";
    $consulta2 = mysqli_query($conexion, $ig);
    $dataig = mysqli_fetch_all($consulta2, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Grupos Vulnerables</title>
    </head>
<body>
        <?php if ($id == 3) {?>
            <style>
                .nose{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 1100px;
                }
            </style>
        <?php }else{ ?>
            <style>
                .nose{
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    width: 1100px;
                }
            </style>
        <?php } ?>

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
    <h2 class="text-center">Estudiantes por: <?php echo $NGV; ?></h2>
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="nose">
            <?php if ($id == 3) {?>

                <div class="filtro">
                    Filtrar por:
                    <select name="ig" id="filterGenero" class="form-select"> 
                        <option value="0" selected>Todos</option>
                        <?php foreach ($dataig as $ig):?>
                            <option value="<?php echo $ig['id']; ?>"><?php echo $ig['nombreig']; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            
            <?php } ?>


            <?php if ($id == 3) {?><?php } ?>


                <div class="boton"> <a href="../sesiones/index.php" class="btn btn-primary">Volver a los filtros</a> </div>
            </div>
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
        <tr data-genero="<?php echo $estudiante['i_genero']; ?>">
            <td> <?php echo $estudiante['id'] ?> </td>
            <td> <?php echo $estudiante['nombre']; ?> </td>
            <td> <?php echo $estudiante['apellido_paterno'] . ' ' . $estudiante['apellido_materno']; ?> </td>
            <td> <?php echo $estudiante['matricula'] ?> </td>
            <td> <?php echo $estudiante['carrera'] ?> </td>
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
