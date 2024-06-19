<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT bestellingen.id AS order_id, gebruikers.email, producten.naam, bestellingen.quantity, bestellingen.order_date 
                        FROM bestellingen 
                        JOIN gebruikers ON bestellingen.user_id = gebruikers.id 
                        JOIN producten ON bestellingen.product_id = producten.id 
                        WHERE gebruikers.id = ? 
                        ORDER BY bestellingen.order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$orders) {
    header("Location: dashboard.php");
    exit();
}

$email = $orders[0]['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bestellingen Details | Anas</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XmkrJH8wBPk1xM/wmMv7XL3xYLcOsMyfAObt3TSp6AdnOJyrtJLSvuC1M6NzsUGsR/XX0GkrDhKzeMt6ahVdbw==" crossorigin="anonymous" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="">Dashboard</a></li>
            <?php if ($_SESSION['user_type'] == 'admin') { ?>
                <li><a href="gebruikers.php">Gebruikers</a></li>
            <?php } ?>
        </ul>
        <div class="centerButton">
            <form action="dashboard.php" method="get">
                <button type="submit" class="login-btn">Admin Panel</button>
            </form>
        </div>
    </nav>
    <div class="dashboard">
        <h2>Bestellingen van <?php echo htmlspecialchars($email); ?>:</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Besteldatum</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['naam']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <form method="POST" action="cancel_order.php">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" class="updateButton">Annuleren</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="updateButton margintop" onclick="location.href='dashboard.php'">Terug naar Overzicht</button>
    </div>
</body>

</html>