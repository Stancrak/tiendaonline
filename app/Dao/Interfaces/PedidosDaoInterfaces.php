<?php

namespace App\Dao;

interface PedidosDAOInterface
{
    // Métodos CRUD
    public function obtenerPedidos();
    public function obtenerProveedores();
    public function obtenerPedidoPorId($idPedido);
    public function agregarPedido($pedido);
    public function modificarPedido($pedido);
    public function eliminarPedido($idPedido);

    // Métodos de utilidad
    public function obtenerEstadosPedido();
    public function obtenerPrioridades();
    public function obtenerEstadisticasPedidos();
    public function obtenerMetodosPago();

    //Detalle

    public function obtenerProductos();
    public function obtenerProductosPorProveedor($idProveedor);
    public function obtenerDetallesPedido($idPedido);
    public function agregarDetallePedido($detalle);
    public function modificarDetallePedido($detalle);
    public function eliminarDetallePedido($idDetallePedido);
    public function obtenerDetallePedidoPorId($idDetallePedido);
    public function verificarExistenciaProducto($idProducto);
    public function obtenerPedidosPorProveedor($idProveedor);
    public function obtenerDetallePorId($idDetallePedido);

    // Métodos para transacciones
    public function beginTransaction(): bool;
    public function commit(): bool;
    public function rollBack(): bool;

    // Métodos para cálculo y actualización de total
    public function calcularTotalPedido(string $idPedido): float;
    public function actualizarTotalPedido(string $idPedido, float $total): bool;
}
