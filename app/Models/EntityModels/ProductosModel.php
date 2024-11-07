<?php

namespace App\Models;

use App\Dao\ProductosDAOInterface;

require_once __DIR__ . '/../../Dao/Interfaces/ProductosDaoInterfaces.php';

class ProductosModel
{
    private $productosDAO;

    public function __construct(ProductosDAOInterface $productoDao)
    {
        $this->productosDAO = $productoDao;
    }

    public function obtenerCategorias(){
        return $this->productosDAO->obtenerCategorias();
    }
    public function obtenerProductos(){
        return $this->productosDAO->obtenerProductos();
    }

    public function obtenerFabricante(){
        return $this->productosDAO->obtenerFabricante();
    }
    
    public function obtenerEstados(){
        return $this->productosDAO->obtenerEstados();
    }

    public function agregarCategorias($productos){
        return $this->productosDAO->agregarCategorias($productos);
    }
    public function agregarProductos($productos){
        return $this->productosDAO->agregarProductos($productos);
    }
    public function modificarCategoria($productos){
        return $this->productosDAO->modificarCategoria($productos);
    }
    public function modificarProductos($productos){
        return $this->productosDAO->modificarProductos($productos);
    }
    public function eliminarCategoria($productos){
        return $this->productosDAO->eliminarCategoria($productos);
    }
    public function eliminarProductos($productos){
        return $this->productosDAO->eliminarProductos($productos);
    }
    public function agregarCaracteristicas($productos){
        return $this->productosDAO->agregarCaracteristicas($productos);
    }
}
