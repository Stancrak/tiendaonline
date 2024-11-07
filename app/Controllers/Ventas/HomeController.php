<?php

namespace App\Controllers;

class HomeController {
    
    // Función que manejará la página de inicio
    public function __construct($conexion)
    {
        
    }
    public function index() {
        
        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Ventas/home/home.php';

        // usar plantilla layaout
        include __DIR__ . '/../../views/layaout/VistaNivel6.php';
    }
}
