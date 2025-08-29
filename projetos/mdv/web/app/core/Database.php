<?php
class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                // Banco SQLite local na pasta database/
                $dbPath = __DIR__ . '/../../database/mvp.db';
                
                // Cria a pasta database se não existir
                if (!file_exists(dirname($dbPath))) {
                    mkdir(dirname($dbPath), 0777, true);
                }

                self::$instance = new PDO('sqlite:' . $dbPath);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
