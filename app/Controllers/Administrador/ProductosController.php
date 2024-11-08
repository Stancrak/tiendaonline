<?php

namespace App\Controllers;

use App\Dao\ProductosDao;
use App\Controllers\LoginController; // Asegúrate de importar el LoginController
use App\Models\ProductosModel;

require_once __DIR__ . '/../Login/LoginControlller.php'; // Incluye el controlador que tiene la función verificarAcceso
require_once __DIR__ . '/../../Dao/Administrador/ProductoDao.php';
require_once __DIR__ . '/../../Models/EntityModels/ProductosModel.php';


class ProductoController
{
    private $loginController;
    private $cargarUsuario;


    // Función que manejará la página de inicio
    public function __construct($conexion)
    {
        $this->loginController = new LoginController($conexion);

        $dao = new ProductosDao($conexion);
        $this->cargarUsuario = new ProductosModel($dao);
    }

    public function index()
    {
        $this->loginController->verificarAcceso(1);

        $fabricante = $this->cargarUsuario->obtenerFabricante();
        $estadoProducto = $this->cargarUsuario->obtenerEstados();
        $categoria = $this->cargarUsuario->obtenerCategorias();
        $productoLista = $this->cargarUsuario->obtenerProductos();

        // Pasar los datos a la vista
        $data = [
            'fabricante' => $fabricante,
            'estadoProductos' => $estadoProducto,
            'categoria' => $categoria
        ];

        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Administrador/Productos/productos.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function agregar()
    {
        // Iniciar buffering para evitar salidas no deseadas
        ob_start();

        $idProducto = $this->generadorId();
        $fecha = $this->limpiarDatos($_POST['fecha'] ?? '');
        $nombreProducto = $this->limpiarDatos($_POST['nombreProducto'] ?? '');
        $fabricante = $this->limpiarDatos($_POST['fabricante'] ?? '');
        $modelo = $this->limpiarDatos($_POST['modelo'] ?? '');
        $categoria = $this->limpiarDatos($_POST['categoria'] ?? '');

        $anio = $this->limpiarDatos($_POST['anio'] ?? '');
        $cantidad = $this->limpiarDatos($_POST['cantidad'] ?? '');
        $precio = $this->limpiarDatos($_POST['precio'] ?? '');
        $estado = $this->limpiarDatos($_POST['estado'] ?? '');

        // Manejo de la imagen
        $imagenRuta = null;
        if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
            $tipoArchivo = $_FILES['imagenProducto']['type'];
            $tamanoArchivo = $_FILES['imagenProducto']['size'];

            // Validar tipo de archivo
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($tipoArchivo, $tiposPermitidos)) {
                echo json_encode(['error' => 'Tipo de archivo no permitido.']);
                return;
            }

            // Validar tamaño de archivo (ejemplo: 2MB)
            if ($tamanoArchivo > 2 * 1024 * 1024) {
                echo json_encode(['error' => 'El archivo es demasiado grande.']);
                return;
            }

            $nombreArchivo = $idProducto . '_' . basename($_FILES['imagenProducto']['name']);
            $directorioDestino = __DIR__ . '/../../../public/img/' . $nombreArchivo; // Asegúrate de que la ruta es correcta

            if (move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $directorioDestino)) {
                $imagenRuta = $nombreArchivo; // Solo guarda el nombre, no la ruta completa
            } else {
                echo json_encode(['error' => 'Error al subir la imagen.']);
                return;
            }
        }

        $agregarProductos = [
            'idProducto' => $idProducto,
            'fechaIngreso' => $fecha,
            'nombre' => $nombreProducto,
            'modeloSerial' => $modelo,
            'anio' => $anio,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'estado' => $estado,
            'proveedor' => $fabricante,
            'categoria' => $categoria,
            'imagen' => $imagenRuta,
        ];

        $agregarProd = $this->cargarUsuario->agregarProductos($agregarProductos);

        // Verificar si ambas actualizaciones fueron exitosas
        if ($agregarProd) {
            echo json_encode(['success' => true, 'message' => 'Se agrego correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo agregar']);
        }
    }

    public function modificarProductos()
    {
        // Iniciar buffering para evitar salidas no deseadas
        ob_start();

        // Recoger los datos del formulario
        $idProducto = $this->limpiarDatos($_POST['idProductos'] ?? '');
        $fecha = $this->limpiarDatos($_POST['fecha'] ?? '');
        $nombreProducto = $this->limpiarDatos($_POST['nombreProducto'] ?? '');
        $fabricante = $this->limpiarDatos($_POST['fabricante'] ?? '');
        $modelo = $this->limpiarDatos($_POST['modelo'] ?? '');
        $categoria = $this->limpiarDatos($_POST['categoria'] ?? '');
        $anio = $this->limpiarDatos($_POST['anio'] ?? '');
        $cantidad = $this->limpiarDatos($_POST['cantidad'] ?? '');
        $precio = $this->limpiarDatos($_POST['precio'] ?? '');
        $estado = $this->limpiarDatos($_POST['estado'] ?? '');

        // Manejo de la imagen
        $imagenRuta = null;
        if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] === UPLOAD_ERR_OK) {
            $tipoArchivo = $_FILES['imagenProducto']['type'];
            $tamanoArchivo = $_FILES['imagenProducto']['size'];

            // Validar tipo de archivo
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($tipoArchivo, $tiposPermitidos)) {
                echo json_encode(['error' => 'Tipo de archivo no permitido.']);
                return;
            }

            // Validar tamaño de archivo (ejemplo: 2MB)
            if ($tamanoArchivo > 2 * 1024 * 1024) {
                echo json_encode(['error' => 'El archivo es demasiado grande.']);
                return;
            }

            $nombreArchivo = $idProducto . '_' . basename($_FILES['imagenProducto']['name']);
            $directorioDestino = __DIR__ . '/../../../public/img/' . $nombreArchivo;

            if (move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $directorioDestino)) {
                $imagenRuta = $nombreArchivo; // Guardar solo el nombre
            } else {
                echo json_encode(['error' => 'Error al subir la imagen.']);
                return;
            }
        }

        // Construir el arreglo de datos para la modificación
        $modificarProductos = [
            'fechaIngreso' => $fecha,
            'nombre' => $nombreProducto,
            'modeloSerial' => $modelo,
            'anio' => $anio,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'estado' => $estado,
            'proveedor' => $fabricante,
            'categoria' => $categoria,
            'imagen' => $imagenRuta,
            'idProducto' => $idProducto,
        ];

        // Llamar al método DAO para modificar el producto
        $modificarProd = $this->cargarUsuario->modificarProductos($modificarProductos);

        if ($modificarProd) {
            echo json_encode(['success' => true, 'message' => 'Producto modificado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo modificar el producto.']);
        }
    }

    public function eliminarProductos()
    {
        ob_start();

        // Recoger los datos del formulario
        $idProducto = $this->limpiarDatos($_POST['idProducto'] ?? '');

        // Construir el arreglo de datos para la modificación
        $eliminarProductos = [
            'idProducto' => $idProducto,
        ];

        // Llamar al método DAO para modificar el producto
        $eliminarProd = $this->cargarUsuario->eliminarProductos($eliminarProductos);

        if ($eliminarProd) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo modificar el producto.']);
        }
    }

    public function categoria()
    {
        $this->loginController->verificarAcceso(1);

        $categoria = $this->cargarUsuario->obtenerCategorias();

        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Administrador/Productos/categoria.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function agregarCategoria()
    {
        ob_start();

        $fecha = $this->limpiarDatos($_POST['fecha'] ?? '');
        $nombreCate = $this->limpiarDatos($_POST['nombreUsuario1'] ?? '');
        $descripcion = $this->limpiarDatos($_POST['descripcion'] ?? '');


        $agregarCategorias = [
            'fechaIngreso' => $fecha,
            'nombre' => $nombreCate,
            'descripcion' => $descripcion
        ];

        $agregarCate = $this->cargarUsuario->agregarCategorias($agregarCategorias);

        // Verificar si ambas actualizaciones fueron exitosas
        if ($agregarCate) {
            echo json_encode(['success' => true, 'message' => 'Se agrego correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo agregar']);
        }
    }

    public function modificarCategoria()
    {
        ob_start();

        $idCat = $this->limpiarDatos($_POST['idCategoria'] ?? '');
        $fecha = $this->limpiarDatos($_POST['fecha'] ?? '');
        $nombreCate = $this->limpiarDatos($_POST['nombreUsuario1'] ?? '');
        $descripcion = $this->limpiarDatos($_POST['descripcion'] ?? '');


        $modificarCategorias = [
            'fechaIngreso' => $fecha,
            'nombre' => $nombreCate,
            'descripcion' => $descripcion,
            'idCat' => $idCat
        ];

        $modificarCate = $this->cargarUsuario->modificarCategoria($modificarCategorias);

        // Verificar si ambas actualizaciones fueron exitosas
        if ($modificarCate) {
            echo json_encode(['success' => true, 'message' => 'Se actualizo correctamente']);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar']);
        }
    }

    public function eliminarCategoria()
    {
        ob_start();

        // Recoger los datos del formulario
        $idCat = $this->limpiarDatos($_POST['idCategoria'] ?? '');

        // Construir el arreglo de datos para la modificación
        $eliminarCategoria = [
            'idCat' => $idCat,
        ];

        // Llamar al método DAO para modificar el producto
        $eliminarCate = $this->cargarUsuario->eliminarCategoria($eliminarCategoria);

        if ($eliminarCate) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo modificar el producto.']);
        }
    }

    public function pedidoshome()
    {
        $this->loginController->verificarAcceso(1);


        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Administrador/Productos/pedidos.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function caracteristicahome()
    {
        $this->loginController->verificarAcceso(1);

        $caracteristicas = $this->cargarUsuario->obtenerProductos();

        // Pasar los datos a la vista
        $data = [

            'caracteristicas' => $caracteristicas
        ];
        //contenido a mostrar
        $content = __DIR__ . '/../../Views/Administrador/Productos/caracteristicas.php';

        // usar plantilla layaout
        include __DIR__ . '/../../Views/Layaout/VistaAdmin.php';
    }

    public function agregarCaracteristicas()
    {
        ob_start();
        $usuarioModel = $this->cargarUsuario;
        $maxCaracteristicas = 15; // Número máximo de características permitidas
        $caracteristicas = [];
        $success = true; // Variable para controlar el estado de éxito

        for ($i = 0; $i < $maxCaracteristicas; $i++) {
            // Obtener los datos del formulario
            if (isset($_POST['idProducto'][$i]) && isset($_POST['nombreCaracteristica'][$i]) && isset($_POST['valor'][$i])) {
                $usuarioData = [
                    'idProducto' => $this->limpiarDatos($_POST['idProducto'][$i]),
                    'nombreCaracteristica' => $this->limpiarDatos($_POST['nombreCaracteristica'][$i]),
                    'valor' => $this->limpiarDatos($_POST['valor'][$i]),
                    'descripcion' => $this->limpiarDatos($_POST['descripcion'][$i]), // El campo descripción es opcional
                ];

                if (!$usuarioModel->agregarCaracteristicas($usuarioData)) {
                    $success = false; // Si ocurre un error, cambiar el estado
                }
            }
        }

        // Verificar si la inserción fue exitosa
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Se agregaron las características correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudieron agregar algunas características.']);
        }
    }

    // ProductoController.php
    public function mostrarCaracteristicas()
    {
        $usuarioModel = $this->cargarUsuario;
        $idProducto = $_POST['idProducto'] ?? null;

        if ($idProducto) {
            $caracteristicas = $usuarioModel->obtenerCaracteristicasPorProducto($idProducto);
            echo json_encode(['success' => true, 'data' => $caracteristicas]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de producto no proporcionado']);
        }
    }

    public function eliminarCaracteristica()
    {
        $usuarioModel = $this->cargarUsuario;
        $idCaracteristica = $_POST['idCaracteristica'] ?? null;

        if ($idCaracteristica) {
            if ($usuarioModel->eliminarCaracteristica($idCaracteristica)) {
                echo json_encode(['success' => true, 'message' => 'Característica eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la característica']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de característica no proporcionado']);
        }
    }

    public function eliminarCaracteristicaSecundaria()
    {
        $usuarioModel = $this->cargarUsuario;
        $idCaracteristica = $_POST['idCaracteristica'] ?? null;

        if ($idCaracteristica) {
            if ($usuarioModel->eliminarCaracteristicaSecundaria($idCaracteristica)) {
                echo json_encode(['success' => true, 'message' => 'Característica secundaria eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la característica secundaria']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de característica secundaria no proporcionado']);
        }
    }

    // Función para limpiar los datos de entrada
    private function limpiarDatos($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public function generadorId($longitud = 5)
    {
        // Genera la parte numérica aleatoria
        $numero = substr(str_shuffle(str_repeat('0123456789', $longitud)), 0, $longitud);

        // Devuelve el código con el prefijo 'prod'
        return 'prod' . $numero;
    }
}
