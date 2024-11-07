<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar la sesión solo si no hay una activa
}

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: /mvcPrueba/?route=login'); // Redirige si no está autenticado
    exit;
}

// Asigna los valores de sesión a variables
$nombreUsuario = $_SESSION['usuario'];
$rolUsuario = $_SESSION['rol'];
$nivelUsuario = $_SESSION['nivel'];
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Menu principal</title>

    <!-- Custom fonts for this template-->
    <link href="/mvcPrueba/public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/mvcPrueba/public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/mvcPrueba/public/css/estilo.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        function actualizarHora() {
            // Obtener la fecha y hora actual
            const ahora = new Date();

            // Formatear la hora en HH:mm:ss
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            const horaFormateada = `${horas}:${minutos}:${segundos}`;

            // Asignar la hora al elemento con el id "horaActual"
            document.getElementById('horaActual').textContent = horaFormateada;
        }

        // Ejecutar la función cada segundo (1000 ms)
        setInterval(actualizarHora, 1000);
    </script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-gamepad"></i>

                </div>
                <div class="sidebar-brand-text mx-3">Zona Gamer </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="?route=home">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu zona gamer
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Usuarios</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Opciones:</h6>
                        <a class="collapse-item" href="?route=nivel3Usuario">Agregar Visitas</a>
                        <a class="collapse-item" href="?route=nivel3Espera">Usuarios en espera</a>
                        <a class="collapse-item" href="?route=nivel3Tiempo">Tiempo</a>
                        <a class="collapse-item" href="?route=nivel3BuscarLista">Lista Usuarios</a>

                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Reportes</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gestion de Reportes:</h6>
                        <a class="collapse-item" href="?route=nivel3RptDiario">Reportes Diarios</a>
                        <a class="collapse-item" href="?route=nivel3RptGeneral">Reporte General</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="horaActual"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuNuWLCLLcxIOHZWSOPeGrmiBLf_fA9Cxqvw&s" alt="Imagen de usuario" class="user-logo" width="50" height="40">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <span class="dropdown-item"><i class="fas fa-user mr-2"></i>Tecnico: <?php echo htmlspecialchars($nombreUsuario); ?></span>
                                <span class="dropdown-item"><i class="fas fa-level-up-alt mr-2"></i> Nivel: <?php echo htmlspecialchars("3"); ?></span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/mvcPrueba/?route=logout"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión</a>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 order-2 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <?php
                                    if (isset($content)) {
                                        include $content;
                                    } else {
                                        echo "<p>No se ha especificado ningún contenido.</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        ,The_Alexander
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="/mvcPrueba/public/vendor/jquery/jquery.min.js"></script>
    <script src="/mvcPrueba/public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/mvcPrueba/public/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/mvcPrueba/public/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="/mvcPrueba/public/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="/mvcPrueba/public/js/demo/chart-area-demo.js"></script>
    <script src="/mvcPrueba/public/js/demo/chart-pie-demo.js"></script>

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


</body>

</html>