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



    public function obtenerProveedores()
    {
        $query = "SELECT idProveedor, nombre FROM proveedor WHERE estado = 'activo'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCategorias()
    {
        $query = "SELECT idCat, fechaIngreso,nombre,descripcion,estado FROM categoria;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPrioridades()
    {
        $query = "SHOW COLUMNS FROM pedidoProveedor WHERE Field = 'prioridad'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer valores ENUM usando una expresión regular
        preg_match("/^enum\('(.+)'\)$/", $result['Type'], $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    public function obtenerEstados()
    {
        $query = "SHOW COLUMNS FROM pedidoProveedor WHERE Field = 'estado'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer valores ENUM usando una expresión regular
        preg_match("/^enum\('(.+)'\)$/", $result['Type'], $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    // Modificar el método obtenerPedidos para incluir los nuevos campos
    public function obtenerPedidos()
    {
        $query = "SELECT p.*, pr.nombre as nombreProveedor, 
              p.totalImpuesto, p.descuentos,
              (SELECT COUNT(*) FROM detallePedidoProvee WHERE idPedido = p.idPedido) as totalProductos 
              FROM pedidoProveedor p 
              INNER JOIN proveedor pr ON p.idProveedor = pr.idProveedor
              ORDER BY p.fechaCreacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modificar método agregarPedido
    public function agregarPedido($pedidoData)
    {
        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO pedidoProveedor (idProveedor, fechaPedido, estado, total, 
                  fechaEntrega, metodoPago, direccionEntrega, observaciones, prioridad, 
                  idUsuario, totalImpuesto, descuentos) 
                  VALUES (:idProveedor, :fechaPedido, :estado, :total, :fechaEntrega, 
                  :metodoPago, :direccionEntrega, :observaciones, :prioridad, 
                  :idUsuario, :totalImpuesto, :descuentos)";

            $stmt = $this->db->prepare($query);
            $stmt->execute($pedidoData);
            $idPedido = $this->db->lastInsertId();

            if (isset($pedidoData['detalles']) && !empty($pedidoData['detalles'])) {
                $this->agregarDetallesPedido($idPedido, $pedidoData['detalles']);
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Pedido agregado correctamente', 'idPedido' => $idPedido];
        } catch (PDOException $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'Error al agregar el pedido: ' . $e->getMessage()];
        }
    }

    // Agregar nuevo método para gestionar detalles
    public function agregarDetallesPedido($idPedido, $detalles)
    {
        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO detallePedidoProvee 
                      (idPedido, idProducto, cantidad, precio, descuento, fechaCreacion) 
                      VALUES (:idPedido, :idProducto, :cantidad, :precio, :descuento, CURRENT_DATE)";
            $stmt = $this->db->prepare($query);

            foreach ($detalles as $detalle) {
                $stmt->execute([
                    ':idPedido' => $idPedido,
                    ':idProducto' => $detalle['idProducto'],
                    ':cantidad' => $detalle['cantidad'],
                    ':precio' => $detalle['precio'],
                    ':descuento' => $detalle['descuento'] ?? 0
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function modificarPedido($pedidoData)
    {
        $query = "UPDATE pedidoProveedor 
                  SET idProveedor = :idProveedor, fechaPedido = :fechaPedido, estado = :estado, total = :total, 
                      fechaEntrega = :fechaEntrega, metodoPago = :metodoPago, direccionEntrega = :direccionEntrega, 
                      observaciones = :observaciones, prioridad = :prioridad 
                  WHERE idPedido = :idPedido";
        $stmt = $this->db->prepare($query);

        if ($stmt->execute($pedidoData)) {
            return ['success' => true, 'message' => 'Pedido modificado correctamente'];
        } else {
            return ['success' => false, 'error' => 'Error al modificar el pedido'];
        }
    }

    public function eliminarPedido($idPedido)
    {
        $query = "DELETE FROM pedidoProveedor WHERE idPedido = :idPedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idPedido', $idPedido);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Pedido eliminado correctamente'];
        } else {
            return ['success' => false, 'error' => 'Error al eliminar el pedido'];
        }
    }

    public function obtenerPedidoPorId($idPedido)
    {
        $query = "SELECT * FROM pedidoProveedor WHERE idPedido = :idPedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idPedido', $idPedido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallesPedido($idPedido)
    {
        $query = "SELECT d.*, p.nombre as nombreProducto 
                  FROM detallePedidoProvee d 
                  INNER JOIN producto p ON d.idProducto = p.idProducto 
                  WHERE d.idPedido = :idPedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idPedido', $idPedido);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
