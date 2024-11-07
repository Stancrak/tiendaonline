<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" />

<section class="vh-100" style="background-color: #CEBAAA;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                            <img src="/tiendaonline/public/img/mario.jpg"
                                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">

                                <form id="loginForm">
                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Bienvenidos</h5>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <div class="form-outline">
                                            <input type="text" id="txtCorreo" class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario">Usuario</label>
                                        </div>
                                        <div class="text-danger mt-1" id="correoError" style="display: none;">Por favor ingrese su usuario</div>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <div class="form-outline">
                                            <input type="password" id="passwords" name="passwords" class="form-control form-control-lg" />
                                            <label class="form-label" for="passwords">Contraseña</label>
                                        </div>
                                        <div class="text-danger mt-1" id="claveError" style="display: none;">Por favor ingrese su contraseña</div>
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <input type="submit" class="btn btn-dark btn-lg btn-block" value="Iniciar Sesión">
                                    </div>

                                    <a class="small text-primary mb-3 d-block" href="#!">¿Has olvidado la contraseña?</a>

                                    <div class="pt-1 mb-4">
                                        <button data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-lg btn-block" type="button"
                                            style="background-color: #EDD27C; color: #1C2833;">
                                            Crear Cuenta
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function mostrarAlerta(icon, title, text) {
        return Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'Aceptar',
            showConfirmButton: false,
            timer: 2000,
            backdrop: false
        });
    }

    // Función para limpiar campos
    function limpiarCampos() {
        $('#txtCorreo').val('');
        $('#passwords').val('');
    }

    // Función para mostrar/ocultar errores de campo
    function manejarErrores(inputSelector, errorSelector) {
        $(inputSelector).on('blur', function() {
            if (!$(this).val()) {
                $(errorSelector).fadeIn();
            }
        });

        $(inputSelector).on('input', function() {
            if ($(this).val()) {
                $(errorSelector).fadeOut();
            }
        });
    }

    // Función para validar campos antes de enviar el formulario
    function validarCampos() {
        var correo = $('#txtCorreo').val().trim();
        var password = $('#passwords').val().trim();

        if (!correo || !password) {
            mostrarAlerta('warning', 'Campos Vacíos', 'Por favor complete todos los campos.');
            return false; // Detenemos el envío si hay campos vacíos
        }
        return true; // Los campos están completos
    }

    $(document).ready(function() {
        // Limpiar los campos al cargar la página
        limpiarCampos();

        // Manejar errores en los campos
        manejarErrores('#txtCorreo', '#correoError');
        manejarErrores('#passwords', '#claveError');

        // Manejo del envío del formulario
        $('#loginForm').submit(function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

            // Validamos los campos antes de enviar
            if (!validarCampos()) return; // Si hay campos vacíos, no se envía el formulario

            var usuario = $('#txtCorreo').val();
            var password = $('#passwords').val();

            // Envío de datos mediante AJAX
            $.ajax({
                url: '?route=authenticate',
                type: 'POST',
                data: {
                    usuario: usuario,
                    passwords: password
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    } else {
                        mostrarAlerta('error', 'Error de Autenticación', response.message);
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error de Servidor', 'Ocurrió un error en el servidor. Intente de nuevo.');
                }
            });
        });
    });
</script>

