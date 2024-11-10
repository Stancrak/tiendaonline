<fieldset>
    <button type="button" name="btnNuevo" id="btnNuevo" class="btn btn-primary" data-toggle="modal" data-target="#opciones">
        <i class="bi bi-check-circle" style="margin-right: 5px;"></i> Agregar Pedido
    </button>
</fieldset>
<br>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6>Gestión de Pedidos</h6>
</div>

<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead class="alert-info" style="position: sticky; top: 0; background-color: #f8f9fa;">
                        <tr style='background-color: blue; color: white;'>
                            <th style="text-align: center;">ID Pedido</th>
                            <th style="text-align: center;">Proveedor</th>
                            <th style="text-align: center;">Fecha Pedido</th>
                            <th style="text-align: center;">Estado</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Prioridad</th>
                            <th style="text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="listaPedidos">
                        <?php if (!empty($pedidosLista)): ?>
                            <?php foreach ($pedidosLista as $pedido): ?>
                                <tr data-id="<?= $pedido['idPedido'] ?>">
                                    <td style="text-align: center;"><?= $pedido['idPedido'] ?></td>
                                    <td style="text-align: center;"><?= $pedido['nombreProveedor'] ?></td>
                                    <td style="text-align: center;"><?= $pedido['fechaPedido'] ?></td>
                                    <td style="text-align: center;"><?= $pedido['estado'] ?></td>
                                    <td style="text-align: center;"><?= $pedido['total'] ?></td>
                                    <td style="text-align: center;"><?= $pedido['prioridad'] ?></td>
                                    <td style="text-align: center;">
                                        <a href="#" onclick="cargarMod('<?= $pedido['idPedido'] ?>')" data-toggle="modal" data-target="#opciones" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn-delete" data-id="<?= $pedido['idPedido'] ?>">
                                            <i class='fas fa-trash-alt' style='color: #dc3545;'></i>
                                        </a>
                                        <!-- Botón para agregar detalles al pedido -->
                                        <a href="#" class="btn-detalles" data-idpedido="<?= $pedido['idPedido'] ?>" data-idproveedor="<?= $pedido['idProveedor'] ?>" data-bs-toggle="modal" data-bs-target="#modalDetalle">
                                            <i class='fas fa-list' style='color: #007bff;'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay pedidos disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de Detalles de Pedido -->
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered" id="tablaDetallesPedido">
                <thead>
                    <tr>
                        <th>ID Detalle</th>
                        <th>ID Pedido</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Subtotal</th> <!-- Nueva columna para subtotal -->
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaDetallesPedidos">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar detalles -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetalleLabel">Agregar Detalle al Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="detalleForm" class="needs-validation" novalidate>
                    <input type="hidden" id="idPedidoDetalle" value="">
                    <input type="hidden" id="idProveedorDetalle" value=""> <!-- Campo oculto para el ID del proveedor -->

                    <div class="mb-3">
                        <label for="producto" class="form-label">Producto</label>
                        <select id="producto" class="form-control" required>
                            <option value="" disabled selected>Seleccionar un producto</option>
                            <!-- Los productos se cargarán aquí dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" id="precio" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento</label>
                        <input type="number" id="descuento" class="form-control" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="subTotal" class="form-label">Subtotal</label>
                        <input type="number" id="subTotal" class="form-control" step="0.01" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fechaCreacion" class="form-label">Fecha de Creación</label>
                        <input type="text" id="fechaCreacion" class="form-control" readonly value="<?php echo date('Y-m-d H:i:s'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnGuardarDetalle">Guardar Detalle</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar pedidos -->
