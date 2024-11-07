<?php

namespace App\Dao;

use PDO;
use PDOException;

require_once __DIR__ . '/../Interfaces/LoginDaoInterfaces.php'; // Ajusta el nombre si es necesario

class LoginDao implements LoginDAOInterface
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function obtenerUsuarioAc($login)
    {
        $query = "SELECT * FROM usuario WHERE usuario = :usuario";
        $stmt = $this->db->prepare($query);

        // Vincular el nombre de usuario
        $stmt->bindParam(':usuario', $login['usuario']);
        $stmt->execute();

        // Cambiar a fetch para obtener solo una fila (el usuario encontrado)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inicioSesion($login){
        $query = "INSERT INTO login (idUsuario, fechaHoraInicio) VALUES (:idUsuario, NOW())";
        $stmt = $this->db->prepare($query);

        // Bind user ID to the query
        $stmt->bindParam(':idUsuario', $login);

        return $stmt->execute();
    }
}
