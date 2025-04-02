<?php
include("emergency_conexion.php");
include("../mongo_conexion.php"); // Aquí incluimos el archivo que maneja la conexión a MySQL (con el nombre 'mongo_conexion.php')

// Verifica si la conexión a MySQL fue exitosa
if (!$conexion_exitosa) {
    echo "<script>
        alert('❌ La conexión con MySQL no se ha podido establecer, por favor contacte a soporte.');
        window.history.back(); // Redirecciona a la página anterior
    </script>";
    exit; // Detiene la ejecución del script
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $backupId = $_POST['id'];

        // Aquí obtenemos la ruta del respaldo desde la base de datos "respaldo" (no desde MongoDB)
        $query = "SELECT ruta FROM respaldos WHERE id = '$backupId'"; // Cambiar la consulta según tu estructura de base de datos
        $result = mysqli_query($conexion_respaldo, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Obtener la ruta del archivo de respaldo
            $backup = mysqli_fetch_assoc($result);
            $filePath = $backup['ruta']; // Ruta completa del archivo de respaldo

            // Verifica si la ruta es válida
            if (!file_exists($filePath)) {
                echo "<script>
                    alert('❌ El archivo no existe en la ruta proporcionada.');
                    window.history.back(); // Redirecciona a la página anterior
                </script>";
                exit;
            }

            // Define la ruta de mysql como una variable
            $mysqlPath = 'C:\xampp\mysql\bin\mysql.exe'; // Ajusta la ruta si es necesario

            // Verifica si mysql.exe existe
            if (!file_exists($mysqlPath)) {
                echo "<script>
                    alert('❌ No se encuentra mysql.exe en la ruta especificada.');
                    window.history.back(); // Redirecciona a la página anterior
                </script>";
                exit;
            }

            // Conexión a MySQL sin especificar la base de datos (solo servidor)
            $connection = new mysqli($host, $user, $pass);

            // Verifica si hay un error de conexión
            if ($connection->connect_error) {
                echo "<script>
                    alert('❌ Error de conexión: " . $connection->connect_error . "');
                    window.history.back(); // Redirecciona a la página anterior
                </script>";
                exit;
            }

            // Verificar si la base de datos existe
            $checkDbQuery = "SHOW DATABASES LIKE '$db'";
            $dbResult = $connection->query($checkDbQuery);

            // Si la base de datos no existe, crearla
            if ($dbResult->num_rows == 0) {
                $createDbQuery = "CREATE DATABASE $db";
                if ($connection->query($createDbQuery) === TRUE) {
                    echo "<script>alert('✅ Base de datos creada correctamente.');</script>";
                } else {
                    echo "<script>
                        alert('❌ Error al crear la base de datos: " . $connection->error . "');
                        window.history.back(); // Redirecciona a la página anterior
                    </script>";
                    exit;
                }
            } else {
                echo "<script>alert('✅ La base de datos ya existe.');</script>";
            }

            // Cierra la conexión a la base de datos
            $connection->close();

            // Ahora importa el respaldo de la base de datos
            $command = "\"$mysqlPath\" --host=$host --user=$user --password=$pass $db < \"$filePath\"";
            echo "<pre>Comando ejecutado: " . $command . "</pre>";

            // Ejecutar el comando para restaurar el respaldo
            exec($command, $output, $result);

            // Muestra detalles de la ejecución del comando para depuración
            if ($result === 0) {
                echo "<script>alert('✅ El archivo se importó correctamente a la base de datos.');</script>";
                include("../../app/Controllers/sessiondestroy_controller.php");
            } else {
                // Si ocurre un error, muestra la salida de la ejecución para depuración
                echo "<script>
                    alert('❌ Error al importar el archivo.');
                    window.history.back(); // Redirecciona a la página anterior
                </script>";
                echo "<pre>Salida del comando: " . print_r($output, true) . "</pre>"; // Muestra la salida del comando mysql
                echo "<pre>Código de resultado: " . $result . "</pre>";
            }
        } else {
            echo "<script>
                alert('❌ No se encontró el respaldo en la base de datos.');
                window.history.back(); // Redirecciona a la página anterior
            </script>";
        }
    }
}
?>
