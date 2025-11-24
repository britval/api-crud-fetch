<?php
require_once "conexion.php";

class Productos {
    private $pdo;
    public  $Codigo;
    public  $Producto;
    public  $Precio;
    public  $Cantidad;
    public  $errors = [];

    public function __construct(DB $db) {
        $this->pdo = $db;
    }

    public function setDesdeArray($data) {
        $this->Codigo   = trim($data['Codigo']   ?? '');
        $this->Producto = trim($data['Producto'] ?? '');
        $this->Precio   = trim($data['Precio']   ?? '');
        $this->Cantidad = trim($data['Cantidad'] ?? '');
    }

    public function validar() {
        $this->errors = [];

        if ($this->Codigo === '')   $this->errors['Codigo']   = "Código requerido";
        if ($this->Producto === '') $this->errors['Producto'] = "Producto requerido";
        if ($this->Precio === '' || !is_numeric($this->Precio)) 
            $this->errors['Precio'] = "Precio inválido";
        if ($this->Cantidad === '' || !ctype_digit($this->Cantidad)) 
            $this->errors['Cantidad'] = "Cantidad inválida";

        return empty($this->errors);
    }

    public function guardarProductos() {
        $data = array(
            "codigo"   => $this->Codigo,
            "producto" => $this->Producto,
            "precio"   => $this->Precio,
            "cantidad" => $this->Cantidad
        );

        $this->pdo->insertSeguro("productos", $data);
    }

    public function actualizarProductos($id) {
        $data = array(
            "codigo"   => $this->Codigo,
            "producto" => $this->Producto,
            "precio"   => $this->Precio,
            "cantidad" => $this->Cantidad
        );

        return $this->pdo->updateSeguro("productos", $data, "id = " . intval($id));
    }

    public function listarProductos() {
        return $this->pdo->Arreglos("SELECT * FROM productos ORDER BY id DESC");
    }
}

