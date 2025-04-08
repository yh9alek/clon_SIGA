<?php

namespace app\helpers;

/**
 * Clase usada para renderización de vistas.
 */
class View {
    /**
     * Renderizar una vista y datos obtenidos por el backend.
     * @param string $view*  Nombre del módulo a renderizar.
     * @param array  $data   Datos a renderizar en el módulo.
     * @return void
     */
    public static function render(string $view, array $data = []): void {

        # Por cada item creamos una variable de forma dinámica
        if(!empty($data))
            foreach($data as $key => $value)
                $$key = $value;
        
        # Guardar el HTML del módulo a renderizar en memoria
        ob_start();
        include_once __DIR__."/../views/modules/$view.php";
        $module = ob_get_clean();
        
        # Renderizar el template a usar
        # Internamente renderizamos el módulo en cuestión                     
        include_once __DIR__.'/../views/templates/_layout.php';
    }
}