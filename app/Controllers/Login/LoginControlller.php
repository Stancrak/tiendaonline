<?php

namespace App\Controllers;

use App\Dao\LoginDao;
use App\Models\LoginModel;

require_once __DIR__ . '/../../Dao/Login/LoginDao.php';
require_once __DIR__ . '/../../Models/EntityModels/LoginModel.php';

class LoginController
{
    private $login;

    public function __construct($conexion)
    {
        $dao = new LoginDao($conexion);
        $this->login = new LoginModel($dao);
    }

    public function index()
    {
        // Verificar si el usuario ya está autenticado
        if (isset($_SESSION['usuario'])) {
            if ($_SESSION['rol'] == 1) {
                header('Location: /tiendaonline/?route=adminHome');
            } elseif ($_SESSION['rol'] == 2) {
                header('Location: /tiendaonline/?route=home');
            } elseif ($_SESSION['rol'] == 3) {
                header('Location: /tiendaonline/?route=homea');
            } else {
                header('Location: /tiendaonline/?route=login');
            }
            exit;
        }

        // Mostrar el formulario de inicio de sesión si no está autenticado
        include __DIR__ . '/../../Views/Login/index.php';
    }


    public function authenticate()
    {

        $login = $_POST['usuario'];
        $password = $_POST['passwords'];

        $usuarioL = [
            'usuario' => $login,
        ];

        $usuario = $this->login->obtenerUsuarioAc($usuarioL);

        header('Content-Type: application/json');

        // Verificamos si el usuario existe
        if ($usuario) {
            // Usamos SHA2 para encriptar la contraseña ingresada
            $hashedPassword = hash('sha256', $password);

            // Comparamos la contraseña hasheada con la almacenada
            if ($hashedPassword === $usuario['contrasenaa']) {
                $_SESSION['usuario'] = $usuario['usuario'];
                $_SESSION['rol'] = $usuario['idRol'];
                $_SESSION['idUsuario'] = $usuario['idUsuario'];


                $response = [
                    'success' => true,
                    'redirect' => ''
                ];

                // Redirección basada en el nivel del usuario
                switch ($usuario['idRol']) {
                    case 1:
                        $response['redirect'] = '?route=adminHome';
                        break;
                    case 2:
                        $response['redirect'] = '?route=home';
                        break;
                    case 3:
                        $response['redirect'] = '?route=homea';
                        break;
                    default:
                        $response['redirect'] = '/';
                        break;
                }
                
                // Record the login event
                $this->login->inicioSesion($usuario['idUsuario']);
                
                echo json_encode($response);
                exit;
            }
        }

        // Respuesta si el usuario no existe o la contraseña es incorrecta
        $response = [
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos '
        ];

        echo json_encode($response);
        exit;
    }


    //Cerrar sesion
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /tiendaonline/?route=login');
        exit;
    }

    // Nueva función para verificar si el usuario tiene el nivel adecuado
    public function verificarAcceso($nivelRequerido)
    {
        if (!isset($_SESSION['usuario'])) {
            // Si no ha iniciado sesión, redirigir a la página de login
            header('Location: /tiendaonline/?route=login');
            exit;
        }

        if ($_SESSION['rol'] != $nivelRequerido) {
            header('Location: /tiendaonline/?route=acceso-denegado');
            exit;
        }
    }
}
