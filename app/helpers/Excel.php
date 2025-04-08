<?php

namespace app\helpers;

use app\config\Server;
use Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Clase para manejo de Excel
 */
class Excel {
    
    private Spreadsheet $spreadsheet;
    private $sheet;

    public function __construct() {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet       = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Agrega datos a la hoja de cálculo.
     * @param array $data Matriz de datos (filas y columnas).
     */
    public function setData(array $data): void {
        try {
            foreach ($data as $rowIndex => $row) {
                foreach ($row as $colIndex => $cellValue) {
                    $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $cell = $columnLetter . ($rowIndex + 1);
                    $this->sheet->setCellValue($cell, $cellValue);
                }
            }
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Aplica estilos a una celda, columna o fila.
     * @param string $target  Celda o rango (ej: 'A1', 'B2:C5', '3', 'D').
     * @param array  $styles  Configuración de estilos.
     */
    public function setStyle(string $target, array $styles): void {
        try {
            $styleArray = [];

            if (isset($styles['fontColor']))
                $styleArray['font']['color'] = ['rgb' => $styles['fontColor']];

            if (isset($styles['fontSize']))
                $styleArray['font']['size'] = $styles['fontSize'];

            if (isset($styles['bold']))
                $styleArray['font']['bold'] = $styles['bold'];

            if (isset($styles['italic']))
                $styleArray['font']['italic'] = $styles['italic'];

            if (isset($styles['underline']))
                $styleArray['font']['underline'] = Font::UNDERLINE_SINGLE;

            if (isset($styles['bgColor']))
                $styleArray['fill'] = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $styles['bgColor']]
                ];

            if (isset($styles['align']))
                $styleArray['alignment']['horizontal'] = $styles['align'];

            if (isset($styles['borders']))
                $styleArray['borders'] = [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, 
                        'color' => ['rgb' => $styles['borders']]
                    ]
                ];

            if (!empty($styleArray))
                $this->sheet->getStyle($target)->applyFromArray($styleArray);

            if (ctype_alpha($target))
                $this->sheet->getColumnDimension($target)->setAutoSize(true);

            if (ctype_digit($target))
                $this->sheet->getRowDimension($target)->setRowHeight($styles['rowHeight'] ?? -1);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Guarda el archivo en el servidor.
     * @param string $filename Nombre del archivo (ejemplo.xlsx o ejemplo.csv).
     */
    public function saveToFile(string $filename): void {
        try {
            $writer = $this->getWriter($filename);
            $writer->save($filename);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Descarga el archivo al usuario.
     * @param string $filename Nombre del archivo.
     */
    public function download(string $filename): void {
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            $writer = $this->getWriter($filename);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Lee un archivo de Excel y lo devuelve en un array.
     * @param string $filepath Ruta del archivo Excel.
     * @return array           Datos en formato de matriz.
     */
    public static function readExcel(string $filepath): array {
        try {
            if (!file_exists($filepath))
                throw new \Exception("El archivo no existe: " . $filepath);

            $spreadsheet = IOFactory::load($filepath);
            $sheet       = $spreadsheet->getActiveSheet();
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $sheet->toArray();
    }

    /**
     * Obtiene el escritor adecuado según la extensión.
     * @param string  $filename Nombre del archivo.
     * @return object           Writer (Xlsx o Csv).
     */
    private function getWriter(string $filename) {
        try {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            return ($extension === 'csv') ? new Csv($this->spreadsheet) 
                                          : new Xlsx($this->spreadsheet);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'excel_error_logs.txt',
                "EXCEL ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }
}
