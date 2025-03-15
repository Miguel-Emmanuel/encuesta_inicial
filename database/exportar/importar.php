<?php
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $backupId = $_POST['id'];

        // Obtén la ruta del respaldo desde MongoDB usando el ID (no desde MySQL)
        // Aquí asumimos que ya tienes la conexión a MongoDB configurada
        include("../mongo_conexion.php");
        $backup = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($backupId)]);

        if ($backup) {
            $filePath = $backup['ruta']; // Ruta completa del archivo de respaldo

            // Verifica la ruta del archivo (agrega depuración)
            echo "<pre>Ruta del archivo: " . $filePath . "</pre>";

            // Verifica si la ruta es válida
            if (!file_exists($filePath)) {
                echo "<script>alert('❌ El archivo no existe en la ruta proporcionada.');</script>";
                exit;
            }

            // Define la ruta de mysql como una variable
            $mysqlPath = '"C:\xampp\mysql\bin\mysql.exe"'; // Ajusta la ruta si es necesario

            // Verifica si mysql.exe existe
            if (!file_exists($mysqlPath)) {
                echo "<script>alert('❌ No se encuentra mysql.exe en la ruta especificada.');</script>";
                exit;
            }

            // Imprime el comando para depuración
            $command = "$mysqlPath --host=$host --user=$user --password=$pass $db < \"$filePath\"";
            echo "<pre>Comando ejecutado: " . $command . "</pre>";

            // Ejecutar el comando
            exec($command, $output, $result);

            // Muestra detalles de la ejecución del comando para depuración
            if ($result === 0) {
                echo "<script>alert('✅ El archivo se importó correctamente a la base de datos.');</script>";
                include("../../app/Controllers/sessiondestroy_controller.php");
            } else {
                // Si ocurre un error, muestra la salida de la ejecución para depuración
                echo "<script>alert('❌ Error al importar el archivo.');</script>";
                echo "<pre>Salida del comando: " . print_r($output, true) . "</pre>"; // Muestra la salida del comando mysql
                echo "<pre>Código de resultado: " . $result . "</pre>";
            }
        } else {
            echo "<script>alert('❌ No se encontró el respaldo en la base de datos.');</script>";
        }
    }
}
?>
