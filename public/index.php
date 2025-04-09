<?php  # Punto de entrada del servidor

require_once __DIR__.'/../vendor/autoload.php';

# --------------- Cargar variables de entorno ---------------

use Dotenv\Dotenv as ENV;
ENV::createImmutable(dirname(__DIR__))->load();

# -----------------------------------------------------------

use app\Router;
use app\controllers\LoginController;

$router = new Router;

$router->get('/login',  [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);

$router->resolve();