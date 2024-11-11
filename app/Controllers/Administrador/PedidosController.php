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

        if ($productos) {
            echo json_encode($productos);
        } else {
            echo json_encode(['error' => 'No se encontraron productos']);
        }
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

        $detalles = $this->pedidosModel->obtenerDetallesPedido($idPedido);

        if ($detalles !== false) {
            echo json_encode($detalles);
        } else {
            echo json_encode([]);
        }
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
        try {
            // Obtener datos del cuerpo de la solicitud
            $datos = json_decode(file_get_contents('php://input'), true);
            $detalle = $this->limpiarDatos($datos);

            // Registro de log para depuración
            error_log('Datos recibidos para agregar detalle: ' . print_r($detalle, true));

            // Validaciones más detalladas
            $validacionResultado = $this->validarDatosDetallePedido($detalle);
            if (!$validacionResultado['success']) {
                error_log('Validación fallida: ' . $validacionResultado['error']);
                echo json_encode($validacionResultado);
                return;
            }

            // Calcular subtotal
            $detalle['subTotal'] = $this->calcularSubtotal(
                $detalle['cantidad'],
                $detalle['precio'],
                $detalle['descuento'] ?? 0
            );

            // Agregar fecha de creación si no existe
            $detalle['fechaCreacion'] = $detalle['fechaCreacion'] ?? date('Y-m-d H:i:s');

            // Intentar agregar el detalle del pedido
            $resultado = $this->pedidosModel->agregarDetallePedido($detalle);

            // Log del resultado
            error_log('Resultado de agregar detalle: ' . print_r($resultado, true));

            echo json_encode($resultado);
        } catch (Exception $e) {
            // Log del error
            error_log('Excepción al agregar detalle: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => 'Ocurrió un error al agregar el detalle: ' . $e->getMessage()
            ]);
        }
    }

    // Método de validación separado
    private function validarDatosDetallePedido($detalle)
    {
        // Validaciones más exhaustivas
        $errores = [];

        if (empty($detalle['idPedido'])) {
            $errores[] = 'ID de Pedido es requerido';
        }

        if (empty($detalle['idProducto'])) {
            $errores[] = 'ID de Producto es requerido';
        }

        if (!isset($detalle['cantidad']) || $detalle['cantidad'] <= 0) {
            $errores[] = 'Cantidad debe ser mayor a 0';
        }

        if (!isset($detalle['precio']) || $detalle['precio'] < 0) {
            $errores[] = 'Precio no puede ser negativo';
        }

        // Verificaciones adicionales
        if (!$this->pedidosModel->verificarExistenciaPedido($detalle['idPedido'])) {
            $errores[] = 'El pedido no existe';
        }

        if (!$this->pedidosModel->verificarExistenciaProducto($detalle['idProducto'])) {
            $errores[] = 'El producto no existe';
        }

        // Verificar stock del producto si es necesario
        // $stockSuficiente = $this->pedidosModel->verificarStockProducto($detalle['idProducto'], $detalle['cantidad']);
        // if (!$stockSuficiente) {
        //     $errores[] = 'Stock insuficiente para el producto';
        // }

        // Retornar resultado de validación
        return $errores ?
            ['success' => false, 'error' => implode(', ', $errores)] :
            ['success' => true];
    }

    // Método para calcular subtotal
    private function calcularSubtotal($cantidad, $precio, $descuento = 0)
    {
        return max(0, ($cantidad * $precio) - $descuento);
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
