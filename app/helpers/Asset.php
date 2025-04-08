<?php

namespace app\helpers;

use app\config\Server;
use app\helpers\Util;

/**
 * Clase usada para manipulación de recursos estáticos y de servidor.
 */
class Asset {

    /**
     * Obtener un recurso estático de manera dinámica según el módulo actual.
     * (Usar solo en templates)
     * @param string $path*  Módulo actual.
     * @param string $type*  Extensión del asset (.css, .js, .etc).
     * @return string
     */
    public static function getSource(string $path, string $type): string {
        return "/assets/$type/modules/".ltrim($path, '/').'.'.$type;
    }

    /**
     * Guardar una imagen subida por el usuario en el servidor.
     * @param array  $imageFile*  Array con datos de la imagen subida desde un formulario (POST).
     * @param string $imagePath   Cadena que contiene una ruta de imagen existente (solo para actualizar imagen).
     * @return void
     */
    public static function saveImage(array $imageFile, string $imagePath = ''): void {

        # Verificar la existencia del directorio de imagenes en el servidor
        if(!is_dir(Server::IMAGES_PATH)) {
            mkdir(Server::IMAGES_PATH);
        }

        # Validar el contenido de la imagen subida mediante POST
        if($imageFile && $imageFile['tmp_name']) {

            # Eliminar imagen y su directorio (actualización de imagen)
            if($imagePath)
                self::deleteImage($imagePath);

            # Generar un nuevo path para la nueva imagen
            $imagePath = '/'.Util::getRandomString(12).'/'.$imageFile['name'];

            # Crear un nuevo directorio para la imagen
            mkdir(
                dirname(Server::IMAGES_PATH.$imagePath)
            );

            # Mover la imagen al directorio creado
            move_uploaded_file($imageFile['tmp_name'] , Server::IMAGES_PATH.$imagePath);

        }
    }

    /**
     * Eliminar una imagen guardada en el servidor.
     * @param string $imagePath*  Ruta de la imagen a elminiar.
     * @return void
     */
    public static function deleteImage(string $imagePath): void {

        # Eliminar la imagen y su directorio respectivo
        unlink(Server::IMAGES_PATH.$imagePath);
        rmdir(
            dirname(Server::IMAGES_PATH.$imagePath)
        );

    }
}