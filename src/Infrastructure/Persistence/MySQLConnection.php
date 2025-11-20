<?php

namespace Farmadec\Infrastructure\Persistence;

use PDO;
use PDOException;

/**
 * Clase de conexión a MySQL
 */
class MySQLConnection
{
    /** @var PDO|null */
    private static $instance = null;
    
    /**
     * Obtener instancia de conexión (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../Config/database.php';
            
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['database'],
                    $config['charset']
                );
                
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                throw new PDOException('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    /**
     * Cerrar conexión
     */
    public static function closeConnection()
    {
        self::$instance = null;
    }
    
    /**
     * Constructor privado para evitar instanciación directa
     */
    private function __construct() {}
    
    /**
     * Evitar clonación
     */
    private function __clone() {}
}
