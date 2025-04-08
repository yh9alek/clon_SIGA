<?php  # Punto de entrada del servidor

require_once __DIR__.'/../vendor/autoload.php';

use app\config\Server;

use app\config\SQL;
use app\helpers\MailService;
use app\helpers\Excel;
use app\helpers\PDF;

# PRUEBA EJECUTAR CONSULTAS SQL

// $pdo = new SQL('pokemon');

// $fields = 'p.pok_name name,
//            p.pok_weight weight';

// $extras = 'WHERE p.pok_weight BETWEEN 300 AND 500';

// die(
//     print_r(
//         $pdo->select(
//             $fields,
//             $extras,
            
//             ORDER_BY: 'name DESC',
//             LIMIT: '3'
//         )
//     )
// );

#  PRUEBA ENVÍO DE EMAILS

// MailService::sendMail(
//     asunto:  'Prueba envío de correo', 
//     message: '<br><span style="font-weight: bold; font-size: 17px;">Estimado Yohan Alek Plazola Arangure.</span><br><br>
//               Espero que este mensaje le encuentre bien. Me dirijo a usted para confirmar la reunión programada para el día <b>16 de Abril</b> a las <b>12:30 p.m.</b> la cual se llevará a cabo en <b>Microsoft Teams</b>.
//               El objetivo de esta reunión será discutir la implementación de una nueva arquitectura para el desarrollo de sistemas, por lo que le agradecería que pudiera revisar previamente la responsiva para optimizar nuestro tiempo.
//               Agradezco de antemano su atención y confirmación de asistencia. Si tiene alguna consulta o requiere hacer ajustes en la fecha o el horario, no dude en comunicármelo.
//               Quedo atento/a a su respuesta.
//               Atentamente,
//               Edgar Garcia Lopez Quiñoz. Gerente de TI.', 
//     para:    [
//         'destinatario1@mail.com',
//     ],
//     cc: ['copiapara@gmail.com']
// );

#  PRUEBA TRABAJO CON EXCEL
// $excel = new Excel;

// // Datos de prueba
// $data = [
//     ['ID', 'Descripción', 'Tipo', 'Estatus'],
//     [1, 'Hola', 'Palabra', 1],
//     [2, 'Mundo', 'Palabra', 1],
//     [3, 'Soy', 'Palabra', 1],
//     [4, 'Yohan', 'Palabra', 0],
// ];

// $excel->setData($data);

// // Aplicar estilos al encabezado
// $excel->setStyle('A1:D1', 
//     [
//         'bgColor' => '1F4E78', 
//         'fontColor' => 'FFFFFF', 
//         'bold' => true, 
//         'align' => 'center'
//     ]);

// // Ajustar ancho de la columna "Descripción"
// $excel->setStyle('B', ['columnWidth' => 20]);

// // Guardar y descargar
// $excel->saveToFile(Server::EXCEL_FILES_PATH.'report.xlsx');
// $excel->download(Server::EXCEL_FILES_PATH.'report.xlsx');

# PRUEBA TRABAJO CON PDFs

// ob_start();
// require_once __DIR__.'/../app/files/pdf/layouts/example.php';
// $html = ob_get_clean();

// PDF::save(
//     $html, 
//     Server::PDF_FILES_PATH.'example.pdf',
// );
