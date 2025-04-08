<?php declare(strict_types=1);

namespace app\config;

require_once __DIR__.'/../config/dbparams.php';

use \PDO;
use \RuntimeException, \Exception;
use \PDOException;
use stdClass;

/**
 * Clase de conexión SQL.
 ** Implementación actual: MARIADB 🦭
 */
final class SQL
{
    private PDO     $con;
    private string  $table;
    private ?string $primaryKey = null;
    
    public function __construct(string $table) {

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '".date('P')."'",
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ];

        try {

            $this->con = new PDO(
                'mysql:host=' . DB_HOST . '; port=' .DB_PORT. '; dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                $options
            );

            $this->table = $table;
            $this->setPrimaryKey();

        } catch (PDOException $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "CONNECTION ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Método para obtener la conexión actual.
     * @return PDO  Conexión de PDO
     */
    public function getConnection(): PDO {
        return $this->con;
    }

    /**
     * Método para obtener el nombre del campo PRIMARY KEY del modelo.
     */
    private function setPrimaryKey(): void {

        $stmt = $this->con->query("SHOW COLUMNS FROM {$this->table}");

        while ($row = $stmt->fetch())
            if ($row['Key'] === 'PRI') {
                $this->primaryKey = $row['Field'];
                break;
            }
    }

    /**
     * Método para verificar la existencia del campo PRIMARY KEY del modelo.
     * @param $type Nombre de la operación (SELECT, INSERT, UPDATE, DELETE).
     */
    private function checkPrimaryKey(string $type): void {
        try {
            if (!$this->primaryKey)
                throw new RuntimeException("No se encontró clave primaria para {$this->table}");
        } catch (RuntimeException $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "$type ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }
    }

    /**
     * Ejecutar un SELECT personalizado.
     * @param string $fields  Campos deseados.
     * @param string $extras  WHERE, JOINS, condicionales, etc.
     * @param string $ORDER_BY   ORDER BY 'campo' (ASC | DESC).
     * @param string $LIMIT   LIMIT N1, N2.
     * @return stdClass       Respuesta del servidor.
     */
    public function select(string $fields = '*', string $extras = '', string $ORDER_BY = '', string $LIMIT = ''): stdClass {

        $response = new stdClass;
        $response->success = false;

        $ORDER_BY = !empty($ORDER_BY) ? "ORDER BY $ORDER_BY" : $ORDER_BY;
        $LIMIT    = !empty($LIMIT)    ? "LIMIT $LIMIT"       : $LIMIT;

        $sql = "SELECT $fields FROM {$this->table} {$this->table[0]} $extras $ORDER_BY $LIMIT;";
        #die($sql);

        try {
            $stmt = $this->con->query($sql);
            $data = $stmt->fetchAll();

            $data = count($data) === 1 ? $data[0]
                                       : $data;
            $response->data    = $data;
            $response->success = true;

        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "SELECT ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $response;
        
    }

    /**
     * Ejecutar un INSERT.
     * @param string $data  Campos a actualizar.
     * @return stdClass     Respuesta del servidor.
     */
    public function insert(array $data): stdClass {

        $response = new stdClass;
        $response->success = false;

        if(empty($data)) return $response;

        $columns = implode(', ', array_keys($data));
        $params  = implode(', ', 
            array_map(
                fn($key) => ":$key", array_keys($data)
            )
        );

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($params);";
        # die($sql);

        try {
            $stmt = $this->con->prepare($sql);
            $response->success = $stmt->execute($data);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "INSERT ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $response;
    }

    /**
     * Ejecutar un UPDATE.
     * @param string $data  Campos a actualizar.
     * @return stdClass     Respuesta del servidor.
     */
    public function update(array $data, int | string $id): stdClass {

        $response = new stdClass;
        $response->success = false;

        if(empty($data)) return $response;

        $this->checkPrimaryKey('UPDATE');

        $fields = implode(', ',
            array_map(
                fn($key) => "$key = :$key", array_keys($data)
            )
        );

        $sql = "UPDATE {$this->table} SET $fields WHERE {$this->primaryKey} = :id;";
        # die($sql);

        try {
            $stmt = $this->con->prepare($sql);
            $response->success = $stmt->execute([
                'id' => $id
            ]);
        } catch (Exception $e) {           
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "UPDATE ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $response;
    }

    /**
     * Ejecutar un DELETE.
     * @param (int | string) $id  ID del registro a eliminar.
     * @return stdClass           Respuesta del servidor.
     */
    public function delete(int | string $id): stdClass {

        $response = new stdClass;
        $response->success = false;

        $this->checkPrimaryKey('DELETE');

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id;";
        # die($sql);

        try {
            $stmt = $this->con->prepare($sql);
            $response->success = $stmt->execute([
                'id' => $id
            ]);
        } catch (Exception $e) {
            file_put_contents(
                Server::ERROR_LOGS_PATH.'db_error_logs.txt',
                "DELETE ERROR (".date('d/m/Y h:i A')."): $e \n" . PHP_EOL, FILE_APPEND
            );
        }

        return $response;
    }
}