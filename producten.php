<?php
session_start();
include 'admin/db_conn.php';

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" <?php echo isset($_SESSION['notification']) ? 'class="has-notification"' : ''; ?>>


<head>
    <title>Product pagina | Anas</title>
    <link rel="stylesheet" href="style.css?v=4">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XmkrJH8wBPk1xM/wmMv7XL3xYLcOsMyfAObt3TSp6AdnOJyrtJLSvuC1M6NzsUGsR/XX0GkrDhKzeMt6ahVdbw==" crossorigin="anonymous" />
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
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
        <a href="cart.php" class="centerButton"><box-icon class="cart" name="cart"></box-icon></a>
    </nav>
    <div class="dashboard">
        <h2>PRODUCTEN</h2>
        <?php
        $notificationClass = '';
        if (isset($_SESSION['notification'])) {
            if (strpos($_SESSION['notification'], 'error') !== false) {
                $notificationClass = 'errorAlert';
                $iconClass = 'fa-exclamation-circle';
            } elseif (strpos($_SESSION['notification'], 'success') !== false) {
                $notificationClass = 'successAlert';
                $iconClass = 'fa-check-circle';
            } else {
                $notificationClass = 'infoAlert';
                $iconClass = 'fa-info-circle';
            }
        }
        ?>
        <div class="<?php echo $notificationClass; ?>">
            <i class="fa solid <?php echo $iconClass; ?>"></i>
            <?php
            if (isset($_SESSION['notification'])) {
                echo $_SESSION['notification'];
                unset($_SESSION['notification']);
            }
            ?>
        </div>
    </div>
    <div class="producten">
        <?php
        $stmt = $conn->prepare("SELECT * FROM producten");
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $product_id = $product['id'];
            $product_name = $product['naam'];
            $product_price = $product['prijs'];
            $product_url = $product['img'];

            echo "
            <div class='product-card'>
                <img src='$product_url' alt='$product_name' class='product-image'>
                <div class='product-info'>
                    <h2 class='product-name'>$product_name</h2>
                    <p class='product-price'>€ $product_price</p>
                    <button class='buy-button' data-id='$product_id'>Aan winkelwagen toevoegen</button>
                </div>
            </div>
            ";
        }
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.buy-button').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');

                    fetch('add_to_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `product_id=${productId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                <?php $_SESSION['notification'] = "error: Er is een fout opgetreden bij het toevoegen aan winkelwagen!"; ?>
                            } else {
                                <?php $_SESSION['notification'] = "success: Product successvol aan winkelwagen toegevoegd!"; ?>
                            }
                            location.reload();
                        })
                });
            });
        });
    </script>
</body>

</html>