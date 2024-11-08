<?php

namespace App\Controllers;

use App\Dao\PedidosDao;
use App\Controllers\LoginController;
use App\Models\PedidosModel;

require_once __DIR__ . '/../Login/LoginControlller.php';
require_once __DIR__ . '/../../Dao/Administrador/PedidosDao.php';
require_once __DIR__ . '/../../Models/EntityModels/PedidosModel.php';

class PedidosController
{
    private $loginController;
    private $pedidosModel;

    public function __construct($conexion)
    {
        $this->loginController = new LoginController($conexion);
        $dao = new PedidosDao($conexion);
        $this->pedidosModel = new PedidosModel($dao);
    }

    public function index()
    {
        $this->loginController->verificarAcceso(1);

        $pedidos = $this->pedidosModel->obtenerPedidos();
        $proveedores = $this->pedidosModel->obtenerProveedores();
        $estados = $this->pedidosModel->obtenerEstados(); // Cambio aquí
        $prioridades = $this->pedidosModel->obtenerPrioridades();

        $data = [
            'pedidos' => $pedidos,
            'proveedores' => $proveedores,
            'estados' => $estados, // Cambio aquí
            'prioridades' => $prioridades
        ];

        $content = __DIR__ . '/../../Views/Administrador/Productos/pedidos.php';
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function agregarPedido()
    {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedidoData = [
                'idProveedor' => $this->limpiarDatos($_POST['idProveedor']),
                'fechaPedido' => $this->limpiarDatos($_POST['fechaPedido']),
                'estado' => $this->limpiarDatos($_POST['estado']),
                'total' => $this->limpiarDatos($_POST['total']),
                'fechaEntrega' => $this->limpiarDatos($_POST['fechaEntrega']),
                'metodoPago' => $this->limpiarDatos($_POST['metodoPago']),
                'direccionEntrega' => $this->limpiarDatos($_POST['direccionEntrega']),
                'observaciones' => $this->limpiarDatos($_POST['observaciones']),
                'prioridad' => $this->limpiarDatos($_POST['prioridad']),
                'idUsuario' => $_SESSION['idUsuario'] ?? null,
                'totalImpuesto' => $this->limpiarDatos($_POST['totalImpuesto']),
                'descuentos' => $this->limpiarDatos($_POST['descuentos'])
            ];

            // Procesar detalles si existen
            if (isset($_POST['detalles'])) {
                $pedidoData['detalles'] = array_map(function ($detalle) {
                    return [
                        'idProducto' => $this->limpiarDatos($detalle['idProducto']),
                        'cantidad' => $this->limpiarDatos($detalle['cantidad']),
                        'precio' => $this->limpiarDatos($detalle['precio']),
                        'descuento' => $this->limpiarDatos($detalle['descuento'])
                    ];
                }, $_POST['detalles']);
            }

            $resultado = $this->pedidosModel->agregarPedido($pedidoData);
            echo json_encode($resultado);
        }
    }

    public function modificarPedido()
    {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedidoData = [
                'idPedido' => $this->limpiarDatos($_POST['idPedido']),
                'idProveedor' => $this->limpiarDatos($_POST['idProveedor']),
                'fechaPedido' => $this->limpiarDatos($_POST['fechaPedido']),
                'estado' => $this->limpiarDatos($_POST['estado']),
                'total' => $this->limpiarDatos($_POST['total']),
                'fechaEntrega' => $this->limpiarDatos($_POST['fechaEntrega']),
                'metodoPago' => $this->limpiarDatos($_POST['metodoPago']),
                'direccionEntrega' => $this->limpiarDatos($_POST['direccionEntrega']),
                'observaciones' => $this->limpiarDatos($_POST['observaciones']),
                'prioridad' => $this->limpiarDatos($_POST['prioridad'])
            ];

            $resultado = $this->pedidosModel->modificarPedido($pedidoData);
            echo json_encode($resultado);
        }
    }

    public function eliminarPedido()
    {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPedido = $this->limpiarDatos($_POST['idPedido']);
            $resultado = $this->pedidosModel->eliminarPedido($idPedido);
            echo json_encode($resultado);
        }
    }

    public function obtenerPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idPedido = $this->limpiarDatos($_GET['idPedido']);
            $pedido = $this->pedidosModel->obtenerPedidoPorId($idPedido);
            echo json_encode($pedido);
        }
    }

    public function obtenerDetallesPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idPedido = $this->limpiarDatos($_GET['idPedido']);
            $detalles = $this->pedidosModel->obtenerDetallesPedido($idPedido);
            echo json_encode($detalles);
        }
    }

    private function limpiarDatos($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
