<?php
class Database {
    private static $host = 'localhost';
    private static $dbname = 'santafe';
    private static $username = 'root';
    private static $password = 'root';
    private static $connection = 'null';

    public static function connect() {
        if (self::$connection === null) {
            try {
                error_log("Intentando establecer la conexión a base de datos...");

                self::$connection = new mysqli(self::$host, self::$username, self::$password, self::$dbname);

                if (self::$connection->connect_error) {
                    error_log("Error de conexión MySQL: " . self::$connection->connect_error);
                    throw new Exception("Error de conexión a base de datos: " . self::$connection->connect_error);

                    if (!self::$connection->set_charset("utf8")) {
                        error_log("Error, estableciendo charset UTF-8: " . self::$connection->error);
                    }

                    error_log("Conexión a base de datos exitosa.");

                } catch (Exception $e) {
                    error_log("Database connection error: " . $e.getMessage());
                    throw new Exception("Error de conexión a base de datos: " . $e->getMessage());
                }
            }

            return self::$connection;
        }

        public static function testConnection() {
            try {
                $conn = self::connect();
                $result = $conn->query("SELECT 1 as test");
                if ($result) {
                    return ['success' => true, 'message' => 'Conexión exitosa'];
                } else {
                    return ['success' => false, 'message' => 'Error en query de pruebas: ' . $conn->error];
                }
            } catch(Exception $e) {
                return ['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()];
            }
        }
    }
}

if (basename($_SERVER['PHP_SELF']) == 'db.php') {
    header('Content-Type: application/json');
    echo json_encode(Database::testConnection());
}
?>
