<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siman.com - Tienda en línea</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Iconos modal -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Estilos para home.php -->
    <link rel="stylesheet" href="public/css/home.css">
    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (con dependencias de Popper.js) -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

</head>

<body>
<header>
    <div class="container">
        <div class="logo">tecnomario.com</div>
        <form class="search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <input type="search" name="q" placeholder="Buscar productos...">
            <button type="submit">Buscar</button>
        </form>
        <div class="user-actions">
            <a href="app\Views\Administrador\Home\cart.php" aria-label="Carrito de compras" id="cartLink">Carrito 🛒</a>
        </div>
    </div>
</header>


    <nav>
        <div class="container">
            <a href="#">Todas las áreas |</a>
            <a href="#">Exclusivo Online</a>
            <a href="#">Celulares</a>
            <a href="#">Computadoras</a>
            <a href="#">Consolas</a>
            <a href="#">Pantallas</a>
            <a href="#">Juegos</a>
        </div>
    </nav>

    <div class="free-shipping">
        <p>Envío gratis a zona metropolitana por compras mayores a $50 🚚</p>
    </div>

    <div class="carousel">
        <div class="carousel-inner">
            <div class="carousel-item">
                <img src="public/img/baner1.png" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="public/img/baner2.png" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="public/img/baner3.png" alt="Imagen 3">
            </div>
        </div>
        <a class="carousel-control carousel-control-prev" href="#" role="button" onclick="moveCarousel(-1)">&#10094;</a>
        <a class="carousel-control carousel-control-next" href="#" role="button" onclick="moveCarousel(1)">&#10095;</a>
        <div class="carousel-indicators">
            <span class="carousel-indicator active" onclick="setCarousel(0)"></span>
            <span class="carousel-indicator" onclick="setCarousel(1)"></span>
            <span class="carousel-indicator" onclick="setCarousel(2)"></span>
        </div>
    </div>

    <div class="container">
        <div class="product-grid">
            <?php
            // Simulación de productos
            $products = [
                ['title' => 'TELEVISOR OLED', 'description' => 'Televisor 64 pulgadas OLED', 'price' => 599.99, 'image' => 'public/img/homeadmin/PROD001_Televisor OLED.png'],
                ['title' => 'CAMARA', 'description' => 'Camara Sony', 'price' => 700.00, 'image' => 'public/img/homeadmin/PROD002_SONY-CAM-2024.png'],
                ['title' => 'REPRODUCTOR', 'description' => 'Reproductor Sony', 'price' => 150.00, 'image' => 'public/img/homeadmin/PROD003_SONY-BR-2024.png'],
                ['title' => 'RELOJ', 'description' => 'Reloj Smart band Galaxy fit 3', 'price' => 259.99, 'image' => 'public/img/homeadmin/PROD004_Smart band Galaxy Fit 3.png'],
                ['title' => 'COMPUTADORA', 'description' => 'Laptop Dell XPS', 'price' => 649.99, 'image' => 'public/img/homeadmin/PROD005_Laptop Dell XPS.png'],
                ['title' => 'COMPUTADORA', 'description' => 'ThinkPad X1', 'price' => 599.99, 'image' => 'public/img/homeadmin/PROD006_ThinkPad X1.png'],
                ['title' => 'PANTALLA', 'description' => 'Monitor 4K', 'price' => 399.99, 'image' => 'public/img/homeadmin/PROD007_Monitor 4K.png'],
                ['title' => 'TECLADO', 'description' => 'Teclado Mecánico con RGB', 'price' => 60.00, 'image' => 'public/img/homeadmin/PROD008_Teclado Mecánico.png'],
                ['title' => 'iPHONE', 'description' => 'iPhone 15 Pro Max', 'price' => 1499.00, 'image' => 'public/img/homeadmin/PROD009_iPhone 15 Pro.png'],
                ['title' => 'SAMSUMG', 'description' => 'Samsung Galaxy S24', 'price' => 1399.00, 'image' => 'public/img/homeadmin/PROD010_Samsung Galaxy S24.png']
            ];

            foreach ($products as $index => $product) {
                echo '<div class="product-card">
                        <img src="' . $product['image'] . '" class="product-image" alt="' . $product['title'] . '">
                        <div class="product-info">
                            <h3 class="product-title">' . $product['title'] . '</h3>
                            <p class="product-description">' . $product['description'] . '</p>
                            <p class="product-price">$' . $product['price'] . '</p>
                            <button class="buy-button" data-index="' . $index . '">Agregar al carrito</button>
                        </div>
                    </div>';
            }
            ?>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-modal-details">
                    <img id="modalProductImage" class="img-fluid" alt="Producto">
                    <div class="product-info">
                        <h3 id="modalProductTitle"></h3>
                        <p id="modalProductDescription"></p>
                        <p><strong>Precio: $<span id="modalProductPrice"></span></strong></p>

                        <!-- Selector de cantidad -->
                        <label for="productQuantity">Cantidad:</label>
                        <select id="productQuantity" class="form-select" style="width: 100px;">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="addToCartBtn" class="btn btn-cart">
                    <i class="fas fa-cart-plus"></i> Agregar al carrito
                </button>
                <button type="button" id="buyNowBtn" class="btn btn-buy">
                    <i class="fas fa-credit-card"></i> Comprar ahora
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Fin del Modal -->


    <footer>
        <p>&copy; 2024 tecnomario.com. Todos los derechos reservados.</p>
    </footer>

    <script>
        let cart = [];

        // Obtener boton Comprar
        const buyButtons = document.querySelectorAll('.buy-button');

        // Obtener los elementos del modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const modalTitle = document.getElementById('modalProductTitle');
        const modalDescription = document.getElementById('modalProductDescription');
        const modalPrice = document.getElementById('modalProductPrice');
        const modalImage = document.getElementById('modalProductImage');

        // Modal behavior
        buyButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const product = <?php echo json_encode($products); ?>[index];
                modalTitle.textContent = product.title;
                modalDescription.textContent = product.description;
                modalPrice.textContent = product.price.toFixed(2);
                modalImage.src = product.image;
                modal.show();
            });
        });

        // Función para agregar al carrito
document.getElementById('addToCartBtn').addEventListener('click', () => {
    const quantity = parseInt(document.getElementById('productQuantity').value);
    const product = {
        title: modalTitle.textContent,
        description: modalDescription.textContent,
        price: parseFloat(modalPrice.textContent),
        image: modalImage.src,
        quantity: quantity, // Guardar la cantidad
        totalPrice: parseFloat(modalPrice.textContent) * quantity // Calcular el precio total
    };

    // Si el producto ya está en el carrito, incrementa la cantidad
    const existingProduct = cart.find(item => item.title === product.title);
    if (existingProduct) {
        existingProduct.quantity += quantity;
        existingProduct.totalPrice += product.totalPrice;
    } else {
        cart.push(product);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`Producto agregado al carrito (${quantity} unidad(es))`);
    updateCartLink();
    modal.hide();
});

// Actualiza el contador del carrito
function updateCartLink() {
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cartLink').innerHTML = `Carrito 🛒 (${cartCount})`;
}

// Cargar carrito desde el localStorage al inicio
window.onload = function() {
    cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartLink();
};

    </script>
</body>

</html>
