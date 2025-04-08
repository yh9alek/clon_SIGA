<?php

namespace app\helpers;

use app\config\Server;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

/**
 * Clase para generar PDFs
 */
class PDF {
    
    private static function getDompdf(): ?Dompdf {
        try {
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            return new Dompdf($options);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'pdf_error_logs.txt',
                "PDF ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
            return null;
        }
    }

    /**
     * Genera un PDF a partir de HTML y CSS
     *
     * @param string $html         Contenido HTML del PDF
     * @param string $filename     Nombre del archivo PDF
     * @param string $orientation  Orientación del PDF (portrait o landscape)
     * @param string $size         Tamaño del papel (A4, Letter, etc.)
     * @param bool   $download     Si es true, descarga el PDF; si es false, lo muestra en el navegador
     */
    public static function create(
        string $html,
        string $filename    = 'documento.pdf',
        string $orientation = 'portrait',
        string $size        = 'A4',
        bool $download      = false
    ) {
        try {
            $dompdf = self::getDompdf();
            if (!$dompdf)
                throw new Exception('No se pudo inicializar Dompdf');

            $dompdf->loadHtml($html);
            $dompdf->setPaper($size, $orientation);
            $dompdf->render();
    
            if ($download)
                $dompdf->stream($filename, ["Attachment" => true]);
            else {
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                echo $dompdf->output();
            }
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'pdf_error_logs.txt',
                "PDF ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Guarda el PDF en el servidor
     *
     * @param string $html         Contenido HTML del PDF
     * @param string $path         Ruta donde se guardará el PDF
     * @param string $orientation  Orientación del PDF
     * @param string $size         Tamaño del papel
     * @return bool                Devuelve true si el archivo se guardó correctamente
     */
    public static function save(
        string $html,
        string $path,
        string $orientation = 'portrait',
        string $size        = 'A4'
    ): bool {
        try {

            $dompdf = self::getDompdf();
            if (!$dompdf)
                throw new Exception('No se pudo inicializar Dompdf');

            $dompdf->loadHtml($html);
            $dompdf->setPaper($size, $orientation);
            $dompdf->render();

            if (file_put_contents($path, $dompdf->output()) === false)
                throw new Exception('No se pudo guardar el archivo en ' . $path);

            return true;
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'pdf_error_logs.txt',
                "PDF ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
            return false;
        }
    }
}

# Ejemplo: <a href="generar_pdf.php" target="_blank">Abrir PDF</a>