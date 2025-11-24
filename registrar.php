<?php
require_once "Modelo/conexion.php";
require_once "Modelo/Productos.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();
$myProducto = new Productos($db);

$accion = $_POST['Accion'] ?? '';
$response = [
    "success" => false,
    "message" => "Acción inválida",
    "accion"  => $accion,
    "data"    => [],
    "errors"  => []
];

switch ($accion) {
    case "Guardar":
        $myProducto->setDesdeArray($_POST);

        if (!$myProducto->validar()) {
            $response["errors"]  = $myProducto->errors;
            $response["message"] = "Errores de validación";
            break;
        }

        $myProducto->guardarProductos();
        $response["success"] = true;
        $response["message"] = "Producto guardado";
        $response["accion"]  = "Guardar";
        break;

    case "Modificar":
        $id = $_POST['id'] ?? 0;
        $myProducto->setDesdeArray($_POST);

        if (!$myProducto->validar()) {
            $response["errors"]  = $myProducto->errors;
            $response["message"] = "Errores de validación";
            break;
        }

        $myProducto->actualizarProductos($id);
        $response["success"] = true;
        $response["message"] = "Producto actualizado";
        $response["accion"]  = "Modificar";
        break;

    case "Listar":
        $lista = $myProducto->listarProductos();
        $response["success"] = true;
        $response["message"] = "Listado de productos";
        $response["accion"]  = "Listar";
        $response["data"]    = $lista;
        break;
}

echo json_encode($response);

