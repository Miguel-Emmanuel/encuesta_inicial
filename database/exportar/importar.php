<?php
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $backupId = $_POST['id'];

        // Obtén la ruta del respaldo desde la base de datos usando el ID
        $sql = "SELECT ruta FROM respaldos WHERE id = $backupId";
        $result = $conexion->query($sql);
        $backup = $result->fetch_assoc();

        if ($backup) {
            $filePath = $backup['ruta']; // Ruta completa del archivo de respaldo

            // Definir la ruta de mysql como una variable
            $mysqlPath = '"C:\xampp\mysql\bin\mysql.exe"'; // Ajusta la ruta si es necesario

            // Comando para importar el dump a la base de datos
            $command = "$mysqlPath --host=$host --user=$user --password=$pass $db < \"$filePath\"";

            // Ejecutar el comando
            exec($command, $output, $result);

            // Mostrar detalles de la ejecución del comando para depuración
            if ($result === 0) {
                echo "<script>alert('✅ El archivo se importó correctamente a la base de datos.');</script>";
                include("../../app/Controllers/sessiondestroy_controller.php");
            } else {
                // Si ocurre un error, muestra la salida de la ejecución para depuración
                echo "<script>alert('❌ Error al importar el archivo.');</script>";
                echo "<pre>" . print_r($output, true) . "</pre>"; // Muestra la salida del comando mysql
            }
        } else {
            echo "<script>alert('❌ No se encontró el respaldo en la base de datos.');</script>";
        }
    }
}
?>
