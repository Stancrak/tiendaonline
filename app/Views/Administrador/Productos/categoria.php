<form id="usuarioForm" class="needs-validation" novalidate>
    <div class="card-title mb-0">
        <h6 class="m-0 me-2">Gestion de categorias</h6>
    </div>
    <br>

    <div class="row mb-4">
        <!--Fecha-->
        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
        </div>
        <!--Usuario-->
        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="nombreUsuario1">Categoria</label>
                <input type="text" id="nombreUsuario1" name="nombreUsuario1" class="form-control">
                <div class="invalid-feedback">Ingrese el usuario</div>
            </div>
        </div>
        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="descripcion">Descripcion</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control">
                <div class="invalid-feedback">Ingrese el usuario</div>
            </div>
        </div>

    </div>
    <input type="hidden" name="idCategoria" id="idCategoria" value="" />

    <div class="row mb-4">
        <div class="col-12">
            <button type="button" class="btn btn-secondary" id="btnGuardar">Guardar Cambios</button>
            <button type="button" class="btn btn-primary d-none" id="btnModificar">Modificar</button>
            <button type="button" class="btn btn-primary d-none" id="btnEliminar">Eliminar</button>
            <button type="button" class="btn btn-danger d-none" id="btnCancelar">Cancelar</button>
        </div>
    </div>

</form>

