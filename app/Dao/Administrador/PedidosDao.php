<?php

namespace App\Dao;

use PDO;
use PDOException;

require_once __DIR__ . '/../Interfaces/PedidosDaoInterfaces.php';

class PedidosDao implements PedidosDAOInterface
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function obtenerPedidos()
    {
        try {
            $query = "SELECT p.*, pr.nombre AS nombreProveedor 
                      FROM pedidoProveedor p 
                      INNER JOIN proveedor pr ON p.idProveedor = pr.idProveedor 
                      ORDER BY p.fechaCreacion DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProveedores()
    {
        try {
            $query = "SELECT idProveedor, nombre FROM proveedor WHERE estado = 'activo' ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener proveedores: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPedidoPorId($idPedido)
    {
        try {
            $query = "SELECT * FROM pedidoProveedor WHERE idPedido = :idPedido";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['idPedido' => $idPedido]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedido por ID: " . $e->getMessage());
            return false;
        }
    }

    public function agregarPedido($pedido)
    {
        try {
            $query = "INSERT INTO pedidoProveedor (idPedido, idProveedor, fechaPedido, estado, total, fechaEntrega, metodoPago, direccionEntrega, observaciones, prioridad, totalImpuesto, descuentos)
                      VALUES (:idPedido, :idProveedor, :fechaPedido, :estado, :total, :fechaEntrega, :metodoPago, :direccionEntrega, :observaciones, :prioridad, :totalImpuesto, :descuentos)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($pedido);
        } catch (PDOException $e) {
            error_log("Error al agregar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function modificarPedido($pedido)
    {
        try {
            $query = "UPDATE pedidoProveedor SET 
                        idProveedor = :idProveedor, fechaPedido = :fechaPedido, estado = :estado, 
                        total = :total, fechaEntrega = :fechaEntrega, metodoPago = :metodoPago, 
                        direccionEntrega = :direccionEntrega, observaciones = :observaciones, 
                        prioridad = :prioridad, totalImpuesto = :totalImpuesto, descuentos = :descuentos 
                      WHERE idPedido = :idPedido";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($pedido);
        } catch (PDOException $e) {
            error_log("Error al modificar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarPedido($idPedido)
    {
        try {
            $query = "DELETE FROM pedidoProveedor WHERE idPedido = :idPedido";
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['idPedido' => $idPedido]);
        } catch (PDOException $e) {
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadosPedido()
    {
        return ['pendiente', 'enviado', 'recibido', 'cancelado'];
    }

    public function obtenerPrioridades()
    {
        return ['baja', 'media', 'alta'];
    }

    public function obtenerEstadisticasPedidos()
    {
        try {
            $query = "SELECT estado, COUNT(*) as total, SUM(total) as totalMonto 
                      FROM pedidoProveedor GROUP BY estado";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas de pedidos: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerMetodosPago()
    {
        return ['transferencia', 'efectivo', 'tarjeta', 'cheque'];
    }

    //Detalle

    public function obtenerPedidosPorProveedor($idProveedor)
    {
        try {
            $query = "SELECT p.*, pr.nombre AS nombreProveedor 
                  FROM pedidoProveedor p 
                  INNER JOIN proveedor pr ON p.idProveedor = pr.idProveedor 
                  WHERE p.idProveedor = :idProveedor 
                  ORDER BY p.fechaCreacion DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['idProveedor' => $idProveedor]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos por proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProductos()
    {
        try {
            $query = "SELECT idProducto, nombre, precio FROM producto WHERE estado = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProductosPorProveedor($idProveedor)
    {
        try {
            $query = "SELECT p.idProducto, p.nombre, p.precio 
                      FROM producto p 
                      WHERE p.proveedor = :idProveedor"; // Asegúrate de que 'proveedor' sea el nombre correcto de la columna
            $stmt = $this->db->prepare($query);
            $stmt->execute(['idProveedor' => $idProveedor]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener productos por proveedor: " . $e->getMessage());
            return false;
        }
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
            $detalle['precio'] < 0 ||
            empty($detalle['fechaCreacion']) // Nueva validación para la fecha
        ) {
            return false;
        }

        try {
            $query = "INSERT INTO detallePedidoProvee 
                      (idPedido, idProducto, cantidad, precio, descuento, subTotal, fechaCreacion) 
                      VALUES 
                      (:idPedido, :idProducto, :cantidad, :precio, :descuento, :subTotal, NOW())";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idPedido', $detalle['idPedido'], PDO::PARAM_STR);
            $stmt->bindParam(':idProducto', $detalle['idProducto'], PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(':precio', $detalle['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':descuento', $detalle['descuento'] ?? 0, PDO::PARAM_STR);
            $stmt->bindParam(':subTotal', $detalle['subTotal'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaCreacion', $detalle['fechaCreacion'], PDO::PARAM_STR); // Vincula la fecha

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al agregar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    public function modificarDetallePedido($detalle)
    {
        try {
            $query = "UPDATE detallePedidoProvee 
                  SET idProducto = :idProducto, 
                      cantidad = :cantidad, 
                      precio = :precio, 
                      descuento = :descuento 
                  WHERE idDetallePedido = :idDetallePedido";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idDetallePedido', $detalle['idDetallePedido'], PDO::PARAM_INT);
            $stmt->bindParam(':idProducto', $detalle['idProducto'], PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
            $stmt->bindParam(':precio', $detalle['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':descuento', $detalle['descuento'], PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al modificar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarDetallePedido($idDetallePedido)
    {
        try {
            $query = "DELETE FROM detallePedidoProvee WHERE idDetallePedido = :idDetallePedido";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idDetallePedido', $idDetallePedido, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDetallesPedido($idPedido)
    {
        try {
            $query = "SELECT dp.*, p.nombre AS nombreProducto 
                      FROM detallePedidoProvee dp
                      INNER JOIN producto p ON dp.idProducto = p.idProducto
                      WHERE dp.idPedido = :idPedido";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles de pedido: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener el detalle del pedido por ID
    public function obtenerDetallePorId($idDetallePedido)
    {
        $stmt = $this->db->prepare("SELECT * FROM detalles_pedido WHERE idDetallePedido = ?");
        $stmt->execute([$idDetallePedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallePedidoPorId($idDetallePedido)
    {
        try {
            $query = "SELECT 
                        dp.idDetallePedido, 
                        dp.idPedido, 
                        dp.idProducto,
                        p.nombre AS nombreProducto, 
                        dp.cantidad, 
                        dp.precio, 
                        dp.descuento,
                        dp.subTotal
                      FROM 
                        detallePedidoProvee dp
                      JOIN 
                        producto p ON dp.idProducto = p.idProducto
                      WHERE 
                        dp.idDetallePedido = :idDetallePedido";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idDetallePedido', $idDetallePedido, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalle de pedido: " . $e->getMessage());
            return null;
        }
    }

    public function verificarExistenciaProducto($idProducto)
    {
        try {
            $query = "SELECT COUNT(*) FROM producto WHERE idProducto = :idProducto AND estado = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['idProducto' => $idProducto]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar existencia de producto: " . $e->getMessage());
            return false;
        }
    }
}
