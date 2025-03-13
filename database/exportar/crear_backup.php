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


?>