<?php

namespace App\Models;

use App\Dao\PedidosDaoInterface;

require_once __DIR__ . '/../../Dao/Interfaces/PedidosDaoInterfaces.php';

class PedidosModel
{
    private $pedidosDAO;

    public function __construct(PedidosDaoInterface $pedidosDao)
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

    public function obtenerEstados()
    {
        return $this->pedidosDAO->obtenerEstados();
    }

    public function obtenerPrioridades()
    {
        return $this->pedidosDAO->obtenerPrioridades();
    }

    public function agregarPedido($pedidoData)
    {
        // Aquí puedes agregar validaciones adicionales si es necesario
        return $this->pedidosDAO->agregarPedido($pedidoData);
    }

    public function modificarPedido($pedidoData)
    {
        // Aquí puedes agregar validaciones adicionales si es necesario
        return $this->pedidosDAO->modificarPedido($pedidoData);
    }

    public function eliminarPedido($idPedido)
    {
        // Aquí puedes agregar verificaciones adicionales antes de eliminar
        return $this->pedidosDAO->eliminarPedido($idPedido);
    }

    public function obtenerPedidoPorId($idPedido)
    {
        return $this->pedidosDAO->obtenerPedidoPorId($idPedido);
    }

    public function obtenerDetallesPedido($idPedido)
    {
        return $this->pedidosDAO->obtenerDetallesPedido($idPedido);
    }
}
