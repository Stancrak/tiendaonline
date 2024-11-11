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
                                        <a href="#" class="btn-detalles" data-idpedido="<?= $pedido['idPedido'] ?>" data-idproveedor="<?= $pedido['idProveedor'] ?>" data-bs-toggle="modal" data-bs-target="#modalDetalles">
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
            <label for="idProveedor" class="form-label">Proveedor</label>
            <select id="idProveedor" name="idProveedor" class="form-control" required>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= htmlspecialchars($proveedor['idProveedor']) ?>">
                        <?= htmlspecialchars($proveedor['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

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
<!-- Modal para agregar/editar detalles de pedido -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetallesLabel">
                    <i class="fas fa-list-alt me-2"></i> Gestión de Detalles de Pedido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Información del Pedido -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">ID Pedido</label>
                        <input type="text" id="idPedidoDetalle" class="form-control" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Proveedor</label>
                        <select id="proveedorDetalle" class="form-control" disabled>
                            <option>Seleccionar Proveedor</option>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <option value="<?= $proveedor['idProveedor'] ?>">
                                    <?= $proveedor['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Formulario para Agregar/Editar Detalle -->
                <form id="formDetallesPedido" class="needs-validation" novalidate>
                    <input type="hidden" id="idDetallePedido" name="idDetallePedido">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="productoDetalle" class="form-label">Producto</label>
                            <select class="form-select" id="producto" name="idProducto" required>
                                <option value="" disabled selected>Seleccionar un producto</option>
                                <!-- Los productos se cargarán aquí dinámicamente -->
                            </select>
                            <div class="invalid-feedback">Seleccione un producto</div>
                        </div>

                        <div class="col-md-2">
                            <label for="cantidadDetalle" class="form-label">Cantidad</label>
                            <input type="number"
                                id="cantidadDetalle"
                                name="cantidad"
                                class="form-control"
                                min="1"
                                required>
                            <div class="invalid-feedback">Cantidad inválida</div>
                        </div>

                        <div class="col-md-2">
                            <label for="precioDetalle" class="form-label">Precio</label>
                            <input type="number"
                                id="precioDetalle"
                                name="precio"
                                class="form-control"
                                step="0.01"
                                min="0"
                                required>
                            <div class="invalid-feedback">Precio inválido</div>
                        </div>

                        <div class="col-md-2">
                            <label for="descuentoDetalle" class="form-label">Descuento</label>
                            <input type="number"
                                id="descuentoDetalle"
                                name="descuento"
                                class="form-control"
                                step="0.01"
                                min="0">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btnAgregarDetalle" class="btn btn-success me-2">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                            <button type="button" id="btnActualizarDetalle" class="btn btn-warning" style="display:none;">
                                <i class="fas fa-sync"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabla de Detalles -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped" id="tablaDetallesPedido">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="listaDetallesPedido">
                            <!-- Detalles se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Resumen de Pedido -->
                <div class="row mt-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Resumen del Pedido</h5>
                                <div class="row">
                                    <div class="col-6">Total Items:</div>
                                    <div class="col-6" id="totalItemsResumen">0</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Subtotal:</div>
                                    <div class="col-6" id="subtotalResumen">$0.00</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Descuentos:</div>
                                    <div class="col-6" id="descuentosResumen">$0.00</div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Total:</strong></div>
                                    <div class="col-6"><strong id="totalResumen">$0.00</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardarDetalles">
                    <i class="fas fa-save me-2"></i>Guardar Detalles
                </button>
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
        const $modalDetalles = $('#modalDetalles');
        const $formDetalles = $('#formDetallesPedido');
        const $tablaDetalles = $('#tablaDetallesPedido');
        const $listaDetalles = $('#listaDetallesPedido');

        // Funciones de Inicialización
        function inicializarModalDetalles() {
            // Resetear formulario
            $formDetalles[0].reset();
            $('#idDetallePedido').val('');
            $('#btnAgregarDetalle').show();
            $('#btnActualizarDetalle').hide();
        }

        // Evento para nuevo pedido
        $btnNuevo.on("click", function() {
            limpiarFormulario();
            $btnModificar.hide();
            $btnGuardar.show();
        });

        // Limpiar formulario
        function limpiarFormulario() {
            $form[0].reset();
            $("#idPedido").val('');
            $form.removeClass('was-validated');
        }

        function calcularSubtotal() {
            const cantidad = parseFloat($('#cantidadDetalle').val()) || 0;
            const precio = parseFloat($('#precioDetalle').val()) || 0;
            const descuento = parseFloat($('#descuentoDetalle').val()) || 0;

            const subtotal = (cantidad * precio) - descuento;
            return subtotal.toFixed(2);
        }

        function actualizarResumenPedido() {
            const detalles = [];
            let totalItems = 0;
            let subtotal = 0;
            let descuentoTotal = 0;

            $('#listaDetallesPedido tr').each(function() {
                const cantidad = parseFloat($(this).find('td:nth-child(3)').text()) || 0;
                const precio = parseFloat($(this).find('td:nth-child(4)').text()) || 0;
                const descuento = parseFloat($(this).find('td:nth-child(5)').text()) || 0;
                const subtotalDetalle = parseFloat($(this).find('td:nth-child(6)').text()) || 0;

                totalItems += cantidad;
                subtotal += precio * cantidad;
                descuentoTotal += descuento;
            });

            $('#totalItemsResumen').text(totalItems.toFixed(2));
            $('#subtotalResumen').text(`$${subtotal.toFixed(2)}`);
            $('#descuentosResumen').text(`$${descuentoTotal.toFixed(2)}`);
            $('#totalResumen').text(`$${(subtotal - descuentoTotal).toFixed(2)}`);
        }

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


        // Eventos para el Modal de Detalles
        // Abrir modal de detalles

        // Función para cargar productos por proveedor
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
                        productos.forEach(function(producto) {
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

        // Evento para cargar productos cuando se selecciona un proveedor
        $('.btn-detalles').on('click', function() {
            const idPedido = $(this).data('idpedido');
            const idProveedor = $(this).data('idproveedor');

            // Setear valores iniciales
            $('#idPedidoDetalle').val(idPedido);
            $('#proveedorDetalle').val(idProveedor);

            // Cargar productos del proveedor
            cargarProductosPorProveedor(idProveedor);

            // Cargar detalles existentes
            cargarDetallesPedido(idPedido);
        });

        // Función para cargar detalles del pedido
        // function cargarDetallesPedido(idPedido) {
        //     $.ajax({
        //         url: '/tiendaonline/?route=obtenerDetallesPedido',
        //         method: 'POST',
        //         data: JSON.stringify({
        //             idPedido: idPedido
        //         }),
        //         contentType: 'application/json',
        //         success: function(response) {
        //             const detalles = JSON.parse(response);
        //             const tbody = $('#tablaDetalles tbody');
        //             tbody.empty();
        //             detalles.forEach(function(detalle) {
        //                 tbody.append(`
        //                 <tr>
        //                     <td>${detalle.idDetallePedido}</td>
        //                     <td>${detalle.nombreProducto}</td>
        //                     <td>${detalle.cantidad}</td>
        //                     <td>${detalle.precio}</td>
        //                     <td>${detalle.descuento}</td>
        //                     <td>${detalle.subTotal}</td>
        //                     <td>
        //                         <button class="btn btn-sm btn-warning editarDetalle" data-id="${detalle.idDetallePedido}">Editar</button>
        //                         <button class="btn btn-sm btn-danger eliminarDetalle" data-id="${detalle.idDetallePedido}">Eliminar</button>
        //                     </td>
        //                 </tr>
        //             `);
        //             });
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error al cargar detalles:', error);
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error',
        //                 text: 'Error al cargar los detalles del pedido'
        //             });
        //         }
        //     });
        // }

        function cargarDetallesPedido(idPedido) {
            $.ajax({
                url: '/tiendaonline/?route=obtenerDetallesPedido',
                method: 'POST',
                data: JSON.stringify({
                    idPedido: idPedido
                }),
                contentType: 'application/json',
                success: function(response) {
                    // Parsear la respuesta si es necesario
                    const detalles = typeof response === 'string' ? JSON.parse(response) : response;

                    // Seleccionar el tbody correcto
                    const $listaDetalles = $('#listaDetallesPedido');
                    $listaDetalles.empty();

                    detalles.forEach(function(detalle) {
                        const fila = `
                    <tr data-id="${detalle.idDetallePedido}">
                        <td>${detalle.idDetallePedido}</td>
                        <td data-idproducto="${detalle.idProducto}">
                            ${detalle.nombreProducto}
                        </td>
                        <td>${detalle.cantidad}</td>
                        <td>${detalle.precio}</td>
                        <td>${detalle.descuento}</td>
                        <td>${detalle.subtotal}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btnEditarDetalle">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btnEliminarDetalle">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                        $listaDetalles.append(fila);
                    });

                    actualizarResumenPedido();
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar detalles:', error);
                    mostrarAlerta('error', 'Error', 'No se pudieron cargar los detalles del pedido');
                }
            });
        }

        // Abrir modal de detalles
        $('.btn-detalles').on('click', function() {
            const idPedido = $(this).data('idpedido');
            const idProveedor = $(this).data('idproveedor');

            // Setear valores iniciales
            $('#idPedidoDetalle').val(idPedido);
            $('#proveedorDetalle').val(idProveedor);

            // Cargar productos del proveedor
            const $selectProductos = $('#producto'); // Cambié aquí de '#productoDetalle' a '#producto'
            $selectProductos.empty();
            $selectProductos.append('<option value="" disabled selected>Seleccionar un producto</option>');

            $.ajax({
                url: '/tiendaonline/?route=obtenerProductosPorProveedor',
                method: 'POST',
                data: JSON.stringify({
                    idProveedor: idProveedor
                }),
                contentType: 'application/json',
                success: function(productos) {
                    productos.forEach(function(producto) {
                        $selectProductos.append(`
                    <option value="${producto.idProducto}">
                        ${producto.nombre} - $${producto.precio}
                    </option>
                `);
                    });
                },
                error: function(xhr, status, error) {
                    mostrarAlerta('error', 'Error', 'No se pudieron cargar los productos');
                }
            });

            // Cargar detalles existentes
            cargarDetallesPedido(idPedido);
        });

        // Agregar detalle
        $('#btnAgregarDetalle').on('click', function() {
            if (!$formDetalles[0].checkValidity()) {
                $formDetalles.addClass('was-validated');
                return;
            }

            const idPedido = $('#idPedidoDetalle').val();
            const producto = $('#productoDetalle option:selected');
            const cantidad = $('#cantidadDetalle').val();
            const precio = $('#precioDetalle').val();
            const descuento = $('#descuentoDetalle').val() || 0;
            const subtotal = calcularSubtotal();

            const nuevoDetalle = {
                idPedido: idPedido,
                idProducto: producto.val(),
                nombreProducto: producto.text().split(' - ')[0],
                cantidad: cantidad,
                precio: precio,
                descuento: descuento,
                subtotal: subtotal
            };

            // Agregar fila a la tabla
            const nuevaFila = `
            <tr>
                <td></td>
                <td>${nuevoDetalle.nombreProducto}</td>
                <td>${nuevoDetalle.cantidad}</td>
                <td>${nuevoDetalle.precio}</td>
                <td>${nuevoDetalle.descuento}</td>
                <td>${nuevoDetalle.subtotal}</td>
                <td>
                    <button class="btn btn-sm btn-warning btnEditarDetalle">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btnEliminarDetalle">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
            $listaDetalles.append(nuevaFila);

            // Guardar detalle en base de datos
            $.ajax({
                url: '/tiendaonline/?route=agregarDetallePedido',
                method: 'POST',
                data: JSON.stringify(nuevoDetalle),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', 'Éxito', 'Detalle agregado correctamente');
                        actualizarResumenPedido();
                        $formDetalles[0].reset();
                    } else {
                        mostrarAlerta('error', 'Error', response.error);
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudo agregar el detalle');
                }
            });
        });

        // Cargar detalles de pedido existentes
        function cargarDetallesPedido(idPedido) {
            $.ajax({
                url: '/tiendaonline/?route=obtenerDetallesPedido',
                method: 'POST',
                data: JSON.stringify({
                    idPedido: idPedido
                }),
                contentType: 'application/json',
                success: function(detalles) {
                    $listaDetalles.empty();
                    detalles.forEach(function(detalle) {
                        const fila = `
                        <tr data-id="${detalle.idDetallePedido}">
                            <td>${detalle.idDetallePedido}</td>
                            <td>${detalle.nombreProducto}</td>
                            <td>${detalle.cantidad}</td>
                            <td>${detalle.precio}</td>
                            <td>${detalle.descuento}</td>
                            <td>${detalle.subtotal}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEditarDetalle">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btnEliminarDetalle">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        $listaDetalles.append(fila);
                    });
                    actualizarResumenPedido();
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudieron cargar los detalles');
                }
            });
        }

        // Editar detalle
        $listaDetalles.on('click', '.btnEditarDetalle', function() {
            const $fila = $(this).closest('tr');
            const idDetalle = $fila.data('id');

            // Cargar datos del detalle para edición
            $.ajax({
                url: '/tiendaonline/?route=obtenerDetallePorId',
                method: 'POST',
                data: JSON.stringify({
                    idDetallePedido: idDetalle
                }),
                contentType: 'application/json',
                success: function(detalle) {
                    // Llenar formulario para edición
                    $('#idDetallePedido').val(detalle.idDetallePedido);
                    $('#productoDetalle').val(detalle.idProducto);
                    $('#cantidadDetalle').val(detalle.cantidad);
                    $('#precioDetalle').val(detalle.precio);
                    $('#descuentoDetalle').val(detalle.descuento);

                    // Cambiar botones
                    $('#btnAgregarDetalle').hide();
                    $('#btnActualizarDetalle').show();
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudo cargar el detalle');
                }
            });
        });

        // Actualizar detalle
        $('#btnActualizarDetalle').on('click', function() {
            if (!$formDetalles[0].checkValidity()) {
                $formDetalles.addClass('was-validated');
                return;
            }

            const detalle = {
                idDetallePedido: $('#idDetallePedido').val(),
                idPedido: $('#idPedidoDetalle').val(),
                idProducto: $('#productoDetalle').val(),
                nombreProducto: $('#productoDetalle option:selected').text().split(' - ')[0],
                cantidad: $('#cantidadDetalle').val(),
                precio: $('#precioDetalle').val(),
                descuento: $('#descuentoDetalle').val() || 0,
                subtotal: calcularSubtotal()
            };

            $.ajax({
                url: '/tiendaonline/?route=modificarDetallePedido',
                method: 'POST',
                data: JSON.stringify(detalle),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        // Actualizar fila en la tabla
                        const $fila = $(`#listaDetallesPedido tr[data-id="${detalle.idDetallePedido}"]`);
                        $fila.find('td:nth-child(2)').text(detalle.nombreProducto);
                        $fila.find('td:nth-child(3)').text(detalle.cantidad);
                        $fila.find('td:nth-child(4)').text(detalle.precio);
                        $fila.find('td:nth-child(5)').text(detalle.descuento);
                        $fila.find('td:nth-child(6)').text(detalle.subtotal);

                        mostrarAlerta('success', 'Éxito', 'Detalle actualizado correctamente');
                        actualizarResumenPedido();

                        // Resetear formulario
                        $formDetalles[0].reset();
                        $('#btnActualizarDetalle').hide();
                        $('#btnAgregarDetalle').show();
                    } else {
                        mostrarAlerta('error', 'Error', response.error);
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudo actualizar el detalle');
                }
            });
        });

        // Eliminar detalle
        $listaDetalles.on('click', '.btnEliminarDetalle', function() {
            const $fila = $(this).closest('tr');
            const idDetalle = $fila.data('id');

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
                    $.ajax({
                        url: '/tiendaonline/?route=eliminarDetallePedido',
                        method: 'POST',
                        data: JSON.stringify({
                            idDetallePedido: idDetalle,
                            idPedido: $('#idPedidoDetalle').val()
                        }),
                        contentType: 'application/json',
                        success: function(response) {
                            if (response.success) {
                                // Eliminar fila de la tabla
                                $fila.remove();

                                mostrarAlerta('success', 'Éxito', 'Detalle eliminado correctamente');
                                actualizarResumenPedido();
                            } else {
                                mostrarAlerta('error', 'Error', response.error);
                            }
                        },
                        error: function() {
                            mostrarAlerta('error', 'Error', 'No se pudo eliminar el detalle');
                        }
                    });
                }
            });
        });

        // Guardar todos los detalles del pedido
        $('#btnGuardarDetalles').on('click', function() {
            const idPedido = $('#idPedidoDetalle').val();
            const detalles = [];

            // Recopilar detalles de la tabla
            $('#listaDetallesPedido tr').each(function() {
                const detalle = {
                    idDetallePedido: $(this).data('id'),
                    idPedido: idPedido,
                    idProducto: $(this).find('td:nth-child(2)').data('idproducto'),
                    cantidad: parseFloat($(this).find('td:nth-child(3)').text()),
                    precio: parseFloat($(this).find('td:nth-child(4)').text()),
                    descuento: parseFloat($(this).find('td:nth-child(5)').text()),
                    subtotal: parseFloat($(this).find('td:nth-child(6)').text())
                };
                detalles.push(detalle);
            });

            $.ajax({
                url: '/tiendaonline/?route=guardarDetallesPedido',
                method: 'POST',
                data: JSON.stringify({
                    idPedido: idPedido,
                    detalles: detalles
                }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        mostrarAlerta('success', 'Éxito', 'Detalles del pedido guardados correctamente');
                        $('#modalDetalles').modal('hide');
                        // Opcional: Actualizar lista de pedidos
                        // cargarPedidos();
                    } else {
                        mostrarAlerta('error', 'Error', response.error);
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudieron guardar los detalles');
                }
            });
        });

        // Eventos adicionales
        $('#cantidadDetalle, #precioDetalle, #descuentoDetalle').on('input', function() {
            const subtotal = calcularSubtotal();
            $('#subtotalDetalle').text(`$${subtotal}`);
        });

        // Inicialización
        $modalDetalles.on('hidden.bs.modal', inicializarModalDetalles);
    });

    // Función global para mostrar alertas
    function mostrarAlerta(icon, title, text) {
        return Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'Aceptar',
            timer: 3000,
            showConfirmButton: true
        });
    }
    // Validaciones adicionales
    function validarDetalles() {
        const detalles = $('#listaDetallesPedido tr');

        if (detalles.length === 0) {
            mostrarAlerta('warning', 'Advertencia', 'Debe agregar al menos un detalle al pedido');
            return false;
        }

        let productosUnicos = new Set();
        let detallesValidos = true;

        detalles.each(function() {
            const idProducto = $(this).find('td:nth-child(2)').data('idproducto');
            const cantidad = parseFloat($(this).find('td:nth-child(3)').text());
            const precio = parseFloat($(this).find('td:nth-child(4)').text());

            // Validar productos únicos
            if (productosUnicos.has(idProducto)) {
                mostrarAlerta('warning', 'Advertencia', 'No se pueden agregar productos duplicados');
                detallesValidos = false;
                return false;
            }
            productosUnicos.add(idProducto);

            // Validar cantidades y precios
            if (cantidad <= 0 || precio <= 0) {
                mostrarAlerta('warning', 'Advertencia', 'Cantidad y precio deben ser mayores a cero');
                detallesValidos = false;
                return false;
            }
        });

        return detallesValidos;
    }

    // Gestión de stock y disponibilidad
    function verificarDisponibilidadStock(detalles) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/tiendaonline/?route=verificarStockProductos',
                method: 'POST',
                data: JSON.stringify({
                    detalles: detalles
                }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.disponible) {
                        resolve(true);
                    } else {
                        // Mostrar productos sin stock
                        let mensajeError = 'Los siguientes productos no tienen stock suficiente:\n';
                        response.productosAgotados.forEach(producto => {
                            mensajeError += `- ${producto.nombre} (Stock disponible: ${producto.stockDisponible})\n`;
                        });
                        mostrarAlerta('error', 'Stock Insuficiente', mensajeError);
                        reject(false);
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error', 'No se pudo verificar la disponibilidad de stock');
                    reject(false);
                }
            });
        });
    }

    // Cálculo de totales con mayor precisión
    function calcularTotalesPedido() {
        const detalles = $('#listaDetallesPedido tr');
        let totalItems = 0;
        let subtotalGeneral = 0;
        let descuentoTotal = 0;
        let impuestoTotal = 0;

        detalles.each(function() {
            const cantidad = parseFloat($(this).find('td:nth-child(3)').text()) || 0;
            const precio = parseFloat($(this).find('td:nth-child(4)').text()) || 0;
            const descuento = parseFloat($(this).find('td:nth-child(5)').text()) || 0;

            const subtotalLinea = cantidad * precio;

            totalItems += cantidad;
            subtotalGeneral += subtotalLinea;
            descuentoTotal += descuento;
        });

        // Calcular impuesto (ejemplo: IVA 19%)
        impuestoTotal = subtotalGeneral * 0.19;

        const totalGeneral = subtotalGeneral - descuentoTotal + impuestoTotal;

        return {
            totalItems,
            subtotalGeneral: Number(subtotalGeneral.toFixed(2)),
            descuentoTotal: Number(descuentoTotal.toFixed(2)),
            impuestoTotal: Number(impuestoTotal.toFixed(2)),
            totalGeneral: Number(totalGeneral.toFixed(2))
        };
    }

    // Exportación de detalles
    function exportarDetallesPedido() {
        const idPedido = $('#idPedidoDetalle').val();

        $.ajax({
            url: '/tiendaonline/?route=exportarDetallesPedido',
            method: 'POST',
            data: JSON.stringify({
                idPedido: idPedido
            }),
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data) {
                const a = document.createElement('a');
                const url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = `detalles_pedido_${idPedido}.xlsx`;
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'No se pudo exportar los detalles');
            }
        });
    }

    // Eventos adicionales
    $('#btnExportarDetalles').on('click', exportarDetallesPedido);

    // Extensión de funcionalidades
    $.fn.pedidosDetalles = function(opciones) {
        const configuracion = $.extend({
            modoEdicion: true,
            permiteEliminar: true,
            mostrarTotales: true
        }, opciones);

        return this.each(function() {
            const $tabla = $(this);

            // Aplicar configuraciones
            if (!configuracion.modoEdicion) {
                $tabla.find('.btnEditarDetalle, .btnEliminarDetalle').hide();
            }

            if (!configuracion.permiteEliminar) {
                $tabla.find('.btnEliminarDetalle').prop('disabled', true);
            }

            if (configuracion.mostrarTotales) {
                const totales = calcularTotalesPedido();
                $('#totalItemsResumen').text(totales.totalItems);
                $('#subtotalResumen').text(`$${totales.subtotalGeneral}`);
                $('#descuentosResumen').text(`$${totales.descuentoTotal}`);
                $('#impuestoResumen').text(`$${totales.impuestoTotal}`);
                $('#totalResumen').text(`$${totales.totalGeneral}`);
            }
        });
    };

    // Ejemplo de uso
    $('#tablaDetallesPedido').pedidosDetalles({
        modoEdicion: true,
        permiteEliminar: true,
        mostrarTotales: true
    });
</script>

<!-- Cierre de la vista -->
</body>