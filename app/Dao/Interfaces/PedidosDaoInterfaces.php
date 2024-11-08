<?php

namespace App\Dao;

interface PedidosDAOInterface
{
    public function obtenerPedidos();
    public function obtenerProveedores();
    public function obtenerEstados();
    public function obtenerPrioridades();
    public function agregarPedido($pedidoData);
    public function modificarPedido($pedidoData);
    public function eliminarPedido($idPedido);
    public function obtenerPedidoPorId($idPedido);
    public function obtenerDetallesPedido($idPedido);
}
