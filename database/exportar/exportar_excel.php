<?php
session_start();
require_once('../conexion.php');  
require_once('../../vendor/autoload.php'); // Asegúrate de la ruta correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;





// Crear un nuevo archivo Excel
$spreadsheet = new Spreadsheet();

// Obtener todas las tablas de la base de datos
$consultaTablas = $conexion->query("SHOW TABLES");
while ($tabla = $consultaTablas->fetch_array()) {
    $nombreTabla = $tabla[0]; // Nombre de la tabla

    // Crear una nueva hoja con el nombre de la tabla
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle($nombreTabla);

    // Obtener los datos de la tabla
    $consultaDatos = $conexion->query("SELECT * FROM $nombreTabla");
    $columnas = $consultaDatos->fetch_fields();

    // Escribir encabezados
    $columnaIndex = 'A';
    foreach ($columnas as $columna) {
        $sheet->setCellValue($columnaIndex . '1', $columna->name);
        $columnaIndex++;
    }

    // Escribir filas de datos
    $filaIndex = 2;
    while ($fila = $consultaDatos->fetch_assoc()) {
        $columnaIndex = 'A';
        foreach ($fila as $valor) {
            $sheet->setCellValue($columnaIndex . $filaIndex, $valor);
            $columnaIndex++;
        }
        $filaIndex++;
    }
}

// Eliminar la hoja en blanco inicial
$spreadsheet->removeSheetByIndex(0);

// Descargar el archivo Excel
$writer = new Xlsx($spreadsheet);
ob_clean(); // Limpia cualquier salida previa
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="base_de_datos.xlsx"');
$writer->save('php://output');
exit;
?>