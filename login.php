<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    header("Location: admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Login pagina | Anas</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="box">
                <h2>Login</h2>
                <?php if (isset($_GET['error'])) { ?>
                    <span class="errorMessage">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </span>
                <?php } ?>
                <form action="login-user.php" method="POST">
                    <div class="form">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form">
                        <input type="password" name="password" placeholder="Wachtwoord" required>
                    </div>
                    <a href="forgot-password.php" class="vergeten">Wachtwoord vergeten</a>
                    <button type="submit" class="knoppie">Login</button>
                </form>
            </div>
        </div>
</body>

</html>