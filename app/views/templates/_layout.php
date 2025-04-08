<?php use app\helpers\Asset; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test Layout</title>
        <link rel="stylesheet" href="/assets/css/_layout.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="<?= Asset::getSource($_SERVER['REQUEST_URI'], 'css') ?>">
    </head>
    <body>

        <?php require_once __DIR__.'/../templates/components/_layout/topbar.php'  ?>
        <?php require_once __DIR__.'/../templates/components/_layout/sidebar.php' ?>

        <main id="module">
            <?= $module # El módulo en memoria se renderiza aquí ?>
        </main>

        <?php require_once __DIR__.'/../templates/components/_layout/footer.php'  ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script type="module" src="<?= Asset::getSource($_SERVER['REQUEST_URI'], 'js') ?>"></script>
    </body>
</html>