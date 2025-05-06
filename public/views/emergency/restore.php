<?php
include("importmodal.php");
include("../../../database/mongo_conexion.php"); // Conexión a MySQL de respaldo
$query = "SELECT id, nombre, ruta, fecha_creacion FROM respaldos ORDER BY fecha_creacion DESC";
$result = mysqli_query($conexion_respaldo, $query);
?>

<style>
    body {
        padding: 5% 15% 15% 15%;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respaldos de Base de Datos</title>
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
            // Obtener los respaldos desde MySQL

            while ($backup = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td scope="row"><?php echo $backup['fecha_creacion']; ?></td>
                    <td scope="row"><?php echo $backup['nombre']; ?></td>
                    <td scope="row" style="text-align:center;">
                        <button class="btn" type="submit" style="background-color: #1976D2;" 
                            data-bs-toggle="modal" data-bs-target="#importBackdrop_<?php echo $backup['id']; ?>">
                            <box-icon name='upload' color='#fffefe'></box-icon>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
