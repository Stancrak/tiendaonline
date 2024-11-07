<?php

namespace App\Dao;

use PDO;
use PDOException;

require_once __DIR__ . '/../Interfaces/AdministradorDaoInterfaces.php'; // Ajusta el nombre si es necesario

class AdministradorDao implements AdministradorDAOInterface
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

}
