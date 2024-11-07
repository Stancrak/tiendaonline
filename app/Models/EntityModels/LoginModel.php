<?php

namespace App\Models;

use App\Dao\LoginDAOInterface;

require_once __DIR__ . '/../../Dao/Interfaces/LoginDaoInterfaces.php';

class LoginModel
{
    private $userDAO;

    public function __construct(LoginDAOInterface $usList)
    {
        $this->userDAO = $usList;
    }
    
    public function obtenerUsuarioAc($login){
        return $this->userDAO->obtenerUsuarioAc($login);
    }

    public function inicioSesion($login){
        return $this->userDAO->inicioSesion($login);
    }
}
