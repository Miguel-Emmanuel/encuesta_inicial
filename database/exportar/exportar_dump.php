<?php
require("../../app/Controllers/auth.php"); 
include("../conexion.php");

// 🔹 1. Definir ruta del backup
$backupDir = __DIR__ . "/../backups";
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// 🔹 2. Obtener timestamp
$fecha = date("Y-m-d_H-i-s");
$backupName = "backup_$fecha.sql";
$dumpFile = "$backupDir/$backupName";

// 🔹 3. Normalizar la ruta (Soluciona el problema de los "\" en Windows)
$dumpFile = str_replace("\\", "/", $dumpFile);

// 🔹 4. Definir el comando mysqldump
$mysqldumpPath = '"C:\xampp\mysql\bin\mysqldump.exe"';
$command = "$mysqldumpPath --host=$host --user=$user --password=$pass --routines --events --databases $db > \"$dumpFile\"";

// 🔹 5. Ejecutar el comando
exec($command, $output, $result);

// 🔹 6. Verificar si el backup se creó correctamente
if ($result === 0) {
    echo "✅ Backup generado correctamente: $dumpFile <br>";

    // 🔹 7. Insertar en la base de datos
    $sqlInsert = "INSERT INTO respaldos (nombre, ruta, fecha_creacion) VALUES ('$backupName', '$dumpFile', NOW())";
    $conexion->query($sqlInsert);

    // 🔹 8. Contar los respaldos existentes
    $sqlCount = "SELECT COUNT(*) as total FROM respaldos";
    $resultCount = $conexion->query($sqlCount);
    $rowCount = $resultCount->fetch_assoc();
    $totalRecords = $rowCount['total'];

    // 🔹 9. Si hay más de 3 respaldos, eliminar el más antiguo
    if ($totalRecords > 3) {
        $sqlOldest = "SELECT id, ruta FROM respaldos ORDER BY fecha_creacion ASC LIMIT 1";
        $resultOldest = $conexion->query($sqlOldest);
        $oldestRow = $resultOldest->fetch_assoc();
        
        if ($oldestRow) {
            $oldestFile = str_replace("\\", "/", $oldestRow['ruta']); // Normaliza la ruta
            $oldestId = $oldestRow['id'];

            echo "🔍 Intentando eliminar: $oldestFile <br>";

            // 🔹 10. Verificar si el archivo existe antes de eliminarlo
            if (file_exists($oldestFile)) {
                unlink($oldestFile);
                echo "🗑 Archivo eliminado correctamente: $oldestFile <br>";
            } else {
                echo "⚠️ Error: El archivo no existe en la ruta especificada: $oldestFile <br>";
            }

            // 🔹 11. Eliminar el registro de la base de datos
            $sqlDelete = "DELETE FROM respaldos WHERE id = $oldestId";
            if ($conexion->query($sqlDelete) === TRUE) {
                echo "✅ Registro eliminado de la base de datos. <br>";
            } else {
                echo "❌ Error al eliminar el registro de la BD: " . $conexion->error . "<br>";
            }
        } else {
            echo "⚠️ No se encontró ningún respaldo antiguo en la base de datos. <br>";
        }
    }
} else {
    echo "❌ Error al generar el backup.<br>";
    echo "<pre>" . print_r($output, true) . "</pre>"; // Mostrar error detallado
}
?>
