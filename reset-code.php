<?php
session_start();
include 'db_conn.php';

if (isset($_SESSION['user_id']) || isset($_SESSION['user_email'])) {
    header("Location: admin/dashboard.php");
    exit;
}
if (isset($_POST['check-reset-otp'])) {
    session_start();
    $otp_code = $_POST['otp'];

    $check_code = $conn->prepare("SELECT * FROM gebruikers WHERE code = :otp_code");
    $check_code->bindParam(':otp_code', $otp_code);
    $check_code->execute();

    if ($check_code->rowCount() > 0) {
        $fetch_data = $check_code->fetch(PDO::FETCH_ASSOC);
        header('location: new-password.php');
        exit();
    } else {
        $errors['otp-error'] = "Ongeldige code ingevoerd!";
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
                <h2>Code Verificatie</h2>
                <form action="reset-code.php" method="POST">
                    <div class="form">
                        <input type="number" name="otp" placeholder="Code" required>
                    </div>
                    <input class="knoppie" type="submit" name="check-reset-otp" value="Indienen">
                </form>
            </div>
        </div>
</body>

</html>