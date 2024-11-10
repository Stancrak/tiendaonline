<?php

namespace App\Controllers;

use App\Dao\PedidosDao; // Asume que crearás este DAO
use App\Controllers\LoginController;
use App\Models\PedidosModel; // Asume que crearás este Model
use Exception;
use PDO;
use PDOException;

require_once __DIR__ . '/../Login/LoginControlller.php';
require_once __DIR__ . '/../../Dao/Administrador/PedidosDao.php';
require_once __DIR__ . '/../../Models/EntityModels/PedidosModel.php';

class PedidosController
{
    private $loginController;
    private $pedidosModel;
    private $db;

    public function __construct($conexion)
    {
        $this->loginController = new LoginController($conexion);
        $dao = new PedidosDao($conexion);
        $this->pedidosModel = new PedidosModel($dao);
        $this->db = $conexion;
    }

    public function index()
    {
        $this->loginController->verificarAcceso(1);

        $data = [
            'proveedores' => $this->pedidosModel->obtenerProveedores(),
            'pedidosLista' => $this->pedidosModel->obtenerPedidos(),
            'estadosPedido' => $this->pedidosModel->obtenerEstadosPedido(),
            'prioridades' => $this->pedidosModel->obtenerPrioridades(),
            'metodosPago' => $this->pedidosModel->obtenerMetodosPago()
        ];

        // Asegúrate de que estas variables estén disponibles en la vista
        extract($data);

        $content = __DIR__ . '/../../Views/Administrador/Productos/pedidos.php';
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function agregar()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $pedido = $this->limpiarDatos($datos);
        $pedido['idPedido'] = $this->generadorId();

        // Validar datos del pedido
        if (empty($pedido['idProveedor']) || empty($pedido['fechaPedido'])) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }

        // Generar ID de pedido
        $pedido['idPedido'] = $this->generadorId();

