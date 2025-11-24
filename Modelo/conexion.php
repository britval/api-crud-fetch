<?php
class DB {
    private $pdo;

    public function __construct() {
        $host = "localhost";
        $db   = "productosdb";
        $user = "root";        // CAMBIA si tu usuario no es root
        $pass = "12345"; // AQUÍ pones tu contraseña de MySQL
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insertSeguro($tabla, $data) {
        $cols = array_keys($data);
        $sql = "INSERT INTO $tabla (" . implode(",", $cols) . ")
                VALUES (:" . implode(",:", $cols) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function updateSeguro($tabla, $data, $where) {
        $sets = [];
        foreach ($data as $col => $val) {
            $sets[] = "$col = :$col";
        }
        $sql = "UPDATE $tabla SET " . implode(",", $sets) . " WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    public function Arreglos($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

