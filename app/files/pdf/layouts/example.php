<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporte PDF</title>
        <style>
            <?= file_get_contents(__DIR__.'/example.css') ?>
        </style>
    </head>
    <body>

        <div class="container">
            <!-- ENCABEZADO -->
            <div class="header">
                <img src="https://via.placeholder.com/80" alt="Logo">
                <h1>Reporte de Actividades</h1>
            </div>

            <!-- INFORMACIÓN GENERAL -->
            <div class="info">
                <p><strong>Fecha:</strong> <?= date("d/m/Y") ?></p>
                <p><strong>Generado por:</strong> Sistema de Administración</p>
            </div>

            <!-- TABLA DE DATOS -->
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>Actividad completada con éxito.</td>
                    <td>25/03/2025</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María López</td>
                    <td>Se inició una nueva tarea.</td>
                    <td>26/03/2025</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos Gómez</td>
                    <td>Revisión de documentos pendiente.</td>
                    <td>27/03/2025</td>
                </tr>
            </table>

            <!-- PIE DE PÁGINA -->
            <div class="footer">
                <p>Este es un reporte generado automáticamente.</p>
            </div>
        </div>

    </body>
</html>