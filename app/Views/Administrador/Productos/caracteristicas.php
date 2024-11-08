<form id="usuarioForm" class="needs-validation" novalidate>

    <div class="card-title mb-0">
        <h5 class="m-0 me-2" style="font-weight: bold;">
            <i class="bi bi-journals" style="font-size: 1.7rem; color: green; margin-right: 5px;"></i>
            Detalles de productos
        </h5>
    </div>
    <br>
    <div class="row mb-4">
        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="idProducto">Producto</label>
                <select id="idProducto" name="idProducto" class="form-control" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach ($data['caracteristicas'] as $prod): ?>
                        <option value="<?php echo htmlspecialchars($prod['idProducto']); ?>">
                            <?php echo htmlspecialchars($prod['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Por favor seleccione un producto.</div>
            </div>
        </div>

        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="nombreCaracteristica">Característica</label>
                <input type="text" id="nombreCaracteristica" name="nombreCaracteristica" class="form-control" required>
                <div class="invalid-feedback">Ingrese una característica válida.</div>
            </div>
        </div>

        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="valor">Valor</label>
                <input type="text" id="valor" name="valor" class="form-control" required>
                <div class="invalid-feedback">Ingrese un valor válido.</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="form-outline">
                <label class="form-label" style="font-size: 12px;" for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                <div class="invalid-feedback">Ingrese una descripción válida.</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <button type="button" class="btn btn-primary" id="agregarCaracteristica"><i class="bi bi-plus-circle" style="margin-right: 5px;"></i>Agregar Característica</button>
            <button type="button" class="btn btn-warning" id="cancelarSeleccion" style="display: none;"><i class="bi bi-x-circle" style="margin-right: 5px;"></i>Cancelar Selección</button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablaCaracteristicas">
                    <thead class="alert-info">
                        <tr style="background-color: blue; color: white;">
                            <th>Producto</th>
                            <th>Característica</th>
                            <th>Valor</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Procesar Usuario -->
    <div class="row mb-4">
        <div class="col-12">
            <button type="button" class="btn btn-secondary" id="guardarCambios"><i class="bi bi-check-circle" style="margin-right: 5px;"></i>Guardar Cambios y Enviar</button>
        </div>
    </div>

    <!-- Nuevo código -->
    <div class="col">
        <div class="card-title mb-2 mt-5">
            <h5 class="m-0 me-2" style="font-weight: bold;">
                <i class="bi bi-card-list" style="font-size: 1.7rem; color: green; margin-right: 5px;"></i>
                Listar características de productos
            </h5>
        </div>

        <div class="form-outline">
            <label class="form-label" style="font-size: 14px;" for="idProductoSecundario">Producto Seleccionado</label>
            <select id="idProductoSecundario" name="idProductoSecundario" class="form-control mb-3" required>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($data['caracteristicas'] as $prod): ?>
                    <option value="<?php echo htmlspecialchars($prod['idProducto']); ?>">
                        <?php echo htmlspecialchars($prod['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Por favor seleccione un producto secundario.</div>
        </div>
    </div>
    <!-- Fin del nuevo código -->
    <!-- Nuevo código -->
    <div class="row mb-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablaCaracteristicasSecundarias">
                    <thead class="alert-info">
                        <tr style="background-color: green; color: white;">
                            <th>Producto Secundario</th>
                            <th>Característica</th>
                            <th>Valor</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Fin del nuevo código -->

</form>

<script>
    let caracteristicas = []; // Almacena las características agregadas

    // Función para manejar la selección del producto
    document.getElementById('idProducto').addEventListener('change', function() {
        const selectedValue = this.value;
        this.disabled = true;
        document.getElementById('cancelarSeleccion').style.display = 'inline-block';
    });

    // Función para agregar características
    document.getElementById('agregarCaracteristica').addEventListener('click', function() {
        const idProducto = document.getElementById('idProducto').value;
        const nombreCaracteristica = document.getElementById('nombreCaracteristica').value;
        const valor = document.getElementById('valor').value;
        const descripcion = document.getElementById('descripcion').value;

        if (idProducto && nombreCaracteristica && valor && descripcion) {
            if (caracteristicas.length < 15) {
                const idCaracteristica = Date.now(); // Generar un ID único
                caracteristicas.push({
                    idProducto,
                    idCaracteristica,
                    nombreCaracteristica,
                    valor,
                    descripcion
                });

                actualizarTablaCaracteristicas(); // Actualiza la tabla después de agregar

                // Limpiar los campos del formulario
                document.getElementById('nombreCaracteristica').value = '';
                document.getElementById('valor').value = '';
                document.getElementById('descripcion').value = '';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Solo puedes agregar un máximo de 15 características.',
                    confirmButtonText: 'Aceptar'
                });
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, completa todos los campos.',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    // Función para cancelar la selección de producto
    document.getElementById('cancelarSeleccion').addEventListener('click', function() {
        document.getElementById('idProducto').disabled = false;
        document.getElementById('idProducto').selectedIndex = 0;
        caracteristicas = [];
        document.querySelector("#tablaCaracteristicas tbody").innerHTML = '';
        this.style.display = 'none';
    });

    // Cuando se guardan los cambios, prepara los datos
    document.getElementById('guardarCambios').addEventListener('click', function() {
        if (caracteristicas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No se puede guardar',
                text: 'Debes agregar al menos una característica antes de guardar.',
                confirmButtonText: 'Aceptar'
            });
        } else {
            const formData = new FormData();

            // Agregar los datos de las características al FormData
            caracteristicas.forEach((c, index) => {
                formData.append(`idProducto[${index}]`, c.idProducto);
                formData.append(`nombreCaracteristica[${index}]`, c.nombreCaracteristica);
                formData.append(`valor[${index}]`, c.valor);
                formData.append(`descripcion[${index}]`, c.descripcion);
            });

            // Enviar los datos al servidor
            fetch('/tiendaonline/?route=agregarCaracteristca', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado',
                            text: data.message,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = '/tiendaonline/?route=caracteristcaHome';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar los datos.',
                        confirmButtonText: 'Aceptar'
                    });
                });
        }
    });


    // Función para eliminar característica
    function eliminarCaracteristica(idCaracteristica) {
        if (!idCaracteristica) {
            console.error('ID de característica no válido');
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let index = caracteristicas.findIndex(c => c.idCaracteristica === idCaracteristica);
                if (index !== -1) {
                    caracteristicas.splice(index, 1);
                    actualizarTablaCaracteristicas(); // Actualiza la tabla de características
                } else {
                    console.error('Característica no encontrada en el array');
                }

                // Enviar la solicitud de eliminación al servidor
                const formData = new FormData();
                formData.append('idCaracteristica', idCaracteristica);

                fetch('/tiendaonline/?route=eliminarCaracteristica', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Eliminado',
                                'La característica ha sido eliminada',
                                'success'
                            );
                        } else {
                            throw new Error(data.message || 'Error al eliminar la característica');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar la característica',
                            confirmButtonText: 'Aceptar'
                        });
                    });
            }
        });
    }

    // Función para actualizar la tabla de características
    function actualizarTablaCaracteristicas() {
        const tbody = document.querySelector("#tablaCaracteristicas tbody");
        tbody.innerHTML = '';

        caracteristicas.forEach((c) => {
            const fila = `<tr>
            <td>${c.idProducto}</td>
            <td>${c.nombreCaracteristica}</td>
            <td>${c.valor}</td>
            <td>${c.descripcion}</td>
            <td>
                <button type="button" class="btn btn-danger" onclick="eliminarCaracteristica(${c.idCaracteristica})"><i class="bi bi-trash" style="margin-right: 5px;"></i>Eliminar</button>
            </td>
        </tr>`;
            tbody.insertAdjacentHTML('beforeend', fila);
        });
    }

    // Función para manejar la selección de productos secundarios
    let caracteristicasSecundarias = []; // Almacena las características secundarias agregadas
    document.getElementById('idProductoSecundario').addEventListener('change', function() {
        const selectedValue = this.value;
        if (selectedValue) {
            cargarCaracteristicasSecundarias(selectedValue);
        }
    });

    // Función para cargar características secundarias desde el servidor
    function cargarCaracteristicasSecundarias(idProducto) {
        const formData = new FormData();
        formData.append('idProducto', idProducto);

        return fetch('/tiendaonline/?route=mostrarCaracteristicas', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    caracteristicasSecundarias = data.data;
                    actualizarTablaCaracteristicasSecundarias();
                } else {
                    throw new Error(data.message || 'Error al cargar las características secundarias');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar las características del producto secundario',
                    confirmButtonText: 'Aceptar'
                });
            });
    }

    // Función para actualizar la tabla de características secundarias
    function actualizarTablaCaracteristicasSecundarias() {
        const tbody = document.querySelector("#tablaCaracteristicasSecundarias tbody");
        tbody.innerHTML = '';

        if (!caracteristicasSecundarias || caracteristicasSecundarias.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay características para este producto seleccionado</td></tr>';
            return;
        }

        caracteristicasSecundarias.forEach(c => {
            const fila = `<tr>
            <td>${c.idProducto}</td>
            <td>${c.nombreCaracteristica}</td>
            <td>${c.valor}</td>
            <td>${c.descripcion || ''}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCaracteristicaSecundaria(${c.idCaracteristica})">
                    <i class="bi bi-trash" style="margin-right: 5px;"></i>Eliminar
                </button>
            </td>
        </tr>`;
            tbody.insertAdjacentHTML('beforeend', fila);
        });
    }

    function eliminarCaracteristicaSecundaria(idCaracteristica) {
        if (!idCaracteristica) {
            console.error('ID de característica no válido');
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar la solicitud de eliminación al servidor primero
                const formData = new FormData();
                formData.append('idCaracteristica', idCaracteristica);

                fetch('/tiendaonline/?route=eliminarCaracteristica', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Confirmación de eliminación exitosa en el servidor
                            let index = caracteristicasSecundarias.findIndex(c => c.idCaracteristica === idCaracteristica);
                            if (index !== -1) {
                                caracteristicasSecundarias.splice(index, 1);
                                actualizarTablaCaracteristicasSecundarias(); // Ahora actualizamos la tabla
                            } else {
                                console.error('Característica no encontrada en el array');
                            }

                            Swal.fire(
                                'Eliminado',
                                'La característica ha sido eliminada',
                                'success'
                            );
                        } else {
                            throw new Error(data.message || 'Error al eliminar la característica');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar la característica',
                            confirmButtonText: 'Aceptar'
                        });
                    });
            }
        });
    }


    // Inicialización de eventos y carga inicial
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar cualquier inicialización que necesites
        // Por ejemplo, cargar productos o características iniciales si es necesario
    });
</script>