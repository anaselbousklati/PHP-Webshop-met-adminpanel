<?php
session_start();
include 'admin/db_conn.php';

if (isset($_POST['email']) && isset($_POST['password'])) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	if (empty($email)) {
		header("Location: login.php?error=Email is vereist!");
	} else if (empty($password)) {
		header("Location: login.php?error=Wachtwoord is vereist!&email=$email");
	} else {
		$stmt = $conn->prepare("SELECT * FROM gebruikers WHERE email=?");
		$stmt->execute([$email]);

		if ($stmt->rowCount() === 1) {
			$user = $stmt->fetch();

			$user_id = $user['id'];
			$user_email = $user['email'];
			$user_password = $user['password'];
			$user_full_name = $user['full_name'];
			$user_type = $user['type'];

			if ($email === $user_email) {
				if (password_verify($password, $user_password)) {
					$_SESSION['user_id'] = $user_id;
					$_SESSION['user_email'] = $user_email;
					$_SESSION['user_full_name'] = $user_full_name;
					$_SESSION['user_type'] = $user_type;
					header("Location: admin/dashboard.php");
				} else {
					header("Location: login.php?error=Onjuiste Wachtwoord&email=$email");
				}
			} else {
				header("Location: login.php?error=Verkeerde email of wachtwoord&email=$email");
			}
		} else {
			header("Location: login.php?error=Verkeerde email of wachtwoord&email=$email");
		}
	}
}
