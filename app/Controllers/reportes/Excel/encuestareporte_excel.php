<?php
session_start();
require_once('../../../../database/conexion.php');  
require_once('../../../../vendor/autoload.php'); 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    $conexion->set_charset("utf8");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estudiante'])) {
        $estudiante_id = intval($_POST['estudiante']);
        
        $query = $conexion->prepare("
            SELECT 
                e.matricula, 
                CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre_completo,
                g.nomenclatura AS grupo, 
                u.email,
                s.descripcion AS seccion_nombre,
                p.pregunta,
                r.respuesta
            FROM estudiantes AS e
            INNER JOIN usuarios AS u ON e.usuario_id = u.id
            INNER JOIN estudiante_grupo AS eg ON eg.estudiante_id = e.id
            INNER JOIN t_grupos AS g ON g.id = eg.grupo_id
            LEFT JOIN preguntas AS p ON p.seccion_id IN (SELECT id FROM secciones)
            LEFT JOIN secciones AS s ON s.id = p.seccion_id
            LEFT JOIN respuestas AS r ON r.pregunta_id = p.id AND r.estudiante_id = e.id
            WHERE e.id = ?
            ORDER BY s.id ASC, p.id ASC;
        ");
        $query->bind_param("i", $estudiante_id);
        $query->execute();
        $resultado = $query->get_result();

        if ($resultado->num_rows > 0) {
            $primera_fila = $resultado->fetch_assoc();
            $resultado->data_seek(0);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Estilos para encabezados
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ];
            $borderStyle = [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
            ];

                    // Título principal
            $sheet->setCellValue('A1', 'Reporte de Estudiante');
            $sheet->mergeCells('A1:C1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => 'FFFFFF'], // Color de la fuente (blanco)
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2f7e68'], // Color de fondo (azul)
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centrado
                ]
            ]);



            // Título Datos Generales
              $sheet->setCellValue('A3', 'Datos Generales');
              $sheet->mergeCells('A3:B3');
              $sheet->getStyle('A3')->applyFromArray(['font' => ['bold' => true, 'size' => 14]]);
            // Datos generales
            $sheet->setCellValue('A4', 'Matrícula:');
            $sheet->setCellValue('B4', $primera_fila['matricula']);
            $sheet->setCellValue('A5', 'Nombre:');
            $sheet->setCellValue('B5', $primera_fila['nombre_completo']);
            $sheet->setCellValue('A6', 'Grupo:');
            $sheet->setCellValue('B6', $primera_fila['grupo']);
            $sheet->setCellValue('A7', 'Correo:');
            $sheet->setCellValue('B7', $primera_fila['email']);
            // Aplicar bordes a toda la tabla
            $sheet->getStyle("A3:B7")->applyFromArray($borderStyle);
         

            // Ajuste de tamaño específico para las columnas
            $sheet->getColumnDimension('A')->setWidth(20); // Columna A
            $sheet->getColumnDimension('B')->setWidth(41); // Columna B

            // Ajuste de tamaño específico para las filas
            $sheet->getRowDimension(3)->setRowHeight(25); // Fila 3
            $sheet->getRowDimension(4)->setRowHeight(20); // Fila 4
            $sheet->getRowDimension(5)->setRowHeight(20); // Fila 5
            $sheet->getRowDimension(6)->setRowHeight(20); // Fila 6
            $sheet->getRowDimension(7)->setRowHeight(20); // Fila 7
            




            // Encabezados de tabla
            $sheet->setCellValue('A10', '');
            $sheet->setCellValue('B10', 'Pregunta');
            $sheet->setCellValue('C10', 'Respuesta');
            $sheet->getStyle('A10:C10')->applyFromArray($headerStyle);

            // Llenado de datos
            $fila_excel = 11;
            $seccion_actual = '';
            while ($row = $resultado->fetch_assoc()) {
                if ($row['seccion_nombre'] !== $seccion_actual) {
                    // Agregar nombre de la sección como encabezado de grupo
                    $sheet->setCellValue('A' . $fila_excel, strtoupper($row['seccion_nombre']));
                    $sheet->mergeCells("A{$fila_excel}:C{$fila_excel}");
                    $sheet->getStyle("A{$fila_excel}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ]);
                    $fila_excel++;
                    $seccion_actual = $row['seccion_nombre'];
                }

                // Agregar pregunta y respuesta
                $sheet->setCellValue('A' . $fila_excel, '');
                $sheet->setCellValue('B' . $fila_excel, $row['pregunta']);
                $sheet->setCellValue('C' . $fila_excel, $row['respuesta'] ?: 'No respondida');
                $fila_excel++;
            }

            // Aplicar bordes a toda la tabla
            $sheet->getStyle("A9:C{$fila_excel}")->applyFromArray($borderStyle);

            // Autoajustar columnas
            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }






            // Limpiar buffer antes de enviar encabezados
            if (ob_get_length()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Estudiante.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } else {
            echo 'No se encontró información para el estudiante seleccionado.';
        }
    } else {
        echo 'No se recibió la información necesaria.';
    }
} catch (Exception $e) {
    echo 'Error al generar el Excel: ' . $e->getMessage();
}