<div class="modal fade" id="opciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-shadow" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-box-open me-2"></i> Registro de Pedidos
                </h5>
                <button id="btnClose" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="pedidoForm" class="needs-validation" novalidate>
                    <input type="hidden" name="idPedido" id="idPedido" value="">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="idProveedor" class="form-label">Proveedor</label>
                            <select id="idProveedor" name="idProveedor" class="form-control" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?= htmlspecialchars($proveedor['idProveedor']) ?>">
                                        <?= htmlspecialchars($proveedor['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaPedido" class="form-label">Fecha de Pedido</label>
                            <input type="date" id="fechaPedido" name="fechaPedido" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="" disabled selected>Seleccionar estado...</option>
                                <?php foreach ($estadosPedido as $estadoPedido): ?>
                                    <option value="<?= htmlspecialchars($estadoPedido) ?>">
                                        <?= ucfirst(htmlspecialchars($estadoPedido)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="total" class="form-label">Total</label>
                            <input type="number"
                                min="1"
                                step="1"
                                id="total"
                                name="total"
                                class="form-control"
                                required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="col-md-4">
                            <label for="fechaEntrega" class="form-label">Fecha de Entrega</label>
                            <input type="date" id="fechaEntrega" name="fechaEntrega" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="metodoPago" class="form-label">Método de Pago</label>
                            <select id="metodoPago" name="metodoPago" class="form-control" required>
                                <option value="" disabled selected>Seleccionar método de pago...</option>
                                <?php foreach ($metodosPago as $metodo): ?>
                                    <option value="<?= htmlspecialchars($metodo) ?>">
                                        <?= ucfirst(htmlspecialchars($metodo)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="prioridad" class="form-label">Prioridad</label>
                            <select id="prioridad" name="prioridad" class="form-control" required>
                                <option value="" disabled selected>Seleccionar prioridad...</option>
                                <?php foreach ($prioridades as $prioridad): ?>
                                    <option value="<?= htmlspecialchars($prioridad) ?>">
                                        <?= ucfirst(htmlspecialchars($prioridad)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="totalImpuesto" class="form-label">Total Impuesto</label>
                            <input type="number" step="0.01" id="totalImpuesto" name="totalImpuesto" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="direccionEntrega" class="form-label">Dirección de Entrega</label>
                            <input type="text" id="direccionEntrega" name="direccionEntrega" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="descuentos" class="form-label">Descuentos</label>
                            <input type="number" step="0.01" id="descuentos" name="descuentos" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
                    </div>
                </form>
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
        </div>
    </div>
</div>

<!-- Script para gestionar la funcionalidad CRUD -->
<script>
    $(document).ready(function() {
        // Variables globales
        const $form = $("#pedidoForm");
        const $btnNuevo = $("#btnNuevo");
        const $btnGuardar = $("#btnGuardar");
        const $btnModificar = $("#btnModificar");
        const $listaPedidos = $("#listaPedidos");

        // Función para calcular el subtotal
        document.getElementById('cantidad').addEventListener('input', calcularSubtotal);
        document.getElementById('precio').addEventListener('input', calcularSubtotal);
        document.getElementById('descuento').addEventListener('input', calcularSubtotal);

        function calcularSubtotal() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio').value) || 0;
            const descuento = parseFloat(document.getElementById('descuento').value) || 0;

            const subTotal = (cantidad * precio) - descuento;
            document.getElementById('subTotal').value = subTotal.toFixed(2);
        }


        $("#proveedoresSelect").on("change", function() {
            const idProveedor = $(this).val();
            if (idProveedor) { // Asegúrate de que hay un valor seleccionado
                cargarProductosPorProveedor(idProveedor);
            }
        });

        // Limpiar formulario
        function limpiarFormulario() {
            $form[0].reset();
            $("#idPedido").val('');
            $form.removeClass('was-validated');
        }

        // Mostrar alertas
        function mostrarAlerta(icon, title, text) {
            return Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: 'Aceptar',
                timer: 2000,
                showConfirmButton: true
            });
        }

        // Evento para nuevo pedido
        $btnNuevo.on("click", function() {
            limpiarFormulario();
            $btnModificar.hide();
            $btnGuardar.show();
        });

        // Guardar nuevo pedido
        $("#btnGuardar").on("click", function(event) {
            event.preventDefault();

            const formData = new FormData($("#pedidoForm")[0]);
            const pedidoData = Object.fromEntries(formData.entries());

            fetch('/tiendaonline/?route=agregarPedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(pedidoData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Éxito', data.message).then(() => location.reload());
                    } else {
                        mostrarAlerta('error', 'Error', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'Ocurrió un problema al guardar el pedido');
                });
        });

        // Cargar pedido para modificar
        window.cargarMod = function(idPedido) {
            fetch('/tiendaonline/?route=obtenerPedidoPorId', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idPedido: idPedido
                    })
                })
                .then(response => response.json())
                .then(pedido => {
                    // Llenar todos los campos del formulario
                    $("#idPedido").val(pedido.idPedido);
                    $("#idProveedor").val(pedido.idProveedor);
                    $("#fechaPedido").val(pedido.fechaPedido);
                    $("#estado").val(pedido.estado);
                    $("#total").val(pedido.total);
                    $("#fechaEntrega").val(pedido.fechaEntrega);
                    $("#metodoPago").val(pedido.metodoPago);
                    $("#direccionEntrega").val(pedido.direccionEntrega);
                    $("#observaciones").val(pedido.observaciones);
                    $("#prioridad").val(pedido.prioridad);
                    $("#totalImpuesto").val(pedido.totalImpuesto);
                    $("#descuentos").val(pedido.descuentos);

                    // Mostrar botón de modificar, ocultar botón de guardar
                    $btnGuardar.hide();
                    $btnModificar.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'No se pudo cargar el pedido');
                });
        }

        // Modificar pedido
        $btnModificar.on("click", function(event) {
            event.preventDefault();

            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            const formData = new FormData($form[0]);
            const pedidoData = Object.fromEntries(formData.entries());

            fetch('/tiendaonline/?route=modificarPedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(pedidoData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Éxito', data.message).then(() => location.reload());
                    } else {
                        mostrarAlerta('error', 'Error', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'Ocurrió un problema al modificar el pedido');
                });
        });

        // Eliminar pedido
        $listaPedidos.on("click", ".btn-delete", function() {
            const idPedido = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=eliminarPedido', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                idPedido: idPedido
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('success', 'Éxito', data.message).then(() => location.reload());
                            } else {
                                mostrarAlerta('error', 'Error', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mostrarAlerta('error', 'Error', 'Ocurrió un problema al eliminar el pedido');
                        });
                }
            });
        });

        // Cargar detalles del pedido
        // Al hacer clic en el botón de detalles, cargar el idPedido y el idProveedor
        // $("#listaPedidos").on("click", ".btn-detalles", function() {
        //     const idPedido = $(this).data('idpedido');
        //     const idProveedor = $(this).data('idproveedor'); // Obtener el ID del proveedor

        //     $("#idPedidoDetalle").val(idPedido); // Guardar el ID del pedido en el campo oculto

        //     // Obtener el proveedor asociado al pedido
        //     const proveedor = $(this).closest('tr').find('td:nth-child(2)').text().trim(); // Obtener el nombre del proveedor


        //     // Cargar productos del proveedor seleccionado
        //     cargarProductosPorProveedor(idProveedor);
        // });

        $("#listaPedidos").on("click", ".btn-detalles", function() {
            const idPedido = $(this).data('idpedido');
            const idProveedor = $(this).data('idproveedor'); // Obtener el ID del proveedor

            $("#idPedidoDetalle").val(idPedido); // Guardar el ID del pedido en el campo oculto
            $("#idProveedorDetalle").val(idProveedor); // Guardar el ID del proveedor en el campo oculto

            // Cargar productos del proveedor seleccionado
            cargarProductosPorProveedor(idProveedor);
        });

        // Función para cargar productos por proveedor
        function cargarProductosPorProveedor(idProveedor) {
            const $productoSelect = $("#producto");
            $productoSelect.empty(); // Limpiar el select de productos

            // Agregar opción de selección
            $productoSelect.append('<option value="" disabled selected>Seleccionar un producto</option>');

            // Hacer la solicitud para obtener los productos del proveedor seleccionado
            fetch('/tiendaonline/?route=obtenerProductosPorProveedor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idProveedor: idProveedor
                    })
                })
                .then(response => response.json())
                .then(productos => {
                    if (productos && productos.length > 0) {
                        productos.forEach(producto => {
                            const option = `
                    <option value="${producto.idProducto}">
                        ${producto.nombre} - $${producto.precio}
                    </option>
                `;
                            $productoSelect.append(option);
                        });
                    } else {
                        $productoSelect.append('<option value="" disabled>No hay productos disponibles</option>');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar productos:', error);
                    mostrarAlerta('error', 'Error', 'No se pudieron cargar los productos');
                });
        }

        // Agregar evento al selector de proveedores
        $("#proveedorDetalle").on("change", function() {
            const idProveedor = $(this).val();
            if (idProveedor) {
                cargarProductosPorProveedor(idProveedor);
            }
        });

        $("#btnGuardarDetalle").on("click", function(event) {
            event.preventDefault();

            const idPedido = $("#idPedidoDetalle").val();
            const idProveedor = $("#idProveedorDetalle").val(); // Obtener el ID del proveedor
            const detalleData = {
                idPedido: idPedido,
                idProveedor: idProveedor, // Incluir el ID del proveedor
                idProducto: $("#producto").val(),
                cantidad: $("#cantidad").val(),
                precio: $("#precio").val(),
                descuento: $("#descuento").val() || 0,
                fechaCreacion: new Date().toISOString() // Usar ISO para la fecha
            };

            // Validaciones
            if (!detalleData.idProducto || !detalleData.cantidad || !detalleData.precio) {
                mostrarAlerta('error', 'Error', 'Por favor, complete todos los campos requeridos.');
                return;
            }

            fetch('/tiendaonline/?route=agregarDetallePedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(detalleData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Éxito', data.message).then(() => {
                            $("#detalleForm")[0].reset(); // Limpiar el formulario
                            $('#modalDetalle').modal('hide'); // Cerrar el modal
                            cargarDetallesPedido(idPedido); // Recargar detalles del pedido
                        });
                    } else {
                        mostrarAlerta('error', 'Error', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'Ocurrió un problema al guardar el detalle');
                });
        });

        // Cargar detalles del pedido
        function cargarDetallesPedido(idPedido) {
            fetch('/tiendaonline/?route=obtenerDetallesPedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idPedido: idPedido
                    })
                })
                .then(response => response.json())
                .then(detalles => {
                    const $listaDetalles = $("#listaDetallesPedidos");
                    $listaDetalles.empty(); // Limpiar la lista de detalles

                    // Iterar sobre los detalles y agregarlos a la tabla
                    detalles.forEach(detalle => {
                        const fila = `
                    <tr>
                        <td>${detalle.idDetallePedido}</td>
                        <td>${detalle.idPedido}</td>
                        <td>${detalle.nombreProducto}</td>
                        <td>${detalle.cantidad}</td>
                        <td>${detalle.precio}</td>
                        <td>${detalle.descuento}</td>
                        <td>${detalle.fechaCreacion}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="cargarDetalleMod(${detalle.idDetallePedido})">Modificar</button>
                            <button class="btn btn-danger btn-sm btn-delete-detalle" data-id="${detalle.idDetallePedido}">Eliminar</button>
                        </td>
                    </tr>
                `;
                        $listaDetalles.append(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'No se pudieron cargar los detalles del pedido');
                });
        }

        // Cargar detalle para modificar
        window.cargarDetalleMod = function(idDetalle) {
            fetch('/tiendaonline/?route=obtenerDetallePorId', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idDetalle: idDetalle
                    })
                })
                .then(response => response.json())
                .then(detalle => {
                    // Llenar los campos del formulario de detalles
                    $("#idDetalle").val(detalle.idDetalle);
                    $("#producto").val(detalle.producto);
                    $("#cantidad").val(detalle.cantidad);
                    $("#precio").val(detalle.precio);
                    $("#descuento").val(detalle.descuento);

                    // Mostrar botón de modificar, ocultar botón de agregar
                    $("#btnAgregarDetalle").hide(); // Asegúrate de que este botón existe
                    $("#btnModificarDetalle").show(); // Asegúrate de que este botón existe
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'No se pudo cargar el detalle');
                });
        }

        // Cargar detalle para modificar
        window.cargarDetalleMod = function(idDetalle) {
            fetch('/tiendaonline/?route=obtenerDetallePorId', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idDetalle: idDetalle
                    })
                })
                .then(response => response.json())
                .then(detalle => {
                    // Llenar los campos del formulario de detalles
                    $("#idDetalle").val(detalle.idDetalle);
                    $("#idProducto").val(detalle.idProducto);
                    $("#cantidad").val(detalle.cantidad);
                    $("#precio").val(detalle.precio);
                    $("#descuento").val(detalle.descuento);

                    // Mostrar botón de modificar, ocultar botón de agregar
                    $("#btnAgregarDetalle").hide(); // Asegúrate de que este botón existe
                    $("#btnModificarDetalle").show(); // Asegúrate de que este botón existe
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'No se pudo cargar el detalle');
                });
        }

        // Modificar detalle
        $("#btnModificarDetalle").on("click", function(event) {
            event.preventDefault();

            const formData = new FormData($("#detalleForm")[0]); // Asegúrate de que este formulario existe
            const detalleData = Object.fromEntries(formData.entries());

            fetch('/tiendaonline/?route=modificarDetallePedido', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(detalleData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('success', 'Éxito', data.message).then(() => {
                            cargarDetallesPedido(detalleData.idPedido); // Recargar detalles del pedido
                            $('#modalDetalle').modal('hide'); // Cerrar el modal
                        });
                    } else {
                        mostrarAlerta('error', 'Error', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('error', 'Error', 'Ocurrió un problema al modificar el detalle');
                });
        });

        // Eliminar detalle de pedido
        $("#listaDetallesPedidos").on("click", ".btn-delete-detalle", function() {
            const idDetalle = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/tiendaonline/?route=eliminarDetallePedido', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                idDetalle: idDetalle
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('success', 'Éxito', data.message).then(() => {
                                    cargarDetallesPedido(data.idPedido); // Asegúrate de que idPedido está presente en data
                                });
                            } else {
                                mostrarAlerta('error', 'Error', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mostrarAlerta('error', 'Error', 'Ocurrió un problema al eliminar el detalle');
                        });
                }
            });
        });
    });
</script>

<!-- Cierre de la vista -->
</body>