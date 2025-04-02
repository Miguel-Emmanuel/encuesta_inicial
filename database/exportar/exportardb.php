<?php
// Incluir las conexiones a MySQL (respaldos y base de datos principal)
include("../conexion.php"); // Conexión a MySQL principal
include("../mongo_conexion.php"); // Conexión a MySQL (tabla respaldos)

// Verificar conexión a MySQL
if ($conexion->connect_error) {
    die("Error de conexión a MySQL: " . $conexion->connect_error);
}

// Verificar conexión a la tabla 'respaldos' en MySQL
if (!$conexion_respaldo) {
    die("Error de conexión a la base de datos de respaldos.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $backupId = $_POST['id'];

        // Obtener la ruta del respaldo desde la tabla 'respaldos' en MySQL usando el ID
        $query = "SELECT ruta FROM respaldos WHERE id = ?";
        $stmt = $conexion_respaldo->prepare($query);
        $stmt->bind_param('i', $backupId); // Usamos 'i' porque el ID es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $backup = $result->fetch_assoc();

        if ($backup) {
            $filePath = $backup['ruta']; // Ruta completa del archivo de respaldo

            // Definir la ruta de mysqldump como una variable
            $mysqldumpPath = '"C:\xampp\mysql\bin\mysqldump.exe"'; // Ajusta la ruta si es necesario

            // Determina qué tipo de exportación se necesita
            switch ($accion) {
                case 'data':
                    // Exportar solo los datos
                    $command = "$mysqldumpPath --host=$host --user=$user --password=$pass --no-create-info --databases $db > \"$filePath\"";
                    break;

                case 'structure':
                    // Exportar solo la estructura
                    $command = "$mysqldumpPath --host=$host --user=$user --password=$pass --no-data --databases $db > \"$filePath\"";
                    break;

                case 'all':
                    // Exportar todo (estructura y datos)
                    $command = "$mysqldumpPath --host=$host --user=$user --password=$pass --routines --events --databases $db > \"$filePath\"";
                    break;

                default:
                    echo "<script>alert('❌ Acción desconocida');</script>";
                    exit();
            }

            // Ejecutar el comando de mysqldump
            exec($command, $output, $result);

            // Mostrar detalles de la ejecución del comando para depuración
            if ($result === 0) {
                // El archivo se ha generado correctamente, proceder a la descarga
                header('Content-Type: application/sql');
                header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit();
            } else {
                // Si ocurre un error, muestra la salida de la ejecución para depuración
                echo "<script>alert('❌ Error al generar el archivo.');</script>";
                echo "<pre>" . print_r($output, true) . "</pre>"; // Muestra la salida del comando mysqldump
            }
        } else {
            echo "<script>alert('❌ No se encontró el respaldo en la base de datos.');</script>";
        }
    }
}

?>
