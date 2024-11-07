<form id="usuarioForm" class="needs-validation" novalidate>
    <div class="card-title mb-0">
        <h6 class="m-0 me-2">Detalles de productos</h6>
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
            <button type="button" class="btn btn-primary" id="agregarCaracteristica">Agregar Característica</button>
            <button type="button" class="btn btn-warning" id="cancelarSeleccion" style="display: none;">Cancelar Selección</button>
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
            <button type="button" class="btn btn-secondary" id="guardarCambios">Guardar Cambios y Enviar</button>
        </div>
    </div>
</form>

<script>
    let caracteristicas = []; // Almacena las características agregadas

    // Función para manejar la selección del producto
    document.getElementById('idProducto').addEventListener('change', function() {
        const selectedValue = this.value;

        // Bloquear el combo box
        this.disabled = true;

        // Mostrar el botón "Cancelar Selección"
        document.getElementById('cancelarSeleccion').style.display = 'inline-block';
    });

    // Función para agregar características
    document.getElementById('agregarCaracteristica').addEventListener('click', function() {
        const idProducto = document.getElementById('idProducto').value;
        const nombreCaracteristica = document.getElementById('nombreCaracteristica').value;
        const valor = document.getElementById('valor').value;
        const descripcion = document.getElementById('descripcion').value;

        // Verificar si todos los campos necesarios están completos
        if (idProducto && nombreCaracteristica && valor && descripcion) {
            if (caracteristicas.length < 15) {
                caracteristicas.push({
                    idProducto,
                    nombreCaracteristica,
                    valor,
                    descripcion
                });

                // Limpiar la tabla antes de agregar las filas
                document.querySelector("#tablaCaracteristicas tbody").innerHTML = '';

                // Actualizar la tabla con las características agregadas
                caracteristicas.forEach((c, index) => {
                    const fila = `<tr>
                        <td>${c.idProducto}</td>
                        <td>${c.nombreCaracteristica}</td>
                        <td>${c.valor}</td>
                        <td>${c.descripcion}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarCaracteristica(${index})">Eliminar</button></td>
                    </tr>`;
                    document.querySelector("#tablaCaracteristicas tbody").insertAdjacentHTML('beforeend', fila);
                });

                // Limpiar los campos del formulario excepto el producto
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
        // Habilitar nuevamente el combo box
        document.getElementById('idProducto').disabled = false;

        // Limpiar la selección del producto
        document.getElementById('idProducto').selectedIndex = 0;

        // Limpiar las características y la tabla
        caracteristicas = []; // Vaciar el array de características
        document.querySelector("#tablaCaracteristicas tbody").innerHTML = ''; // Limpiar la tabla

        // Ocultar el botón "Cancelar Selección"
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

    function eliminarCaracteristica(index) {
        caracteristicas.splice(index, 1); // Eliminar característica
        // Actualizar la tabla
        document.querySelector("#tablaCaracteristicas tbody").innerHTML = '';
        caracteristicas.forEach((c, index) => {
            const fila = `<tr>
                <td>${c.idProducto}</td>
                <td>${c.nombreCaracteristica}</td>
                <td>${c.valor}</td>
                <td>${c.descripcion}</td>
                <td><button type="button" class="btn btn-danger" onclick="eliminarCaracteristica(${index})">Eliminar</button></td>
            </tr>`;
            document.querySelector("#tablaCaracteristicas tbody").insertAdjacentHTML('beforeend', fila);
        });
    }
</script>
