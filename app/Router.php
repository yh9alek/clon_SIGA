<?php

namespace app;

/**
 * Clase usada para validar las peticiones del usuario hacia el servidor,
 * llama al controlador solicitado según la petición.
 */
class Router {

    private array $getRoutes  = [];
    private array $postRoutes = [];

    /**
     * Método para añadir una ruta a validar por el servidor
     * cuando el usuario envíe una petición GET.
     * @param string $url*  URL solicitada. Ejemplo: '/login'
     * @param array  $fn*   Array que contiene el controlador y el método a ejecutar [Controller::class, 'method_name']
     * @return void
     */
    public function get(string $url, array $fn): void {
        $this->getRoutes[$url] = $fn;
    }

    /**
     * Método para añadir una ruta a validar por el servidor
     * cuando el usuario envíe una petición POST.
     * @param string $url*  URL solicitada. Ejemplo: '/register'
     * @param array  $fn*   Array que contiene el controlador y el método a ejecutar [Controller::class, 'method_name']
     * @return void
     */
    public function post(string $url, array $fn): void {
        $this->postRoutes[$url] = $fn;
    }

    /**
     * Método para validar que exista la ruta solicitada por el usuario en el servidor.
     * Si existe, ejecuta el controlador correspondiente.
     * [Revisa las rutas previamente añadidas con ->get() o ->post()].
     * @return void
     */
    public function resolve(): void {
        # Obtenemos la URL que se solicitó y el método HTTP utilizado
        $currentUrl = $_SERVER['REQUEST_URI']    ?? '/';
        $method     = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        # Obtenemos el array con el controlador y su método a ejecutar -  $fn = [Controlador, Método] ?? null
        $fn = $method === 'GET' ? $this->getRoutes[$currentUrl]  ?? null:
                                  $this->postRoutes[$currentUrl] ?? null;
        
        # Ejecutar dinámicamente el método de un controlador con base en el array configurado previamente
        if($fn) call_user_func($fn);
        else {
            echo 'Page not found';
            exit;
        }
    }
}