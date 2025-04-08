<?php

namespace app\helpers;

/**
 * Clase que contiene funciones en general
 */
class Util {

    /**
     * Obtener una cadena con caracteres aleatorios.
     * @param int $n*  Longitud que debería tener la cadena a generar (mínimo 8).
     * @return string
     */
    public static function getRandomString(int $n): string {

        if($n <= 8) $n = 8;
        $characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str        = '';

        while($n-- > 0)
            $str .= $characters[
                rand(0, strlen($characters) - 1)
            ];

        return $str;

    }

}