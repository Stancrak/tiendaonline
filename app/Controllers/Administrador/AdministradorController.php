<?php

namespace App\Controllers;

use App\Dao\AdministradorDao;
use App\Controllers\LoginController; // Asegúrate de importar el LoginController
use App\Models\AdministradorModel;

require_once __DIR__ . '/../Login/LoginControlller.php'; // Incluye el controlador que tiene la función verificarAcceso
require_once __DIR__ . '/../../Dao/Administrador/AdministradorDao.php';
require_once __DIR__ . '/../../Models/EntityModels/AdministradorModel.php';


class AdministradorController
{
    private $loginController;
    private $cargarUsuario;
    
   
    // Función que manejará la página de inicio
    public function __construct($conexion)
    {
        $this->loginController = new LoginController($conexion);

        $dao = new AdministradorDao($conexion);
        $this->cargarUsuario = new AdministradorModel($dao);
    }

    public function index()
    {
        $this->loginController->verificarAcceso(1);
        
        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Administrador/Home/home.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }
    

    // Función para limpiar los datos de entrada
    private function limpiarDatos($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
