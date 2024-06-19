<?php
session_start();
include 'db_conn.php';

if (isset($_SESSION['user_id']) || isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['check-email'])) {
    $email = $_POST['email'];

    $check_email = $conn->prepare("SELECT * FROM gebruikers WHERE email = :email");
    $check_email->bindParam(':email', $email);
    $check_email->execute();

    if ($check_email->rowCount() > 0) {
        $code = rand(100000, 999999);

        $update_code = $conn->prepare("UPDATE gebruikers SET code = :code WHERE email = :email");
        $update_code->bindParam(':code', $code);
        $update_code->bindParam(':email', $email);
        $update_code->execute();

        if ($update_code) {
            $subject = "Wachtwoord Reset Code";
            $message = "Je verificatie code is: $code";
            $headers = "Van: gadgetwave1@gmail.com";

            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                echo "Failed while sending code!";
            }
        } else {
            echo "Something went wrong!";
        }
    } else {
        echo "This email address does not exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Wachtwoord vergeten | Anas</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="box">
                <h2>Wachtwoord resetten</h2>
                <form action="forgot-password.php" method="POST">
                    <div class="form">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <input class="knoppie" type="submit" name="check-email" value="Doorgaan">
                </form>
            </div>
        </div>
</body>

</html>