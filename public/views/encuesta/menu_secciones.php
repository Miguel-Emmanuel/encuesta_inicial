<?php
include("../../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
    exit;
}

// Crear conexión

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener secciones
$sql = "SELECT s.id, s.nombre, s.descripcion,  COUNT(ur.id) as completado
        FROM secciones s
        LEFT JOIN preguntas p ON s.id = p.seccion_id
        LEFT JOIN usuario_respuesta ur ON p.id = ur.pregunta_id AND ur.usuario_id = 1  -- Reemplaza 1 con el ID del usuario actual
        GROUP BY s.id, s.nombre";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Menu de Secciones</title>
            <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="../../css/menusecciones.css">
        </head>
        <body>
        <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Navbar</span>
            <a href="../../../app/Controllers/sessiondestroy_controller.php"><button class="btn btn-danger" name="">Cerrar Sesion</button></a>
        </div>
        </nav>
            <div class="contenedor rounded shadow">
            <h1>Menu de Secciones</h1>
                <table>
                    <thead>
                        <th>Seccion</th>
                        <th>Acciones</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $completado = $row['completado'] > 0 ? '✅' : '❌';
                                    echo "<tr>";
                                    echo "<td>" . $row["descripcion"] . "</td>";
                                    echo "<td class='centrar'><button class='btn btn-success'>Responder</button></td>";
                                    echo "<td class='centrar'>" . $completado . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>0 resultados</td></tr>";
                            }
                            $conexion->close();
                        ?>
                    </tbody>
                </table>
            </div>


            
            <script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
        </body>
</html>
