<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

function generatePassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $password;
}

if (!isset($_SESSION['generatedPassword'])) {
    $_SESSION['generatedPassword'] = generatePassword();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['user_type'] !== 'admin') {
        header("Location: dashboard.php");
        exit;
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $type = $_POST['type'];

    $check_stmt = $conn->prepare("SELECT COUNT(*) AS num FROM gebruikers WHERE email = ?");
    $check_stmt->execute([$email]);
    $row = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        echo 'Email bestaat al.';
    } else {
        $wachtwoord = $_SESSION['generatedPassword'];
        $password = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO gebruikers (full_name, email, password, type) VALUES (?, ?, ?, ?)");
        $insert_stmt->execute([$name, $email, $password, $type]);

        $subject = "Je bent toegevoegd aan het panel van Anas als $type";
        $message = "Je automatisch gegenereerd wachtwoord is: $wachtwoord";
        $headers = "From: gadgetwave1@gmail.com";
        mail($email, $subject, $message, $headers);

        unset($_SESSION['generatedPassword']);

        header("Location: gebruikers.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Index pagina | Anas</title>
    <link rel="stylesheet" href="../style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if ($_SESSION['user_type'] == 'admin') : ?>
                <li><a href="gebruikers.php">Gebruikers</a></li>
            <?php endif; ?>
        </ul>
        <div class="centerButton">
            <form action="logout.php" method="get">
                <button type="submit" class="login-btn">LOG UIT</button>
            </form>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Gebruiker toevoegen:</h2>
    </div>
    <div class="formAdduser">
        <form method="post">
            <div>
                <input type="text" id="name" name="name" placeholder="Naam" required>
            </div>
            <div>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($_SESSION['generatedPassword']); ?>" readonly>
            </div>
            <div>
                <select id="type" name="type">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit">Toevoegen</button>
        </form>
    </div>
</body>

</html>