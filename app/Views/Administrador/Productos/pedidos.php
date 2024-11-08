<?php
// Asegúrate de que $data esté disponible desde el controlador
$pedidos = $data['pedidos'] ?? [];
$proveedores = $data['proveedores'] ?? [];
$estados = $data['estados'] ?? [];
$prioridades = $data['prioridades'] ?? [];
?>

<div class="container mt-4">
    <h2>Gestión de Pedidos a Proveedores</h2>

    <fieldset>
        <button type="button" name="btnNuevo" id="btnNuevo" class="btn btn-primary" data-toggle="modal" data-target="#opciones">
            <i class="bi bi-check-circle" style="margin-right: 5px;"></i> Agregar Pedido
        </button>
    </fieldset>
    <br>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>Gestión de pedidos</h6>
    </div>

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-bordered" id="tablaPedidos">
            <thead class="alert-info" style="position: sticky; top: 0; background-color: #f8f9fa;">
                <tr style='background-color: blue; color: white;'>
                    <th style="text-align: center;">ID</th>
                    <th style="text-align: center;">Proveedor</th>
                    <th style="text-align: center;">Fecha Pedido</th>
                    <th style="text-align: center;">Estado</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;">Prioridad</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody id="listaPedidos">
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
                            <td style="text-align: center;">
                                <?php
                                // Buscar el nombre del proveedor
                                $nombreProveedor = '';
                                foreach ($proveedores as $proveedor) {
                                    if ($proveedor['idProveedor'] == $pedido['idProveedor']) {
                                        $nombreProveedor = htmlspecialchars($proveedor['nombre']);
                                        break;
                                    }
                                }
                                echo $nombreProveedor;
                                ?>
                            </td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($pedido['fechaPedido']); ?></td>
                            <td style="text-align: center;">
                                <?php
                                // Mostrar estado con color según su valor
                                $estadoClase = '';
                                switch ($pedido['estado']) {
                                    case 'pendiente':
                                        $estadoClase = 'badge bg-warning text-dark';
                                        break;
                                    case 'enviado':
                                        $estadoClase = 'badge bg-info';
                                        break;
                                    case 'recibido':
                                        $estadoClase = 'badge bg-success';
                                        break;
                                    case 'cancelado':
                                        $estadoClase = 'badge bg-danger';
                                        break;
                                    default:
                                        $estadoClase = 'badge bg-secondary';
                                }
                                ?>
                                <span class="<?php echo $estadoClase; ?>">
                                    <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?>
                                </span>
                            </td>
                            <td style="text-align: center;"><?php echo number_format($pedido['total'], 2); ?></td>
                            <td style="text-align: center;">
                                <?php
                                // Mostrar prioridad con color
                                $prioridadClase = '';
                                switch ($pedido['prioridad']) {
                                    case 'baja':
                                        $prioridadClase = 'badge bg-success';
                                        break;
                                    case 'media':
                                        $prioridadClase = 'badge bg-warning text-dark';
                                        break;
                                    case 'alta':
                                        $prioridadClase = 'badge bg-danger';
                                        break;
                                    default:
                                        $prioridadClase = 'badge bg-secondary';
                                }
                                ?>
                                <span class="<?php echo $prioridadClase; ?>">
                                    <?php echo ucfirst(htmlspecialchars($pedido['prioridad'])); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group" role="group">
                                    <a href="#" onclick="cargarMod('<?php echo $pedido['idPedido']; ?>')"
                                        class="btn btn-sm btn-primary"
                                        data-toggle="modal"
                                        data-target="#opciones"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete"
                                        data-idd="<?php echo $pedido['idPedido']; ?>"
                                        title="Eliminar">
                                        <i class='fas fa-trash-alt'></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-info btn-details"
                                        data-id="<?php echo $pedido['idPedido']; ?>"
                                        title="Detalles">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
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

