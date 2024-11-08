<?php

namespace App\Dao;

use PDO;
use PDOException;

require_once __DIR__ . '/../Interfaces/ProductosDaoInterfaces.php'; // Ajusta el nombre si es necesario

class ProductosDao implements ProductosDAOInterface
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }
    public function obtenerCategorias()
    {
        $query = "SELECT idCat, fechaIngreso,nombre,descripcion,estado FROM categoria;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductos()
    {
        $query = "SELECT pro.idProducto, 
                pro.nombre AS nombre, 
                pro.proveedor AS idProveedor,
                fa.nombre AS fabricante,
                pro.modeloSerial AS modelo,
                pro.categoria AS idCat,
                cat.nombre AS categoria,
                pro.anio AS anio,
                pro.fechaIngreso,
                pro.cantidad,
                pro.precio,
                pro.estado AS idEstado,
                stP.estado AS estado,
                pro.imagen AS img
                FROM producto pro 
            INNER JOIN categoria cat ON pro.categoria = cat.idCat
            LEFT JOIN proveedor fa ON pro.proveedor = fa.idProveedor
            LEFT JOIN estadosProductos stP ON pro.estado = stP.idEstado;";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerFabricante()
    {
        $query = "SELECT idProveedor, nombre FROM proveedor;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstados()
    {
        $query = "SELECT idEstado, estado FROM estadosProductos";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarCategorias($productos)
    {
        $query = "INSERT INTO categoria (fechaIngreso,nombre, descripcion) 
        VALUES (:fechaIngreso,:nombre, :descripcion)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':fechaIngreso', $productos['fechaIngreso']);
        $stmt->bindParam(':nombre', $productos['nombre']);
        $stmt->bindParam(':descripcion', $productos['descripcion']);

        return $stmt->execute();
    }

    public function agregarProductos($productos)
    {
        $query = "INSERT INTO producto (idProducto, fechaIngreso, nombre, modeloSerial, anio, cantidad, precio, estado, proveedor, categoria, imagen) 
        VALUES (:idProducto, :fechaIngreso, :nombre, :modeloSerial, :anio, :cantidad, :precio, :estado, :proveedor,:categoria, :imagen)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':idProducto', $productos['idProducto']);
        $stmt->bindParam(':fechaIngreso', $productos['fechaIngreso']);
        $stmt->bindParam(':nombre', $productos['nombre']);
        $stmt->bindParam(':modeloSerial', $productos['modeloSerial']);
        $stmt->bindParam(':anio', $productos['anio']);
        $stmt->bindParam(':cantidad', $productos['cantidad']);
        $stmt->bindParam(':precio', $productos['precio']);
        $stmt->bindParam(':estado', $productos['estado']);
        $stmt->bindParam(':proveedor', $productos['proveedor']);
        $stmt->bindParam(':categoria', $productos['categoria']);
        $stmt->bindParam(':imagen', $productos['imagen']);

        return $stmt->execute();
    }

    public function modificarCategoria($productos)
    {
        try {
            $query = "UPDATE categoria SET 
                        fechaIngreso = :fechaIngreso, 
                        nombre = :nombre,  
                        descripcion = :descripcion 
                      WHERE idCat = :idCat;";

            $stmt = $this->db->prepare($query);

            // Ejecutar la consulta con los datos del producto
            $stmt->execute([
                ':fechaIngreso' => $productos['fechaIngreso'], // Fecha de ingreso
                ':nombre' => $productos['nombre'],             // Nombre de la categoría
                ':descripcion' => $productos['descripcion'],   // Descripción de la categoría
                ':idCat' => $productos['idCat'],               // ID de la categoría
            ]);

            return true; // Retornar verdadero si la actualización fue exitosa
        } catch (PDOException $e) {
            return false; // Retornar falso si hubo un error
        }
    }

    public function modificarProductos($productos)
    {
        try {
            // Verificar si hay imagen nueva para actualizar o no
            $query = "UPDATE producto SET 
                        nombre = :nombre, 
                        proveedor = :proveedor, 
                        modeloSerial = :modeloSerial, 
                        categoria = :categoria, 
                        anio = :anio, 
                        fechaIngreso = :fechaIngreso, 
                        cantidad = :cantidad, 
                        precio = :precio, 
                        estado = :estado";

            // Solo actualizar la imagen si se proporciona una nueva
            if (!is_null($productos['imagen'])) {
                $query .= ", imagen = :imagen";
            }

            $query .= " WHERE idProducto = :idProducto";

            $stmt = $this->db->prepare($query);

            // Asociar parámetros
            $params = [
                ':nombre' => $productos['nombre'],
                ':proveedor' => $productos['proveedor'],
                ':modeloSerial' => $productos['modeloSerial'],
                ':categoria' => $productos['categoria'],
                ':anio' => $productos['anio'],
                ':fechaIngreso' => $productos['fechaIngreso'],
                ':cantidad' => $productos['cantidad'],
                ':precio' => $productos['precio'],
                ':estado' => $productos['estado'],
                ':idProducto' => $productos['idProducto']
            ];

            // Agregar el parámetro de imagen solo si hay una nueva imagen
            if (!is_null($productos['imagen'])) {
                $params[':imagen'] = $productos['imagen'];
            }

            // Ejecutar la consulta
            $stmt->execute($params);

            return true;
        } catch (PDOException $e) {
            // Manejo del error
            error_log("Error en modificarProductos: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCategoria($productos)
    {
        try {
            // Preparar la consulta SQL para actualizar el usuario
            $query = "DELETE FROM categoria WHERE idCat = :idCat;";
            $stmt = $this->db->prepare($query);

            // Ejecutar la consulta con los datos del usuario
            $stmt->execute([
                ':idCat' => $productos['idCat'],
            ]);

            return true;
        } catch (PDOException $e) {
            // Manejar errores (puedes agregar logging aquí si es necesario)
            return false;
        }
    }

    public function eliminarProductos($productos)
    {
        try {
            // Preparar la consulta SQL para actualizar el usuario
            $query = "DELETE FROM producto WHERE idProducto = :idProducto;";
            $stmt = $this->db->prepare($query);

            // Ejecutar la consulta con los datos del usuario
            $stmt->execute([
                ':idProducto' => $productos['idProducto'],
            ]);

            return true;
        } catch (PDOException $e) {
            // Manejar errores (puedes agregar logging aquí si es necesario)
            return false;
        }
    }

    public function agregarCaracteristicas($productos)
    {
        $query = "INSERT INTO caracteristicaProducto (idProducto, nombreCaracteristica, valor, descripcion) 
        VALUES (:idProducto, :nombreCaracteristica, :valor, :descripcion)";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':idProducto', $productos['idProducto']);
        $stmt->bindParam(':nombreCaracteristica', $productos['nombreCaracteristica']);
        $stmt->bindParam(':valor', $productos['valor']);
        $stmt->bindParam(':descripcion', $productos['descripcion']);

        return $stmt->execute();
    }

    // ProductoDao.php
    public function obtenerCaracteristicasPorProducto($idProducto)
    {
        $query = "SELECT * FROM caracteristicaProducto WHERE idProducto = :idProducto";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idProducto', $idProducto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarCaracteristica($idCaracteristica)
    {
        $query = "DELETE FROM caracteristicaProducto WHERE idCaracteristica = :idCaracteristica";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idCaracteristica', $idCaracteristica);
        return $stmt->execute();
    }

    public function eliminarCaracteristicaSecundaria($idCaracteristica)
    {
        $query = "DELETE FROM caracteristicas_secundarias WHERE idCaracteristica = :idCaracteristica";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idCaracteristica', $idCaracteristica);
        return $stmt->execute();
    }
}
