<?php

namespace App\Controllers;

use App\Controllers\LoginController; // Asegúrate de importar el LoginController

require_once __DIR__ . '/../Login/LoginControlller.php'; // Incluye el controlador que tiene la función verificarAcceso


class Principaln3Controller {

    private $loginController;
    
    // Función que manejará la página de inicio
    public function __construct($conexion)
    {
         // Crear una instancia de LoginController, pasando la conexión si es necesaria
         $this->loginController = new LoginController($conexion);
    }
    public function index() {
        
        $this->loginController->verificarAcceso(2); 
        
        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Ventas/home/home.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/vistanivel3.php';
    }
}
