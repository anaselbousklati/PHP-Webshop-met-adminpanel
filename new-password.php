<?php
session_start();
include 'admin/db_conn.php';

if (isset($_SESSION['user_id']) || isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['change-password'])) {
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        echo "Wachtwoorden matchen niet";
    } else {
        $code = 0;
        $email = $_SESSION['email'];
        $encpass = password_hash($password, PASSWORD_BCRYPT);

        $update_pass = $conn->prepare("UPDATE gebruikers SET code = :code, password = :password WHERE email = :email");
        $update_pass->bindParam(':code', $code);
        $update_pass->bindParam(':password', $encpass);
        $update_pass->bindParam(':email', $email);
        $update_pass->execute();

        if ($update_pass->rowCount() > 0) {
            header('Location: login.php');
            exit();
        } else {
            echo "Failed to change your password!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Wachtwoord reset | Anas</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="box">
                <h2>Nieuw Wachtwoord</h2>
                <form action="new-password.php" method="POST">
                    <div class="form">
                        <input type="password" name="password" placeholder="Nieuw Wachtwoord" required>
                    </div>
                    <div class="form">
                        <input type="password" name="cpassword" placeholder="Verifeer je Wachtwoord" required>
                    </div>
                    <input class="knoppie" type="submit" name="change-password" value="Wijzigen">
                </form>
            </div>
        </div>
</body>

</html>