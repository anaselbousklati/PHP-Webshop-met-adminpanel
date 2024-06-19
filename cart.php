<?php
session_start();
include 'db_conn.php';

$user_id = 49;
$user_email = 'scipto19990@gmail.com';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = intval($_POST['quantity']);
        if ($new_quantity > 0) {
            $_SESSION['cart'][$product_id] = $new_quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif (isset($_POST['place_order'])) {
        $order_success = true;

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("INSERT INTO bestellingen (user_id, product_id, quantity) VALUES (?, ?, ?)");
            if (!$stmt->execute([$user_id, $product_id, $quantity])) {
                $order_success = false;
                break;
            }
        }

        if ($order_success) {
            $_SESSION['notification'] = "success: Uw bestelling is geplaatst!";
            unset($_SESSION['cart']);
        } else {
            $_SESSION['notification'] = "error: Er is een fout opgetreden bij het plaatsen van uw bestelling.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" <?php echo isset($_SESSION['notification']) ? 'class="has-notification"' : ''; ?>>

<head>
    <title>Winkelwagen pagina | Anas</title>
    <link rel="stylesheet" href="style.css?v=12">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XmkrJH8wBPk1xM/wmMv7XL3xYLcOsMyfAObt3TSp6AdnOJyrtJLSvuC1M6NzsUGsR/XX0GkrDhKzeMt6ahVdbw==" crossorigin="anonymous" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="index.php">Home</a></li>
            <li><a href="producten.php">Producten</a></li>
        </ul>
        <div class="centerButton">
            <form action="dashboard.php" method="get">
                <button type="submit" class="login-btn">Admin Panel</button>
            </form>
        </div>
    </nav>
    <div class="dashboard">
        <h2>Winkelwagen:</h2>
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

    <div class="cart">
        <?php
        $totalPrice = 0;
        if (!empty($_SESSION['cart'])) {
        ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Prijs</th>
                        <th>Aantal</th>
                        <th>Subtotaal</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $stmt = $conn->prepare("SELECT naam, prijs FROM producten WHERE id = ?");
                        $stmt->execute([$product_id]);
                        $product = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($product) {
                            $subTotal = $product['prijs'] * $quantity;
                            $totalPrice += $subTotal;
                            echo "<tr>
                                <td>" . htmlspecialchars($product['naam']) . "</td>
                                <td>€" . htmlspecialchars($product['prijs']) . "</td>
                                <td>
                                <form method='post' action='cart.php' class='update-form'>
                                <input type='hidden' name='product_id' value='$product_id'>
                                <input type='number' name='quantity' value='$quantity' min='1' class='quantity-input'>
                                <button class='updateButton' type='submit' name='update_quantity'>Update</button>
                            </form>
                            
                                </td>
                                <td>€" . htmlspecialchars($subTotal) . "</td>
                                <td>
                                    <form method='post' action='cart.php'>
                                        <input type='hidden' name='product_id' value='$product_id'>
                                        <button class='updateButton' type='submit' name='remove'>Verwijderen</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="totaal">
                <h2>Totaalprijs: €<?php echo htmlspecialchars($totalPrice); ?></h2>
                <form method="post" action="cart.php">
                    <button type="submit" name="place_order" class="order-button">Bestelling plaatsen</button>
                </form>
            </div>
        <?php } else { ?>
            <div class="dashboard">
                <p>Winkelwagen is leeg.</p>
            </div>
        <?php } ?>
    </div>
</body>

</html>