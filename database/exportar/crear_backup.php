<?php
require("../../app/Controllers/auth.php");
include("../conexion.php");

// Ruta del backup
$backupDir = __DIR__ . "/../backups";
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Nombre del archivo con timestamp
$fecha = date("Y-m-d_H-i-s");
$backupName = "backup_$fecha.sql";
$dumpFile = "$backupDir/$backupName";

// Comando mysqldump (ajustar ruta según instalación)
$dumpFile = str_replace("\\", "/", $dumpFile);
$mysqldumpPath = '"C:\xampp\mysql\bin\mysqldump.exe"';
$command = "$mysqldumpPath --host=$host --user=$user --password=$pass --routines --events --databases $db > \"$dumpFile\"";

// Ejecutar el comando
exec($command, $output, $result);

if ($result === 0) {
    // Insertar en la base de datos
    $sqlInsert = "INSERT INTO respaldos (nombre, ruta) VALUES ('$backupName', '$dumpFile')";
    $conexion->query($sqlInsert);

    // Verificar si hay más de 3 registros
    $sqlCount = "SELECT id FROM respaldos ORDER BY id ASC";
    $resultCount = $conexion->query($sqlCount);

    if ($resultCount->num_rows > 3) {
        // Obtener el registro más antiguo
        $sqlOldest = "SELECT id, ruta FROM respaldos ORDER BY id ASC LIMIT 1";
        $oldestResult = $conexion->query($sqlOldest);

        if ($oldestResult->num_rows > 0) {
            $oldestRow = $oldestResult->fetch_assoc();
            $oldestId = $oldestRow['id'];
            $oldestFile = $oldestRow['ruta'];

            // Eliminar el archivo del sistema
            if (file_exists($oldestFile)) {
                unlink($oldestFile);
            }

            // Eliminar el registro de la base de datos
            $sqlDelete = "DELETE FROM respaldos WHERE id = $oldestId";
            if ($conexion->query($sqlDelete) === TRUE) {
                echo "<script>
                    alert('✅ Backup generado correctamente. Se eliminó el respaldo más antiguo.');
                    window.location.href = document.referrer;
                </script>";
            } else {
                echo "<script>
                    alert('❌ Error al eliminar el registro de la BD: " . addslashes($conexion->error) . "');
                    window.location.href = document.referrer;
                </script>";
            }
        }
    } else {
        echo "<script>
            alert('✅ Backup generado correctamente.');
            window.location.href = document.referrer;
        </script>";
    }
} else {
    echo "<script>
        alert('❌ Error al generar el backup.');
        console.log(" . json_encode($output) . ");
        window.location.href = document.referrer;
    </script>";
}
?>
