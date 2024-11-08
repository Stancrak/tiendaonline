<fieldset>
    <button type="button" name="btnNuevo" id="btnNuevo" class="btn btn-primary" data-toggle="modal" data-target="#opciones">
        <i class="bi bi-check-circle" style="margin-right: 5px;"></i> Agregar Producto
    </button>

</fieldset>
<br>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6>Gestion de productos</h6>
</div>

<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-bordered">
        <thead class="alert-info" style="position: sticky; top: 0; background-color: #f8f9fa;">
            <tr style='background-color: blue; color: white;'>
                <th style="text-align: center;">Imagen</th>
                <th style="text-align: center;">Producto</th>
                <th style="text-align: center;">Modelo</th>
                <th style="text-align: center;">Proveedor</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: center;">Precio</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: center;">Accion</th>
            </tr>
        </thead>
        <tbody id="listaUsuario">
            <?php if (!empty($productoLista)): ?>
                <?php foreach ($productoLista as $productoList): ?>
                    <tr>
                        <td style="text-align: center;">
                            <?php if (!empty($productoList['img'])): ?> <!-- Verifica si la imagen está disponible -->
                                <img src="/tiendaonline/public/img/<?= htmlspecialchars($productoList['img']) ?>" alt="Imagen del Producto" style="max-width: 50px; max-height: 50px;" /> <!-- Tamaño de la imagen -->
                            <?php else: ?>
                                <span>No disponible</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?= $productoList['nombre'] ?></td>
                        <td style="text-align: center;"><?= $productoList['modelo'] ?></td>
                        <td style="text-align: center;"><?= $productoList['fabricante'] ?></td>
                        <td style="text-align: center;"><?= $productoList['cantidad'] ?></td>
                        <td style="text-align: center;"><?= $productoList['precio'] ?></td>
                        <td style="text-align: center;"><?= $productoList['estado'] ?></td>

                        <td style="text-align: center;">
                            <a href="#"
                                onclick="cargarMod('<?= $productoList['idProducto'] ?>', 
                                                    
                                                    '<?= $productoList['nombre'] ?>', 
                                                    '<?= $productoList['idProveedor'] ?>', 
                                                    '<?= $productoList['modelo'] ?>', 
                                                    '<?= $productoList['idCat'] ?>', 
                                                    '<?= $productoList['anio'] ?>',
                                                    '<?= $productoList['fechaIngreso'] ?>',
                                                    
                                                    '<?= $productoList['cantidad'] ?>', 
                                                    '<?= $productoList['precio'] ?>', 
                                                    '<?= $productoList['idEstado'] ?>', 
                                                    '<?= "/tiendaonline/public/img/" . htmlspecialchars($productoList['img']) ?>')"


                                data-toggle="modal" data-target="#opciones" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn-delete" data-idd="<?= $productoList['idProducto'] ?>" data-toggle="modal" data-target="#editModal2">
                                <i class='fas fa-trash-alt' style='color: #dc3545;'></i>
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay productos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="opciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-shadow" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-box-open me-2"></i> Registro de Productos
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="productoForm" class="needs-validation" novalidate enctype="multipart/form-data">
                    <!-- Tarjeta de Datos del Producto -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Datos del Producto</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                                    <div class="invalid-feedback">Por favor, ingresa una fecha.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="nombreProducto" class="form-label">Nombre del Producto</label>
                                    <input type="text" id="nombreProducto" name="nombreProducto" class="form-control" required>
                                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="modelo" class="form-label">Modelo Serial</label>
                                    <input type="text" id="modelo" name="modelo" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="anio" class="form-label">Año</label>
                                    <input type="number" id="anio" name="anio" class="form-control" required>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <select id="categoria" name="categoria" class="form-control" required>
                                        <option value="" disabled selected>Seleccionar</option>
                                        <?php foreach ($data['categoria'] as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria['idCat']); ?>">
                                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="cantidad" class="form-label">Cantidad</label>
                                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="number" step="0.01" id="precio" name="precio" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select id="estado" name="estado" class="form-control" required>
                                        <option value="" disabled selected>Seleccionar</option>
                                        <?php foreach ($data['estadoProductos'] as $estados): ?>
                                            <option value="<?php echo htmlspecialchars($estados['idEstado']); ?>">
                                                <?php echo htmlspecialchars($estados['estado']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label for="fabricante" class="form-label">Proveedor</label>
                                    <select id="fabricante" name="fabricante" class="form-control" required>
                                        <option value="" disabled selected>Seleccionar</option>
                                        <?php foreach ($data['fabricante'] as $fabricante): ?>
                                            <option value="<?php echo htmlspecialchars($fabricante['idProveedor']); ?>">
                                                <?php echo htmlspecialchars($fabricante['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="imagenProducto" class="form-label">Imagen del Producto</label>
                                    <input type="file" id="imagenProducto" name="imagenProducto" class="form-control" accept="image/*" required>
                                    <div class="invalid-feedback">Por favor, selecciona una imagen.</div>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                                    <div class="text-center preview-container">
                                        <img id="preview" class="img-fluid" alt="Vista previa" style="max-width: 150px;" />
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="idProductos" id="idProductos" value="" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btnGuardar">
                            <i class="fas fa-save me-2"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-info" id="btnModificar">
                            <i class="fas fa-edit me-2"></i> Modificar
                        </button>
                        <button type="button" class="btn btn-secondary" id="btnCancelar" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Función para mostrar alertas con SweetAlert
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

    // Validación del formulario antes de enviar
    function validarFormulario() {
        const nombre = document.getElementById('nombreProducto').value.trim();
        const fabricante = document.getElementById('fabricante').value;
        const modelo = document.getElementById('modelo').value;
        const categoria = document.getElementById('categoria').value;
        const anio = document.getElementById('anio').value;
        const cantidad = document.getElementById('cantidad').value;
        const precio = document.getElementById('precio').value;
        const estado = document.getElementById('estado').value;
        const img = document.getElementById('imagenProducto').value;


        if (nombre === '' || fabricante === '' || modelo === '' || categoria === '' || categoria === '' || anio === '' || cantidad === '' || precio === '' || estado === '' || img === '') {
            mostrarAlerta('warning', 'Campos vacíos', 'Por favor, complete todos los campos.');
            return false; // Bloquea el flujo si hay campos vacíos
        }
        return true; // Permite continuar si todos los campos están completos
    }

    // Función para limpiar los campos del formulario
    function limpiarFormulario() {
        $("#idProductos").val('');
        $("#nombreProducto").val('');
        $("#fabricante").val('');
        $("#modelo").val('');
        $("#categoria").val('');
        $("#anio").val('');
        $("#cantidad").val('');
        $("#precio").val('');
        $("#estado").val('');
        $("#imagenProducto").val('');
        $("#preview").attr("src", "");
    }

    // Mostrar u ocultar los botones según sea necesario
    function configurarBotones(guardarVisible, modificarVisible) {
        $("#btnGuardar").toggle(guardarVisible);
        $("#btnModificar").toggle(modificarVisible);
    }

    $("#imagenProducto").on("change", function(event) {
        const file = event.target.files[0];
        const preview = $("#preview");

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr("src", e.target.result);
                preview.removeClass("d-none");
            };
            reader.readAsDataURL(file);
        } else {
            preview.addClass("d-none");
        }
    });

    $(document).ready(function() {
        // Al abrir el modal para agregar un nuevo registro
        $("#btnNuevo").on("click", function() {
            limpiarFormulario(); // Limpia los campos del formulario
            configurarBotones(true, false); // Muestra "Guardar", oculta "Modificar"
        });

        // Al hacer clic en el botón Guardar
        $("#btnGuardar").on("click", function() {
            event.preventDefault();
            if (!validarFormulario()) return; // Validación antes de continuar

            const formData = new FormData(document.getElementById('productoForm'));

            fetch('/tiendaonline/?route=agregarProducto', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Guardado', data.message)
                            .then(() => location.reload()); // Recargar página
                    } else {
                        mostrarAlerta('error', 'Error', data.message);
                    }
                })
                .catch(error => {
                    mostrarAlerta('error', 'Error', 'Ocurrió un error al enviar los datos.');
                });
        });

        $("#btnModificar").on("click", function(event) {
            event.preventDefault(); // Evita el envío automático del formulario

            if (!validarFormulario()) return; // Validación antes de continuar

            const formData = new FormData(document.getElementById('productoForm'));

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Desea modificar el registro del producto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Modificar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=modificarProductos', {
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
                            Swal.fire('Error', 'Ocurrió un error al modificar el producto.', 'error');
                        });
                }
            });
        });

        $(".btn-delete").on("click", function(event) {
            event.preventDefault(); // Evita el envío automático del formulario

            const idControl = this.getAttribute('data-idd');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Desea eliminar este producto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=eliminarProductos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'idProducto': idControl
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('success', 'Eliminado', data.message)
                                    .then(() => location.reload()); // Recargar página
                            } else {
                                mostrarAlerta('error', 'Error', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Ocurrió un error al modificar el producto.', 'error');
                        });
                }
            });
        });


        // Al cargar un registro para modificar
        window.cargarMod = function(id, nombre, idFabricante, modelo, categoria, anio, fecha, cantidad, precio, estado, img) {
            $("#idProductos").val(id);
            $("#nombreProducto").val(nombre);
            $("#fabricante").val(idFabricante);
            $("#modelo").val(modelo);
            $("#categoria").val(categoria);
            $("#anio").val(anio);
            $("#fecha").val(fecha);

            $("#cantidad").val(cantidad);
            $("#precio").val(precio);
            $("#estado").val(estado);

            const preview = $("#preview");
            const nombreImagen = $("#nombreImagen");

            // Mostrar vista previa de la imagen
            if (img) {
                preview.attr("src", img);
                preview.removeClass("d-none");
                // Mostrar el nombre de la imagen en el <span>
                nombreImagen.text(img.split('/').pop()); // Extraer solo el nombre del archivo
            } else {
                preview.attr("src", "");
                preview.addClass("d-none");
                nombreImagen.text(''); // Limpiar el nombre si no hay imagen
            }

            configurarBotones(false, true); // Muestra "Modificar", oculta "Guardar"
        };

        // Cerrar modal al cancelar
        $("#btnCancelar").on("click", function() {
            $('#opciones').modal('hide');
        });

        // Asegurarse de que el modal esté limpio cada vez que se abre
        $('#opciones').on('hidden.bs.modal', function() {
            limpiarFormulario(); // Limpia el formulario al cerrar el modal
            configurarBotones(true, false); // Por defecto: Mostrar "Guardar"
        });
    });
</script>

<script src="/tiendaonline/public/js/fechaactual.js"></script>