        // Agregar el pedido
        $resultado = $this->pedidosModel->agregarPedido($pedido);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Pedido agregado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo agregar el pedido']);
        }
    }

    public function modificarPedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $pedido = $this->limpiarDatos($datos);

        $resultado = $this->pedidosModel->modificarPedido($pedido);

        echo json_encode($resultado ?
            ['success' => true, 'message' => 'Pedido modificado correctamente'] :
            ['error' => 'No se pudo modificar el pedido']);
    }

    public function eliminarPedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idPedido = $this->limpiarDatos($datos['idPedido']);

        $resultado = $this->pedidosModel->eliminarPedido($idPedido);

        echo json_encode($resultado ?
            ['success' => true, 'message' => 'Pedido eliminado correctamente'] :
            ['error' => 'No se pudo eliminar el pedido']);
    }

    private function limpiarDatos($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'limpiarDatos'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public function generadorId($longitud = 5)
    {
        $numero = substr(str_shuffle(str_repeat('0123456789', $longitud)), 0, $longitud);
        return 'ped' . $numero;
    }

    // Métodos adicionales para obtener datos específicos
    public function obtenerProveedores()
    {
        echo json_encode($this->pedidosModel->obtenerProveedores());
    }

    public function obtenerEstadisticas()
    {
        echo json_encode($this->pedidosModel->obtenerEstadisticasPedidos());
    }

    public function obtenerPedidos()
    {
        echo json_encode($this->pedidosModel->obtenerPedidos());
    }

    public function obtenerPedidoPorId()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idPedido = $this->limpiarDatos($datos['idPedido']);
        echo json_encode($this->pedidosModel->obtenerPedidoPorId($idPedido));
    }

    //Detalle

    public function obtenerPedidosPorProveedor()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idProveedor = $this->limpiarDatos($datos['idProveedor']);
        echo json_encode($this->pedidosModel->obtenerPedidosPorProveedor($idProveedor));
    }

    public function obtenerDetallePedidoPorId()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idDetallePedido = $this->limpiarDatos($datos['idDetallePedido']);

        // Llamar al modelo para obtener el detalle del pedido
        $detallePedido = $this->pedidosModel->obtenerDetallePedidoPorId($idDetallePedido);

        // Devolver el resultado en formato JSON
        echo json_encode($detallePedido ? $detallePedido : ['error' => 'Detalle de pedido no encontrado']);
    }

    public function obtenerProductos()
    {
        echo json_encode($this->pedidosModel->obtenerProductos());
    }

    public function obtenerProductosPorProveedor()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idProveedor = $this->limpiarDatos($datos['idProveedor']);

        // Verifica que el idProveedor no esté vacío
        if (empty($idProveedor)) {
            echo json_encode(['error' => 'ID del proveedor no válido']);
            return;
        }

        $productos = $this->pedidosModel->obtenerProductosPorProveedor($idProveedor);
        echo json_encode($productos ?: ['error' => 'No se encontraron productos']);
    }

    public function verificarExistenciaPedido($idPedido)
    {
        $pedido = $this->pedidosModel->obtenerPedidoPorId($idPedido);
        return $pedido ? true : false;
    }

    public function obtenerDetallesPedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idPedido = $this->limpiarDatos($datos['idPedido']);
        echo json_encode($this->pedidosModel->obtenerDetallesPedido($idPedido));
    }

    // PedidosController.php
    public function obtenerDetallePorId()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idDetallePedido = $this->limpiarDatos($datos['idDetallePedido']);

        // Llamar al modelo para obtener el detalle del pedido
        $detallePedido = $this->pedidosModel->obtenerDetallePorId($idDetallePedido);

        // Devolver el resultado en formato JSON
        echo json_encode($detallePedido ? $detallePedido : ['error' => 'Detalle de pedido no encontrado']);
    }

    public function agregarDetallePedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $detalle = $this->limpiarDatos($datos);

        // Validaciones
        if (
            empty($detalle['idPedido']) ||
            empty($detalle['idProducto']) ||
            !isset($detalle['cantidad']) ||
            !isset($detalle['precio']) ||
            $detalle['cantidad'] <= 0 ||
            $detalle['precio'] < 0
        ) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos o inválidos']);
            return;
        }

        // Verificar existencia de idPedido
        if (!$this->pedidosModel->verificarExistenciaPedido($detalle['idPedido'])) {
            echo json_encode(['success' => false, 'error' => 'El idPedido no existe']);
            return;
        }

        // Verificar existencia de idProducto
        if (!$this->pedidosModel->verificarExistenciaProducto($detalle['idProducto'])) {
            echo json_encode(['success' => false, 'error' => 'El idProducto no existe']);
            return;
        }

        // Intentar agregar el detalle del pedido
        try {
            $resultado = $this->pedidosModel->agregarDetallePedido($datos);
            echo json_encode(['success' => true, 'message' => 'Detalle agregado exitosamente']);
        } catch (Exception $e) {
            // Capturar errores de la base de datos o cualquier otro error
            echo json_encode(['success' => false, 'error' => 'Ocurrió un error al agregar el detalle: ' . $e->getMessage()]);
        }
    }

    public function modificarDetallePedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $detalle = $this->limpiarDatos($datos);

        $resultado = $this->pedidosModel->modificarDetallePedido($detalle);

        echo json_encode(
            $resultado ?
                ['success' => true, 'message' => 'Detalle de pedido modificado correctamente'] :
                ['success' => false, 'error' => 'No se pudo modificar el detalle del pedido. Verifique los datos.']
        );
    }

    public function eliminarDetallePedido()
    {
        $datos = json_decode(file_get_contents('php://input'), true);
        $idDetallePedido = $this->limpiarDatos($datos['idDetallePedido']);

        $resultado = $this->pedidosModel->eliminarDetallePedido($idDetallePedido);

        echo json_encode(
            $resultado ?
                ['success' => true, 'message' => 'Detalle de pedido eliminado correctamente'] :
                ['success' => false, 'error' => 'No se pudo eliminar el detalle del pedido. Verifique los datos.']
        );
    }
}
