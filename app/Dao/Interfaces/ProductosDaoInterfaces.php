<?php
namespace App\Dao;

interface ProductosDAOInterface
{
    public function obtenerCategorias();
    public function obtenerProductos();
    public function obtenerFabricante();
    public function obtenerEstados();

    public function agregarCategorias($productos);
    public function agregarProductos($productos);
    public function modificarCategoria($productos);
    public function modificarProductos($productos);
    public function eliminarCategoria($productos);
    public function eliminarProductos($productos);

    public function agregarCaracteristicas($productos);


}
