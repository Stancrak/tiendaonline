<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <link rel="stylesheet" href="public/css/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">tecnomario.com</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Volver a la tienda</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Carrito de compras -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Carrito de compras</h2>
        <div id="cartContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
        
        <!-- Detalles de envío y descuento -->
        <div class="mt-4">
            <label for="couponCode">Código de descuento:</label>
            <input type="text" id="couponCode" class="form-control mb-3" placeholder="Introduce tu código">
            <button class="btn btn-primary" onclick="applyDiscount()">Aplicar descuento</button>
            <!-- Mensaje de cupon -->
            <div id="couponMessage" class="mt-2"></div>
        </div>
        
        <div id="cartTotal" class="mt-4 text-end">
            <h4>Total: $<span id="totalPrice">0.00</span></h4>
            <h5>Envío: $<span id="shippingCost">0.00</span></h5>
            <h5>Descuento: -$<span id="discount">0.00</span></h5>
            <h4>Total final: $<span id="finalPrice">0.00</span></h4>
            <button id="checkoutBtn" class="btn btn-success mt-3" onclick="goToPaymentPage()" disabled>Finalizar compra</button>
            <button class="btn btn-danger mt-3" onclick="clearCart()">Vaciar carrito</button>
        </div>
    </div>

    <!-- Página de pagos -->
    <div id="paymentPage" class="container mt-5" style="display:none;">
        <h3 class="text-center mb-4">Formulario de Pago</h3>
        <div class="card shadow-sm p-4">
            <form id="paymentForm">
                <!-- Número de tarjeta -->
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">Número de tarjeta</label>
                    <input type="text" id="cardNumber" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX" required>
                </div>
                
                <!-- Fecha de expiración -->
                <div class="mb-3">
                    <label for="expiryDate" class="form-label">Fecha de expiración</label>
                    <input type="month" id="expiryDate" class="form-control" required>
                </div>

                <!-- CVV -->
                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" id="cvv" class="form-control" placeholder="XXX" required>
                </div>

                <!-- Nombre en la tarjeta -->
                <div class="mb-3">
                    <label for="cardHolder" class="form-label">Nombre del titular</label>
                    <input type="text" id="cardHolder" class="form-control" placeholder="Nombre completo" required>
                </div>

                <!-- Botón de pago -->
                <button type="submit" class="btn btn-success btn-lg w-100">Pagar $<span id="paymentAmount"></span></button>
            </form>
        </div>

        <!-- Mensaje de pago exitoso -->
        <div id="paymentMessage" class="alert alert-success mt-4" style="display: none;">
            <h5 class="text-center">¡Pago realizado con éxito!</h5>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 tecnomario.com. Todos los derechos reservados.</p>
    </footer>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let discount = 0;
        const cartContainer = document.getElementById('cartContainer');
        const totalPriceElement = document.getElementById('totalPrice');
        const finalPriceElement = document.getElementById('finalPrice');
        const shippingCostElement = document.getElementById('shippingCost');
        const discountElement = document.getElementById('discount');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const paymentPage = document.getElementById('paymentPage');
        const paymentForm = document.getElementById('paymentForm');
        const couponMessage = document.getElementById('couponMessage');

        function saveCart() {
            const cartData = cart.map(product => ({
                id: product.id,
                title: product.title,
                description: product.description,
                image: product.image,
                price: product.price,
                quantity: product.quantity
            }));
            localStorage.setItem('cart', JSON.stringify(cartData));
        }

        function updateQuantity(productId, newQuantity) {
            const product = cart.find(item => item.id === productId);
            if (product) {
                product.quantity = newQuantity;
                saveCart();
                displayCart();
            }
        }

        function removeFromCart(productId) {
            const productIndex = cart.findIndex(product => product.id === productId);
            if (productIndex !== -1) {
                cart.splice(productIndex, 1);
                saveCart();
                displayCart();
            }
        }

        function applyDiscount() {
            const code = document.getElementById('couponCode').value.trim();
            if (code === 'DESCUENTO10') {
                discount = 10;  // 10% de descuento
                couponMessage.textContent = "¡Código aplicado! 10% de descuento.";
                couponMessage.classList.remove('text-danger');
                couponMessage.classList.add('text-success');
            } else {
                discount = 0;
                couponMessage.textContent = "Código invalido.";
                couponMessage.classList.remove('text-success');
                couponMessage.classList.add('text-danger');
            }
            saveCart();
            displayCart();
        }

        function clearCart() {
            cart = [];
            saveCart();
            displayCart();
        }

        function displayCart() {
            cartContainer.innerHTML = '';
            let total = 0;

            cart.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.classList.add('col', 'mb-4');
                const productHtml = `
                    <div class="card cart-item shadow-sm border-light rounded">
                        <img src="${product.image}" alt="${product.title}" class="card-img-top cart-item-img">
                        <div class="card-body">
                            <h5 class="card-title">${product.title}</h5>
                            <p class="card-text">${product.description}</p>
                            <p class="text-end"><strong>Precio: $${(product.price * product.quantity).toFixed(2)}</strong></p>
                            <label for="quantity${product.id}">Cantidad:</label>
                            <select id="quantity${product.id}" class="form-select" onchange="updateQuantity(${product.id}, this.value)" style="width: 100px;">
                                <option value="1" ${product.quantity == 1 ? 'selected' : ''}>1</option>
                                <option value="2" ${product.quantity == 2 ? 'selected' : ''}>2</option>
                                <option value="3" ${product.quantity == 3 ? 'selected' : ''}>3</option>
                                <option value="4" ${product.quantity == 4 ? 'selected' : ''}>4</option>
                                <option value="5" ${product.quantity == 5 ? 'selected' : ''}>5</option>
                            </select>
                            <button class="btn btn-danger btn-sm mt-2" onclick="removeFromCart(${product.id})">Eliminar</button>
                        </div>
                    </div>
                `;
                productDiv.innerHTML = productHtml;
                cartContainer.appendChild(productDiv);
                total += product.price * product.quantity;
            });

            const shippingCost = 5.00; // Envío fijo
            const finalPrice = total + shippingCost - discount;
            totalPriceElement.textContent = total.toFixed(2);
            shippingCostElement.textContent = shippingCost.toFixed(2);
            discountElement.textContent = discount.toFixed(2);
            finalPriceElement.textContent = finalPrice.toFixed(2);

            if (cart.length > 0) {
                checkoutBtn.disabled = false;
            } else {
                checkoutBtn.disabled = true;
            }
        }

        function goToPaymentPage() {
            paymentPage.style.display = 'block';
            paymentAmount.textContent = finalPriceElement.textContent;
        }

        paymentForm.addEventListener('submit', (event) => {
            event.preventDefault();
            document.getElementById('paymentMessage').style.display = 'block';
            setTimeout(() => {
                document.getElementById('paymentMessage').style.display = 'none';
                clearCart();
            }, 3000);
        });

        // Inicializa el carrito
        displayCart();
    </script>
</body>
</html>







