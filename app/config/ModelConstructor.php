<?php

namespace app\config;

/**
 * Trait para el constructor de los modelos
 */
trait ModelConstructor {
    public function __construct() {
        $this->db = new SQL(
            basename(self::class)
        );
    }
}