<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $conn->prepare("SELECT gebruikers.id AS user_id, gebruikers.email, MAX(bestellingen.order_date) AS last_order_date
                        FROM bestellingen 
                        JOIN gebruikers ON bestellingen.user_id = gebruikers.id 
                        GROUP BY gebruikers.id 
                        ORDER BY last_order_date DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bestellingen Overzicht | Anas</title>
    <link rel="stylesheet" href="../style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XmkrJH8wBPk1xM/wmMv7XL3xYLcOsMyfAObt3TSp6AdnOJyrtJLSvuC1M6NzsUGsR/XX0GkrDhKzeMt6ahVdbw==" crossorigin="anonymous" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="">Dashboard</a></li>
            <?php if ($_SESSION['user_type'] == 'admin') { ?>
                <li><a href="gebruikers.php">Gebruikers</a></li>
            <?php } ?>
        </ul>
        <div class="centerButton">
            <form action="../logout.php" method="get">
                <button type="submit" class="login-btn">LOG UIT</button>
            </form>
        </div>
    </nav>
    <div class="dashboard">
        <h2>Gebruikers en hun laatste bestellingen:</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Laatste Besteldatum</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_order_date']); ?></td>
                        <td>
                            <a href="orders_detail.php?user_id=<?php echo $user['user_id']; ?>"><button class="updateButton">Bekijk Bestellingen</button></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>