<div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
    <table class="table table-bordered">
        <thead class="alert-info" style="position: sticky; top: 0; background-color: #f8f9fa;">
            <tr style='background-color: blue; color: white;'>
                <th style="text-align: center;">Fecha</th>
                <th style="text-align: center;">Categoria</th>
                <th style="text-align: center;">Descripcion</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: center;">Accion</th>

            </tr>
        </thead>
        <tbody id="listaUsuario">
            <?php if (!empty($categoria)): ?>
                <?php foreach ($categoria as $cat): ?>
                    <tr>

                        <td style="text-align: center;"><?= $cat['fechaIngreso'] ?></td>
                        <td style="text-align: center;"><?= $cat['nombre'] ?></td>
                        <td style="text-align: center;"><?= $cat['descripcion'] ?></td>
                        <td style="text-align: center;"><?= $cat['estado'] ?></td>
                        <td style="text-align: center;">
                            <a href="#"
                                onclick="cargarMod('<?= $cat['idCat'] ?>', 
                                                    '<?= $cat['fechaIngreso'] ?>', 
                                                    '<?= $cat['nombre'] ?>', 
                                                    '<?= $cat['descripcion'] ?>')"

                                data-toggle="modal" data-target="#opciones" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#"
                                onclick="cargarElim('<?= $cat['idCat'] ?>', 
                                                    '<?= $cat['fechaIngreso'] ?>', 
                                                    '<?= $cat['nombre'] ?>', 
                                                    '<?= $cat['descripcion'] ?>')"

                                data-toggle="modal" data-target="#opciones" class="btn-delete">
                                <i class="fas fa-trash-alt" style='color: #dc3545;'></i>
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No hay productos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        // Función para obtener la fecha actual en formato yyyy-mm-dd
        function obtenerFechaActual() {
            const hoy = new Date();
            const año = hoy.getFullYear();
            const mes = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes con 2 dígitos
            const dia = String(hoy.getDate()).padStart(2, '0'); // Día con 2 dígitos
            return `${año}-${mes}-${dia}`; // Formato: yyyy-mm-dd
        }

        // Configurar los botones entre "Guardar" y "Modificar"
        function configurarBotones(guardarVisible, modificarVisible, eliminarVisible) {
            $("#btnGuardar").toggleClass("d-none", !guardarVisible); // Muestra/oculta el botón "Guardar"
            $("#btnModificar").toggleClass("d-none", !modificarVisible); // Muestra/oculta el botón "Modificar"
            $("#btnEliminar").toggleClass("d-none", !eliminarVisible); // Muestra/oculta el botón "Eliminar"

            // Mostrar "Cancelar" si estamos en modo modificar o eliminar
            const mostrarCancelar = modificarVisible || eliminarVisible; // Mostrar "Cancelar" si modificar o eliminar
            $("#btnCancelar").toggleClass("d-none", !mostrarCancelar); // Muestra/oculta el botón "Cancelar"
        }


        // Limpiar el formulario y establecer la fecha actual
        function limpiarFormulario() {
            $("#usuarioForm")[0].reset(); // Resetear formulario
            $("#idProductos").val(''); // Limpiar ID oculto
            $("#fecha").val(obtenerFechaActual()); // Establecer fecha actual
        }

        // Validar que los campos no estén vacíos
        function validarFormulario() {
            const fecha = $("#fecha").val().trim();
            const nombre = $("#nombreUsuario1").val().trim();
            const descripcion = $("#descripcion").val().trim();

            if (!fecha || !nombre || !descripcion) {
                mostrarAlerta('warning', 'Campos vacíos', 'Por favor, complete todos los campos.');
                return false;
            }
            return true;
        }

        // Mostrar alertas con SweetAlert
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

        // Guardar nuevos cambios
        $("#btnGuardar").on("click", function(event) {
            event.preventDefault(); // Evitar recarga
            if (!validarFormulario()) return; // Validación previa

            const formData = new FormData($("#usuarioForm")[0]);

            fetch('/tiendaonline/?route=agregarCategoria', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Guardado', data.message)
                            .then(() => location.reload()); // Recargar la página
                    } else {
                        mostrarAlerta('error', 'Error', data.message);
                    }
                })
                .catch(() => {
                    mostrarAlerta('error', 'Error', 'Ocurrió un error al enviar los datos.');
                });
        });

        $("#btnModificar").on("click", function(event) {
            event.preventDefault(); // Evita el envío automático del formulario

            if (!validarFormulario()) return; // Validación antes de continuar

            const formData = new FormData(document.getElementById('usuarioForm'));

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Desea modificar la categoria!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Modificar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=modificarCategoria', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('success', 'Actualizado', data.message)
                                    .then(() => location.reload()); // Recargar página
                            } else {
                                mostrarAlerta('error', 'Error', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al modificar categoria', 'error');
                        });
                }
            });
        });

        $("#btnEliminar").on("click", function(event) {
            event.preventDefault(); // Evita el envío automático del formulario

            if (!validarFormulario()) return; // Validación antes de continuar

            const formData = new FormData(document.getElementById('usuarioForm'));

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Desea Eliminar la categoria!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=eliminarCategoria', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('success', 'Eliminar', data.message)
                                    .then(() => location.reload()); // Recargar página
                            } else {
                                mostrarAlerta('error', 'Error', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al modificar categoria', 'error');
                        });
                }
            });
        });

        // Cargar datos para modificar un registro
        window.cargarMod = function(id, fecha, nombre, descripcion) {
            $("#idCategoria").val(id);
            $("#fecha").val(fecha); // Cargar la fecha del registro seleccionado
            $("#nombreUsuario1").val(nombre);
            $("#descripcion").val(descripcion);

            configurarBotones(false, true,false); // Mostrar "Modificar" y "Cancelar", ocultar "Guardar"
        };

        // Cargar datos para modificar un registro
        window.cargarElim = function(id, fecha, nombre, descripcion) {
            $("#idCategoria").val(id);
            $("#fecha").val(fecha); // Cargar la fecha del registro seleccionado
            $("#nombreUsuario1").val(nombre);
            $("#descripcion").val(descripcion);

            configurarBotones(false, false,true); // Mostrar "Modificar" y "Cancelar", ocultar "Guardar"
        };

        // Acción al cancelar
        $("#btnCancelar").on("click", function() {
            limpiarFormulario(); // Limpiar formulario
            configurarBotones(true, false,false); // Mostrar "Guardar"
        });

        // Asegurarse de limpiar formulario y cargar fecha actual al cerrar el modal
        $('#opciones').on('hidden.bs.modal', function() {
            limpiarFormulario(); // Limpiar formulario al cerrar el modal
            configurarBotones(true, false,false); // Mostrar "Guardar"
        });

        // Establecer la fecha actual al cargar la página
        $("#fecha").val(obtenerFechaActual());
    });
</script>