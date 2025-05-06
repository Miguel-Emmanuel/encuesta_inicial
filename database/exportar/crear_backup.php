<?php
require("../../app/Controllers/auth.php");
include("../conexion.php"); // Conexión a MySQL
include("../mongo_conexion.php"); // Este archivo ya está para MySQL

// Ruta del backup
$backupDir = __DIR__ . "/../backups";
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Nombre del archivo con timestamp
$fechaArchivo = date("Y-m-d_H-i-s");
$backupName = "backup_$fechaArchivo.sql";
$dumpFile = "$backupDir/$backupName";

// Comando mysqldump (ajustar ruta según instalación)
$dumpFile = str_replace("\\", "/", $dumpFile);
$mysqldumpPath = '""D:\Programas\Xampp\mysql\bin\mysqldump.exe""';
//////////////////PARA SERVIDOR ///////////////
// $mysqldumpPath = '"/bin/mysqldump"';
/////////////////////////////////////////////////
$command = "$mysqldumpPath --host=$host --user=$user --password=$pass --routines --events --databases $db > \"$dumpFile\"";


// Ejecutar el comando
exec($command, $output, $result);

if ($result === 0) { // Si el backup se generó correctamente
    // Obtener fecha local correctamente
    $fechaLocal = (new DateTime('now', new DateTimeZone('America/Mexico_City')))->format('Y-m-d H:i:s');

    // Insertar registro en MySQL (en la tabla 'respaldos')
    $insertQuery = "INSERT INTO respaldos (nombre, ruta, fecha_creacion) VALUES (?, ?, ?)";
    $stmt = $conexion_respaldo->prepare($insertQuery);
    $stmt->bind_param("sss", $backupName, realpath($dumpFile), $fechaLocal);

    if ($stmt->execute()) {
        $mensaje = "Backup generado y registrado en la base de datos correctamente.";
        $tipo = "Listo"; // Alerta de éxito

        // Verificar cuántos respaldos existen
        $query = "SELECT * FROM respaldos ORDER BY fecha_creacion ASC"; // Ordenar por fecha
        $result = $conexion_respaldo->query($query);
        $backups = $result->fetch_all(MYSQLI_ASSOC);

        if (count($backups) > 3) {
            $oldestBackup = $backups[0]; // Primer elemento (más antiguo)
            $oldestFilePath = $oldestBackup['ruta'];

            // Eliminar archivo físico
            if (file_exists($oldestFilePath)) {
                unlink($oldestFilePath);
                $mensaje .= "<br>Archivo más antiguo eliminado: {$oldestBackup['nombre']}";
            } else {
                $mensaje .= "<br>Archivo no encontrado en la ruta: $oldestFilePath";
            }

            // Eliminar el registro en MySQL
            $deleteQuery = "DELETE FROM respaldos WHERE id = ?";
            $stmt = $conexion_respaldo->prepare($deleteQuery);
            $stmt->bind_param("i", $oldestBackup['id']);
            $stmt->execute();
            $mensaje .= "<br>Registro eliminado de la base de datos.";
        }
    } else {
        $mensaje = "Error al registrar el backup en la base de datos.";
        $tipo = "danger"; // Alerta de error
    }
} else {
    $mensaje = "Error al generar el backup.";
    $tipo = "danger"; // Alerta de error
}

// Enviar mensaje de error o éxito con alerta y redirección
// Codificar los parámetros antes de pasarlos en la URL
$mensaje = urlencode($mensaje);
$tipo = urlencode($tipo);

// Redirigir usando header() después de haber enviado los mensajes
header("Location: /public/views/dbbackup/index.php?mensaje=$mensaje&tipo=$tipo");
exit(); // Asegurarse de que el script no siga ejecutándose después de la redirección
?>