<!-- Modal para el formulario de pedidos -->
<div class="modal fade" id="opciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-shadow" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-box-open me-2"></i> Registro de Pedidos
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="pedidoForm" class="needs-validation" novalidate>
                    <input type="hidden" id="idPedido" name="idPedido">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="idProveedor" class="form-label">Proveedor *</label>
                            <select class="form-control" id="idProveedor" name="idProveedor" required>
                                <option value="">Seleccione un proveedor</option>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo htmlspecialchars($proveedor['idProveedor']); ?>">
                                        <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un proveedor</div>
                        </div>

                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="">Seleccione un estado</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?php echo htmlspecialchars($estado); ?>">
                                        <?php echo ucfirst(htmlspecialchars($estado)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un estado</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prioridad" class="form-label">Prioridad *</label>
                            <select class="form-control" id="prioridad" name="prioridad" required>
                                <option value="">Seleccione una prioridad</option>
                                <?php foreach ($prioridades as $prioridad): ?>
                                    <option value="<?php echo htmlspecialchars($prioridad); ?>">
                                        <?php echo ucfirst(htmlspecialchars($prioridad)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione una prioridad</div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaPedido" class="form-label">Fecha de Pedido *</label>
                            <input type="date" class="form-control" id="fechaPedido" name="fechaPedido" required>
                            <div class="invalid-feedback">Ingrese la fecha del pedido</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total" class="form-label">Total *</label>
                            <input type="number" step="0.01" class="form-control" id="total" name="total" required>
                            <div class="invalid-feedback">Ingrese el total del pedido</div>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaEntrega" class="form-label">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="fechaEntrega" name="fechaEntrega">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="metodoPago" class="form-label">Método de Pago</label>
                            <input type="text" class="form-control" id="metodoPago" name="metodoPago">
                        </div>

                        <div class="col-md-6">
                            <label for="totalImpuesto" class="form-label">Total Impuesto</label>
                            <input type="number" step="0.01" class="form-control" id="totalImpuesto" name="totalImpuesto" value="0.00">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="descuentos" class="form-label">Descuentos</label>
                            <input type="number" step="0.01" class="form-control" id="descuentos" name="descuentos" value="0.00">
                        </div>

                        <div class="col-md-6">
                            <label for="direccionEntrega" class="form-label">Dirección de Entrega</label>
                            <textarea class="form-control" id="direccionEntrega" name="direccionEntrega" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Sección de Detalles del Pedido -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Detalles del Pedido</h5>
                            <button type="button" class="btn btn-success mb-2" id="agregarDetalle">
                                <i class="fas fa-plus"></i> Agregar Producto
                            </button>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaDetalles">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los detalles se agregarán dinámicamente con JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Pedido -->
<div class="modal fade" id="detallesPedidoModal" tabindex="-1" role="dialog" aria-labelledby="detallesPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesPedidoModalLabel">Detalles del Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoDetallesPedido">
                <!-- Aquí se cargarán los detalles del pedido -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para cargar productos en el select de detalles
    // Variable global para almacenar productos
    let productosOptions = '';

    // Función para cargar productos al iniciar
    function cargarProductos() {
        $.ajax({
            url: 'index.php?route=obtenerProductos', // Asegúrate de tener esta ruta
            method: 'GET',
            success: function(response) {
                const productos = JSON.parse(response);
                productosOptions = productos.map(producto =>
                    `<option value="${producto.idProducto}">${producto.nombre} (${producto.modeloSerial})</option>`
                ).join('');
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'No se pudieron cargar los productos');
            }
        });
    }

    // Llamar a cargar productos al iniciar
    $(document).ready(function() {
        cargarProductos();
    });

    // Función para agregar fila de detalle de pedido
    function agregarFilaDetalle(data = {}) {
        if (!productosOptions) {
            mostrarAlerta('warning', 'Advertencia', 'Aún no se han cargado los productos');
            return;
        }

        const index = $('#tablaDetalles tbody tr').length;
        const html = `
        <tr>
            <td>
                <select name="detalles[${index}][idProducto]" class="form-control producto-select" required>
                    <option value="">Seleccione un producto</option>
                    ${productosOptions}
                </select>
            </td>
            <td>
                <input type="number" name="detalles[${index}][cantidad]" 
                       class="form-control cantidad" 
                       value="${data.cantidad || 1}" 
                       min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" 
                       name="detalles[${index}][precio]" 
                       class="form-control precio" 
                       value="${data.precio || 0}" 
                       min="0" required>
            </td>
            <td>
                <input type="number" step="0.01" 
                       name="detalles[${index}][descuento]" 
                       class="form-control descuento" 
                       value="${data.descuento || 0}" 
                       min="0">
            </td>
            <td>
                <span class="subtotal">0.00</span>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-detalle">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
        $('#tablaDetalles tbody').append(html);
        calcularTotales();
    }

    // Calcular totales del pedido
    function calcularTotales() {
        let totalPedido = 0;
        let totalImpuesto = parseFloat($('#totalImpuesto').val()) || 0;
        let descuentos = parseFloat($('#descuentos').val()) || 0;

        $('#tablaDetalles tbody tr').each(function() {
            const cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
            const precio = parseFloat($(this).find('.precio').val()) || 0;
            const descuento = parseFloat($(this).find('.descuento').val()) || 0;

            const subtotal = (cantidad * precio) - descuento;
            $(this).find('.subtotal').text(subtotal.toFixed(2));

            totalPedido += subtotal;
        });

        // Calcular total final considerando impuestos y descuentos
        const totalFinal = totalPedido + totalImpuesto - descuentos;
        $('#total').val(totalFinal.toFixed(2));
    }

    // Eventos para recalcular totales
    $(document).on('change', '.cantidad, .precio, .descuento, #totalImpuesto, #descuentos', calcularTotales);

    // Evento para agregar detalle
    $('#agregarDetalle').on('click', function() {
        agregarFilaDetalle();
    });

    // Evento para eliminar fila de detalle
    $(document).on('click', '.eliminar-detalle', function() {
        $(this).closest('tr').remove();
        calcularTotales();
    });

    // Validación de formulario
    function validarFormulario() {
        let valido = true;

        // Validar campos obligatorios
        const camposObligatorios = [
            '#idProveedor', '#estado', '#prioridad',
            '#fechaPedido', '#total'
        ];

        camposObligatorios.forEach(function(campo) {
            const elemento = $(campo);
            if (!elemento.val()) {
                elemento.addClass('is-invalid');
                valido = false;
            } else {
                elemento.removeClass('is-invalid');
            }
        });

        // Validar detalles del pedido
        const detalles = $('#tablaDetalles tbody tr');
        if (detalles.length === 0) {
            mostrarAlerta('warning', 'Detalles Incompletos', 'Debe agregar al menos un producto al pedido');
            valido = false;
        }

        return valido;
    }

    // Guardar pedido
    $('#pedidoForm').on('submit', function(e) {
        e.preventDefault();

        if (!validarFormulario()) return;

        const formData = $(this).serialize();

        $.ajax({
            url: $('#idPedido').val() ? 'index.php?route=modificarPedido' : 'index.php?route=agregarPedido',
            method: 'POST',
            data: formData,
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    mostrarAlerta('success', 'Éxito', result.message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    mostrarAlerta('error', 'Error', result.error);
                }
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'Hubo un problema al procesar la solicitud');
            }
        });
    });

    // Cargar pedido para modificación
    function cargarPedidoParaModificar(idPedido) {
        $.ajax({
            url: 'index.php?route=obtenerPedido',
            method: 'GET',
            data: {
                idPedido: idPedido
            },
            success: function(response) {
                const pedido = JSON.parse(response);

                // Llenar campos del formulario
                $('#idPedido').val(pedido.idPedido);
                $('#idProveedor').val(pedido.idProveedor);
                $('#estado').val(pedido.estado);
                $('#prioridad').val(pedido.prioridad);
                $('#fechaPedido').val(pedido.fechaPedido);
                $('#total').val(pedido.total);
                $('#fechaEntrega').val(pedido.fechaEntrega);
                $('#metodoPago').val(pedido.metodoPago);
                $('#totalImpuesto').val(pedido.totalImpuesto);
                $('#descuentos').val(pedido.descuentos);
                $('#direccionEntrega').val(pedido.direccionEntrega);
                $('#observaciones').val(pedido.observaciones);

                // Cargar detalles del pedido
                cargarDetallesPedido(idPedido);
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'No se pudo cargar el pedido');
            }
        });
    }

    // Cargar detalles del pedido
    function cargarDetallesPedido(idPedido) {
        $.ajax({
            url: 'index.php?route=obtenerDetallesPedido',
            method: 'GET',
            data: {
                idPedido: idPedido
            },
            success: function(response) {
                const detalles = JSON.parse(response);

                // Limpiar tabla de detalles
                $('#tablaDetalles tbody').empty();

                // Agregar cada detalle
                detalles.forEach(detalle => {
                    agregarFilaDetalle({
                        idProducto: detalle.idProducto,
                        cantidad: detalle.cantidad,
                        precio: detalle.precio,
                        descuento: detalle.descuento
                    });

                    // Seleccionar el producto correspondiente
                    const ultimaFila = $('#tablaDetalles tbody tr:last');
                    ultimaFila.find('.producto-select').val(detalle.idProducto);
                });

                calcularTotales();
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'No se pudieron cargar los detalles del pedido');
            }
        });
    }

    // Función para mostrar detalles del pedido en modal
    function mostrarDetallesPedido(idPedido) {
        $.ajax({
            url: 'index.php?route=obtenerDetallesPedido',
            method: 'GET',
            data: {
                idPedido: idPedido
            },
            success: function(response) {
                const detalles = JSON.parse(response);

                let html = `
                <div class="row">
                    <div class="col-md-12">
                        <h5>Detalles del Pedido #${idPedido}</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

                let totalPedido = 0;
                detalles.forEach(detalle => {
                    const subtotal = (detalle.cantidad * detalle.precio) - detalle.descuento;
                    totalPedido += subtotal;

                    html += `
                    <tr>
                        <td>${detalle.nombreProducto}</td>
                        <td>${detalle.cantidad}</td>
                        <td>${formatoMoneda(detalle.precio)}</td>
                        <td>${formatoMoneda(detalle.descuento)}</td>
                        <td>${formatoMoneda(subtotal)}</td>
                    </tr>
                `;
                });

                html += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th>${formatoMoneda(totalPedido)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            `;

                $('#contenidoDetallesPedido').html(html);
                $('#detallesPedidoModal').modal('show');
            },
            error: function() {
                mostrarAlerta('error', 'Error', 'No se pudieron cargar los detalles del pedido');
            }
        });
    }

    // Función para formatear moneda
    function formatoMoneda(valor) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 2
        }).format(valor);
    }

    // Evento para abrir modal de detalles
    $(document).on('click', '.btn-details', function() {
        const idPedido = $(this).data('id');
        mostrarDetallesPedido(idPedido);
    });

    // Función para eliminar pedido
    function eliminarPedido(idPedido) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "No podrá revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php?route=eliminarPedido',
                    method: 'POST',
                    data: {
                        idPedido: idPedido
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            mostrarAlerta('success', 'Éxito', result.message);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            mostrarAlerta('error', 'Error', result.error);
                        }
                    },
                    error: function() {
                        mostrarAlerta('error', 'Error', 'No se pudo eliminar el pedido');
                    }
                });
            }
        });
    }

    // Evento para eliminar pedido
    $(document).on('click', '.btn-delete', function() {
        const idPedido = $(this).data('idd');
        eliminarPedido(idPedido);
    });

    // Función de alerta personalizada
    function mostrarAlerta(tipo, titulo, mensaje) {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }
</script>