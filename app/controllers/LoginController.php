<?php

namespace app\controllers;

use app\config\SQL;
use app\helpers\View;

class LoginController {

    public static function login() {
        # Definir el Estado de la vista (Ejemplo)
        $user  = [];
        $notificaciones = [];
        $theme = 1;

        # Ejecutar Lógica GET o POST (Fetch hacia BD, validaciones previas, etc.)
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login   = $_POST['login']      ?? null;
            $contra  = $_POST['contrasena'] ?? null;

            $usuario = new SQL('ca_usuario')->select(extras: " WHERE login = '$login'");

            // echo '<pre>';
            // die(
            //     print_r($usuario)
            // );
            // echo '</pre>';

            
        }

        # Renderizar la vista
        View::render('login', [
            'user'     => $user,
            'theme'    => $theme,
            'notificaciones' => $notificaciones,
        ]);
    }
}