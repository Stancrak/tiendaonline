<?php

session_start(); // Iniciar la sesión

use App\Controllers\HomeController;
use App\Controllers\LoginController;

use App\Controllers\Principaln3Controller;
use App\Controllers\AdministradorController;
use App\Controllers\ProductoController;
use App\Controllers\PedidosController;



require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Controllers/Ventas/HomeController.php';
require_once __DIR__ . '/../app/Controllers/Contador/Homen3Controller.php';

//Carpeta Administrador
require_once __DIR__ . '/../app/Controllers/Administrador/AdministradorController.php';
require_once __DIR__ . '/../app/Controllers/Administrador/ProductosController.php';
require_once __DIR__ . '/../app/Controllers/Administrador/PedidosController.php';

require_once __DIR__ . '/../app/controllers/Login/LoginControlller.php';

// Verificación de si el usuario está autenticado y tiene el nivel adecuado
function verificarAutenticacion($nivelRequerido = null)
{
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['usuario'])) {
        header('Location: /tiendaonline/?route=login');
        exit;
    }

    // Verificar si se requiere un nivel específico y si el usuario tiene ese nivel
    if ($nivelRequerido !== null && isset($_SESSION['rol']) && $_SESSION['rol'] != $nivelRequerido) {
        header('Location: /tiendaonline/?route=login');
        exit;
    }
}


// Obtener la ruta de la solicitud
$route = $_GET['route'] ?? 'login'; // Ruta predeterminada

// Crear instancias de los controladores
$homeController = new HomeController($conexion);
$loginController = new LoginController($conexion);

//Nivel 3
$homePController = new Principaln3Controller($conexion);

//Administrador
$administradorControllers = new AdministradorController($conexion);
$productosControllers = new ProductoController($conexion);
$pedidosControllers = new PedidosController($conexion);

// Manejar la solicitud según la ruta
switch ($route) {

        // Para nivel 6
    case 'home':
        verificarAutenticacion(); // Verificar si está autenticado
        $homeController->index();
        break;


    case 'logout':
        $loginController->logout();
        break;

    case 'authenticate':
        $loginController->authenticate();
        break;


        //nivel 3

    case 'homea':
        verificarAutenticacion(); // Verificar si está autenticado
        $homePController->index();
        break;



    case 'adminHome':
        verificarAutenticacion();
        $administradorControllers->index();
        break;

    case 'productos':
        verificarAutenticacion();
        $productosControllers->index();
        break;

    case 'agregarProducto':
        verificarAutenticacion();
        $productosControllers->agregar();
        break;

    case 'modificarProductos':
        verificarAutenticacion();
        $productosControllers->modificarProductos();
        break;

    case 'eliminarProductos':
        verificarAutenticacion();
        $productosControllers->eliminarProductos();
        break;

    case 'categoriahome':
        verificarAutenticacion();
        $productosControllers->categoria();
        break;

    case 'agregarCategoria':
        verificarAutenticacion();
        $productosControllers->agregarCategoria();
        break;

    case 'modificarCategoria':
        verificarAutenticacion();
        $productosControllers->modificarCategoria();
        break;

    case 'eliminarCategoria':
        verificarAutenticacion();
        $productosControllers->eliminarCategoria();
        break;

    case 'pedidosHome':
        verificarAutenticacion();
        $productosControllers->pedidoshome();
        break;

    case 'caracteristcaHome':
        verificarAutenticacion();
        $productosControllers->caracteristicahome();
        break;

    case 'agregarCaracteristca':
        verificarAutenticacion();
        $productosControllers->agregarCaracteristicas();
        break;

    case 'mostrarCaracteristicas':
        verificarAutenticacion();
        $productosControllers->mostrarCaracteristicas();
        break;

    case 'eliminarCaracteristica':
        verificarAutenticacion();
        $productosControllers->eliminarCaracteristica();
        break;

    case 'eliminarCaracteristicaSecundaria':
        verificarAutenticacion();
        $productosControllers->eliminarCaracteristicaSecundaria();
        break;

    case 'pedidos':
        verificarAutenticacion();
        $pedidosController->index();
        break;

    case 'agregarPedido':
        verificarAutenticacion();
        $pedidosController->agregarPedido();
        break;

    case 'modificarPedido':
        verificarAutenticacion();
        $pedidosController->modificarPedido();
        break;

    case 'eliminarPedido':
        verificarAutenticacion();
        $pedidosController->eliminarPedido();
        break;

    case 'obtenerPedido':
        verificarAutenticacion();
        $pedidosController->obtenerPedido();
        break;

    case 'obtenerDetallesPedido':
        verificarAutenticacion();
        $pedidosController->obtenerDetallesPedido();
        break;


    case 'login':
    default:
        $loginController->index();
        break;
}
