<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=33">
    <title>Home</title>
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="index.php">Home</a></li>
            <li><a href="producten.php">Producten</a></li>
        </ul>
        <div class="centerButton">
            <form action="admin/dashboard.php" method="get">
                <button type="submit" class="login-btn">Admin Panel</button>
            </form>
        </div>
    </nav>
    <div class="background-image">
        <a href="#featured-products" class="button-link">Winkel Nu!</a>
    </div>
    <section id="featured-products" class="featured-products">
        <h2>Populaire Producten</h2>
        <div class="products-grid">
            <div class="product">
                <img src="https://i.imgur.com/B2FxR3f.png" alt="Product 1">
                <h3>Iphone 13</h3>
                <p>€899</p>
                <a href="producten.php" class="buy-btn">Koop Nu</a>
            </div>
            <div class="product">
                <img src="https://i.imgur.com/jOzORSm.png" alt="Product 2">
                <h3>Iphone 15</h3>
                <p>€1499</p>
                <a href="producten.php" class="buy-btn">Koop Nu</a>
            </div>
            <div class="product">
                <img src="https://i.imgur.com/B2FxR3f.png" alt="Product 3">
                <h3>Iphone 13</h3>
                <p>€899</p>
                <a href="producten.php" class="buy-btn">Koop Nu</a>
            </div>
        </div>
    </section>

    <section class="testimonials">
        <h2>Wat Onze Klanten Zeggen</h2>
        <div class="testimonials-grid">
            <div class="testimonial">
                <p>"Geweldige service en snelle verzending. Zeer aan te bevelen!"</p>
                <h4>- Jan Jansen</h4>
            </div>
            <div class="testimonial">
                <p>"Geweldige producten tegen geweldige prijzen. Zeer tevreden met mijn aankoop."</p>
                <h4>- Anne de Vries</h4>
            </div>
            <div class="testimonial">
                <p>"De klantenservice was erg behulpzaam en responsief. Ik zal hier weer kopen."</p>
                <h4>- Emily de Jong</h4>
            </div>
            <div class="testimonial">
                <p>"Uitstekende kwaliteit en snelle levering. Zeker een aanrader!"</p>
                <h4>- Kees Bakker</h4>
            </div>
        </div>
    </section>


    <footer>
        <div class="footer-content">
            <h3>ANAS</h3>
            <p>Uw nummer één bron voor alle elektronische gadgets.</p>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 ANAS | Ontworpen door Anas</p>
        </div>
    </footer>

</body>

</html>