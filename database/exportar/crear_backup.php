<?php
require("../../app/Controllers/auth.php");
include("../conexion.php");
include("../mongo_conexion.php");

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
$mysqldumpPath = '"C:\xampp\mysql\bin\mysqldump.exe"';
$command = "$mysqldumpPath --host=$host --user=$user --password=$pass --routines --events --databases $db > \"$dumpFile\"";

// Ejecutar el comando
exec($command, $output, $result);

if ($result === 0) { // Si el backup se generó correctamente
    // Obtener fecha local correctamente
    $fechaLocal = (new DateTime('now', new DateTimeZone('America/Mexico_City')))->format('Y-m-d H:i:s');

    // Insertar registro en MongoDB
    $backupData = [
        'nombre' => $backupName,
        'ruta' => realpath($dumpFile), // Ruta absoluta
        'fecha_creacion' => $fechaLocal // Fecha en zona horaria local
    ];

    $insertResult = $collection->insertOne($backupData);

    if ($insertResult->getInsertedCount() > 0) {
        $mensaje = "Backup generado y registrado en MongoDB correctamente.";
        $tipo = "success"; // Alerta de éxito

        // Verificar cuántos respaldos existen
        $backups = $collection->find([], ['sort' => ['fecha_creacion' => 1]]);
        $backups = iterator_to_array($backups);

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

            // Eliminar el registro en MongoDB
            $collection->deleteOne(['_id' => $oldestBackup['_id']]);
            $mensaje .= "<br>Registro eliminado.";
        }
    } else {
        $mensaje = "Error al registrar el backup.";
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
