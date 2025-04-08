<?php

namespace app\controllers;
use app\helpers\View;

class TestController {

    public static function test() {
        # Definir el Estado de la vista (Ejemplo)
        $products = [];
        $user     = ['name' => 'Yohan Alek'];

        # Ejecutar Lógica GET o POST (Fetch hacia BD, validaciones previas, etc.)
        $products = [
            [ 'name' => 'apple', 'img'  => '🍎' ],
            [ 'name' => 'grape', 'img'  => '🍇' ],
            [ 'name' => 'coconut', 'img'  => '🥥' ]
        ];

        # Renderizar la vista
        View::render('test', [
            'user'     => $user,
            'products' => $products,
        ]);
    }
}