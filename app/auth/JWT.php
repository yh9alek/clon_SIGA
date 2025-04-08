<?php

namespace app\auth;

use app\config\Server;
use Firebase\JWT\JWT as _JWT;
use Firebase\JWT\Key;

use \Exception;
use \stdClass;

class JWT {

    private static string $algorithm = 'HS512';

    /**
     * Obtener la clave de JWT de las variables de entorno.
     */
    private static function getSecretKey(): string {
        return $_ENV['JWT_SECRET_KEY'];
    }

    /**
     * Genera un token JWT
     * @param array $payload Datos a incluir en el token
     * @return string|false  Token generado o false en caso de error
     */
    public static function generateToken(array $payload): string|false {
        try {

            $token = _JWT::encode($payload, self::getSecretKey(), self::$algorithm);

            # Establecer la cookie segura con el token
            setcookie(
                'jwt_token',      # Nombre de la cookie
                $token,           # Token JWT
                [
                    'expires'  => time() + 3600,            # Expira en 1 hora
                    'path'     => '/',                      # Disponible en todo el sitio
                    'httponly' => true,                     # Inaccesible por JS
                    'secure'   => isset($_SERVER['HTTPS']), # Solo en HTTPS
                    'samesite' => 'Strict'                  # Previene ataques CSRF
                ]
            );

            return $token;
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH . 'jwt_error_logs.txt',
                "JWT ENCODE ERROR (" . date('d/m/Y h:i A') . "): $e \n" . PHP_EOL,
                FILE_APPEND
            );
            return false;
        }
    }

    /**
     * Valida un token JWT
     * @return stdClass|false  Datos decodificados o false si el token no es válido
     */
    public static function validateToken(): stdClass|false {
        if (!isset($_COOKIE['jwt_token']))
            return false; # No hay token en la cookie
    
        try {
            return _JWT::decode(
                $_COOKIE['jwt_token'], 
                new Key(
                    self::getSecretKey(), 
                    self::$algorithm
                )
            );
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH . 'jwt_error_logs.txt',
                "JWT DECODE ERROR (" . date('d/m/Y h:i A') . "): $e \n" . PHP_EOL,
                FILE_APPEND
            );
            return false;
        }
    }

    public static function logout(): void {
        setcookie('jwt_token', '', [
            'expires'  => time() - 3600, # Expirar cookie
            'path'     => '/',
            'httponly' => true,
            'secure'   => isset($_SERVER['HTTPS']),
            'samesite' => 'Strict'
        ]);
    }
}