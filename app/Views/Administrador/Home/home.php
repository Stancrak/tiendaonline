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
    <link rel="stylesheet" href="public\css\home.css">
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
                <a href="#" aria-label="Carrito de compras">Carrito 🛒</a>
            </div>
        </div>
    </header>

    <nav>
        <div class="container">
            <a href="#">Todas las areas |</a>
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
                <img src="public\img\baner1.png" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="public\img\baner2.png" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="public\img\baner3.png" alt="Imagen 3">
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
                ['title' => 'TELEVISOR OLED', 'description' => 'Televisor 64 pulgadas OLED', 'price' => 599.99, 'image' => 'public\img\homeadmin\PROD001_Televisor OLED.png'],
                ['title' => 'CAMARA', 'description' => 'Camara Sony', 'price' => 700.00, 'image' => 'public\img\homeadmin\PROD002_SONY-CAM-2024.png'],
                ['title' => 'REPRODUCTOR', 'description' => 'Reproductor Sony', 'price' => 150.00, 'image' => 'public\img\homeadmin\PROD003_SONY-BR-2024.png'],
                ['title' => 'RELOJ', 'description' => 'Reloj Smart band Galaxy fit 3', 'price' => 259.99, 'image' => 'public\img\homeadmin\PROD004_Smart band Galaxy Fit 3.png'],
                ['title' => 'COMPUTADORA', 'description' => 'Laptop Dell XPS', 'price' => 649.99, 'image' => 'public\img\homeadmin\PROD005_Laptop Dell XPS.png'],
                ['title' => 'COMPUTADORA', 'description' => 'ThinkP adX1', 'price' => 599.99, 'image' => 'public\img\homeadmin\PROD006_ThinkPad X1.png'],
                ['title' => 'PANTALLA', 'description' => 'Monitor or 4K', 'price' => 399.99, 'image' => 'public\img\homeadmin\PROD007_Monitor 4K.png'],
                ['title' => 'TECLADO', 'description' => 'Teclado Mecánico con RGB', 'price' => 60.00, 'image' => 'public\img\homeadmin\PROD008_Teclado Mecánico.png'],
                ['title' => 'iPHONE', 'description' => 'iphone 15 pro Max', 'price' => 1499.00, 'image' => 'public\img\homeadmin\PROD009_iPhone 15 Pro.png'],
                ['title' => 'SAMSUMG', 'description' => 'Samung Galaxy S24', 'price' => 1399.00, 'image' => 'public\img\homeadmin\PROD010_Samsung Galaxy S24.png'],
                ['title' => 'AURICULARES', 'description' => 'Auriculares Sony WH-1000M5', 'price' => 250.00, 'image' => 'public\img\homeadmin\PROD011_Auriculares Sony WH-1000XM5.png'],
                ['title' => 'AURICULARES', 'description' => 'Auriculares Bose QuietComfort 45', 'price' => 150.00, 'image' => 'public\img\homeadmin\PROD012_Bose QuietComfort 45.png'],
                ['title' => 'AURICULARES', 'description' => 'AirPod Pro', 'price' => 300.00, 'image' => 'public\img\homeadmin\PROD013_AirPods Pro.png'],
                ['title' => 'NINTENDO SWITCH', 'description' => 'Nintendo Switch OLED', 'price' => 494.99, 'image' => 'public\img\homeadmin\PROD014_Nintendo Switch OLED.png'],
                ['title' => 'PLAYSTATION', 'description' => 'PlayStation 5', 'price' => 599.99, 'image' => 'public\img\homeadmin\PROD015_PlayStation 5.png'],
                ['title' => 'XBOX', 'description' => 'Xbox series X', 'price' => 549.99, 'image' => 'public\img\homeadmin\PROD016_Xbox Series X.png'],
                ['title' => 'SAMSUMG', 'description' => 'Samung Galaxy S24 Ultra', 'price' => 1450.00, 'image' => 'public\img\homeadmin\prod46435_s24.png'],
                ['title' => 'TELEVISOR', 'description' => 'Google tv ', 'price' => 499.99, 'image' => 'public\img\homeadmin\prod82414_tv.png']
            ];


            foreach ($products as $product) {
                echo '<div class="product-card">
                        <img src="' . $product['image'] . '" class="product-image" alt="' . $product['title'] . '">
                        <div class="product-info">
                            <h3 class="product-title">' . $product['title'] . '</h3>
                            <p class="product-description">' . $product['description'] . '</p>
                            <p class="product-price">$' . $product['price'] . '</p>
                            <button class="buy-button">Comprar</button>
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
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.carousel-indicator');

        function showSlide(n) {
            slides[currentSlide].style.display = 'none';
            indicators[currentSlide].classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].style.display = 'block';
            indicators[currentSlide].classList.add('active');
        }

        function moveCarousel(n) {
            showSlide(currentSlide + n);
        }

        function setCarousel(n) {
            showSlide(n);
        }

        // Initialize carousel
        showSlide(0);

        // Auto-play carousel
        setInterval(() => moveCarousel(1), 5000);
    </script>


    <script>
        // Obtener boton Comprar
        const buyButtons = document.querySelectorAll('.buy-button');

        // Obtener los elementos del modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const modalTitle = document.getElementById('modalProductTitle');
        const modalDescription = document.getElementById('modalProductDescription');
        const modalPrice = document.getElementById('modalProductPrice');
        const modalImage = document.getElementById('modalProductImage');

        //boton de Comprar
        buyButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                // Obtener los datos del producto correspondiente
                const product = <?php echo json_encode($products); ?>[index];

                // Asignar los valores al modal
                modalTitle.textContent = product.title;
                modalDescription.textContent = product.description;
                modalPrice.textContent = product.price.toFixed(2);
                modalImage.src = product.image;

                modal.show();
            });
        });

        //Manejar la acción de Agregar al carrito o Comprar ahora
        document.getElementById('addToCartBtn').addEventListener('click', function() {
            alert('Producto agregado al carrito.');
            modal.hide();
        });

        document.getElementById('buyNowBtn').addEventListener('click', function() {
            alert('Compra realizada.');
            modal.hide();
        });
    </script>

</body>


</html>