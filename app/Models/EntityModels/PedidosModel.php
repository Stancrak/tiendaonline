<?php

namespace App\Models;

use App\Dao\PedidosDAOInterface;
use PDO;
use PDOException;

require_once __DIR__ . '/../../Dao/Interfaces/PedidosDaoInterfaces.php';

class PedidosModel
{
    private $pedidosDAO;
    private $db;

    public function __construct(PedidosDAOInterface $pedidosDao)
    {
        $this->pedidosDAO = $pedidosDao;
    }

    public function obtenerPedidos()
    {
        return $this->pedidosDAO->obtenerPedidos();
    }

    public function obtenerProveedores()
    {
        return $this->pedidosDAO->obtenerProveedores();
    }

    public function obtenerPedidoPorId($idPedido)
    {
        return $this->pedidosDAO->obtenerPedidoPorId($idPedido);
    }

    public function agregarPedido($pedido)
    {
        if (empty($pedido['idProveedor']) || empty($pedido['fechaPedido'])) {
            return false;
        }
        return $this->pedidosDAO->agregarPedido($pedido);
    }

    public function modificarPedido($pedido)
    {
        if (empty($pedido['idPedido']) || empty($pedido['idProveedor'])) {
            return false;
        }
        return $this->pedidosDAO->modificarPedido($pedido);
    }

    public function eliminarPedido($idPedido)
    {
        return $this->pedidosDAO->eliminarPedido($idPedido);
    }

    public function obtenerEstadosPedido()
    {
        return $this->pedidosDAO->obtenerEstadosPedido();
    }

    public function obtenerPrioridades()
    {
        return $this->pedidosDAO->obtenerPrioridades();
    }

    public function obtenerEstadisticasPedidos()
    {
        return $this->pedidosDAO->obtenerEstadisticasPedidos();
    }

    public function obtenerMetodosPago()
    {
        return $this->pedidosDAO->obtenerMetodosPago();
    }

    //Detalle
    public function obtenerPedidosPorProveedor($idProveedor)
    {
        return $this->pedidosDAO->obtenerPedidosPorProveedor($idProveedor);
    }

    public function obtenerDetallesPedido($idPedido)
    {
        return $this->pedidosDAO->obtenerDetallesPedido($idPedido);
    }

    public function obtenerProductos()
    {
        return $this->pedidosDAO->obtenerProductos();
    }

    public function obtenerProductosPorProveedor($idProveedor)
    {
        return $this->pedidosDAO->obtenerProductosPorProveedor($idProveedor);
    }

    public function obtenerDetallePedidoPorId($idProducto)
    {
        return $this->pedidosDAO->obtenerDetallePedidoPorId($idProducto);
    }

    // Método para obtener el detalle por ID
    public function obtenerDetallePorId($idDetallePedido)
    {
        return $this->pedidosDAO->obtenerDetallePorId($idDetallePedido);
    }

    public function agregarDetallePedido($detalle)
    {
        // Validaciones
        if (
            empty($detalle['idPedido']) ||
            empty($detalle['idProducto']) ||
            !isset($detalle['cantidad']) ||
            !isset($detalle['precio']) ||
            $detalle['cantidad'] <= 0 ||
            $detalle['precio'] < 0
        ) {
            return ['success' => false, 'error' => 'Datos incompletos o inválidos'];
        }

        // Validar que el idPedido y el idProducto existan
        if (!$this->verificarExistenciaPedido($detalle['idPedido'])) {
            return ['success' => false, 'error' => 'El idPedido no existe'];
        }
        if (!$this->verificarExistenciaProducto($detalle['idProducto'])) {
            return ['success' => false, 'error' => 'El idProducto no existe'];
        }

        try {
            $query = "INSERT INTO detallePedidoProvee 
        (idPedido, idProducto, cantidad, precio, descuento, fechaCreacion) 
        VALUES 
        (:idPedido, :idProducto, :cantidad, :precio, :descuento, NOW())";

            // Preparar la consulta
            $stmt = $this->db->prepare($query);

            // Vincular los parámetros
            $stmt->bindParam(':idPedido', $detalle['idPedido'], PDO::PARAM_STR);
            $stmt->bindParam(':idProducto', $detalle['idProducto'], PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(':precio', $detalle['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':descuento', $detalle['descuento'] ?? 0, PDO::PARAM_STR);

            // Ejecutar la consulta
            $success = $stmt->execute();

            // Retornar el resultado de la operación
            return $success ? ['success' => true, 'message' => 'Detalle de pedido agregado correctamente'] : ['success' => false, 'error' => 'No se pudo agregar el detalle de pedido'];
        } catch (PDOException $e) {
            // Loguear el error y retornar un mensaje de error
            error_log("Error al agregar detalle de pedido: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error en la base de datos'];
        }
    }

    public function modificarDetallePedido($detalle)
    {
        // Validaciones más robustas
        if (
            empty($detalle['idDetallePedido']) ||
            empty($detalle['idProducto']) ||
            !isset($detalle['cantidad']) ||
            !isset($detalle['precio']) ||
            $detalle['cantidad'] <= 0 ||
            $detalle['precio'] < 0
        ) {
            return false;
        }

        // Verificar que el detalle de pedido exista
        $detalleExistente = $this->pedidosDAO->obtenerDetallePedidoPorId($detalle['idDetallePedido']);
        if (!$detalleExistente) {
            return false;
        }

        // Verificar que el producto exista
        $productoExiste = $this->pedidosDAO->verificarExistenciaProducto($detalle['idProducto']);
        if (!$productoExiste) {
            return false;
        }

        return $this->pedidosDAO->modificarDetallePedido($detalle);
    }

    public function eliminarDetallePedido($idDetallePedido)
    {
        // Verificar que el detalle de pedido exista
        $detalleExistente = $this->pedidosDAO->obtenerDetallePedidoPorId($idDetallePedido);
        if (!$detalleExistente) {
            return false;
        }

        return $this->pedidosDAO->eliminarDetallePedido($idDetallePedido);
    }

    public function verificarExistenciaPedido($idPedido)
    {
        return $this->pedidosDAO->obtenerPedidoPorId($idPedido) !== false;
    }

    public function verificarExistenciaProducto($idProducto)
    {
        return $this->pedidosDAO->verificarExistenciaProducto($idProducto);
    }
}
