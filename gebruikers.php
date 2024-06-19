<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$stmt_perms = $conn->prepare("SELECT deleteUserPerms FROM gebruikers WHERE id = ?");
$stmt_perms->execute([$_SESSION['user_id']]);
$current_user_perms = $stmt_perms->fetchColumn();

$stmt_perms2 = $conn->prepare("SELECT ChangeUserType FROM gebruikers WHERE id = ?");
$stmt_perms2->execute([$_SESSION['user_id']]);
$change_usertype_perms = $stmt_perms2->fetchColumn();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['user_type'] == 'admin') {
        $user_id = $_POST['user_id'];

        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'update') {
                $new_type = $_POST['new_type'];

                $stmt_check_type = $conn->prepare("SELECT type FROM gebruikers WHERE id = ?");
                $stmt_check_type->execute([$user_id]);
                $current_type = $stmt_check_type->fetchColumn();

                if ($current_type != $new_type) {
                    if ($change_usertype_perms) {

                        $update_stmt = $conn->prepare("UPDATE gebruikers SET type = ? WHERE id = ?");
                        $update_stmt->execute([$new_type, $user_id]);

                        $_SESSION['notification'] = "success: Gebruiker is succesvol geupdate naar: " . strtoupper($new_type);
                    }
                } else {
                    $_SESSION['notification'] = "error: Gebruiker is al een " . strtoupper($new_type);
                }
            } elseif ($_POST['action'] == 'update_perms') {
                $new_delete_perms = $_POST['new_delete_perms'];

                $update_perms_stmt = $conn->prepare("UPDATE gebruikers SET deleteUserPerms = ? WHERE id = ?");
                $update_perms_stmt->execute([$new_delete_perms, $user_id]);

                $_SESSION['notification'] = "success: Gebruiker delete permissions succesvol geupdate";
            } elseif ($_POST['action'] == 'delete') {
                if ($current_user_perms) {
                    $delete_stmt = $conn->prepare("DELETE FROM gebruikers WHERE id = ?");
                    $delete_stmt->execute([$user_id]);

                    $_SESSION['notification'] = "success: Gebruiker is succesvol verwijderd";
                }
            }
        }

        header("Location: gebruikers.php");
        exit();
    } else {
        header("Location: dashboard.php");
    }
}

$stmt = $conn->prepare("SELECT * FROM gebruikers WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" <?php echo isset($_SESSION['notification']) ? 'class="has-notification"' : ''; ?>>

<head>
    <title>Index pagina | Anas</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-XmkrJH8wBPk1xM/wmMv7XL3xYLcOsMyfAObt3TSp6AdnOJyrtJLSvuC1M6NzsUGsR/XX0GkrDhKzeMt6ahVdbw==" crossorigin="anonymous" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">
            <h2>ANAS</h2>
        </a>
        <ul class="links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if ($_SESSION['user_type'] == 'admin') { ?>
                <li><a href="gebruikers.php">Gebruikers</a></li>
            <?php } ?>
        </ul>
        <div class="gebruikersButtons">
            <?php if ($_SESSION['user_type'] == 'admin') { ?>
                <a href="add-user.php" class="login-btn">ADD</a>
            <?php } ?>
            <a href="logout.php" class="login-btn">LOG UIT</a>
        </div>

    </nav>
    <div class="dashboard">
        <h2>Gebruikers:</h2>
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

    <table class="styled-table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <?php
                if ($change_usertype_perms) {
                ?>
                    <th>Type</th>
                <?php
                }
                ?>
                <?php
                if ($current_user_perms) {
                ?>
                    <th>Delete Perms</th>
                    <th>Acties</th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user['full_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <?php
                    if ($change_usertype_perms) {
                    ?>
                        <td>
                            <form method="post" class="type-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="new_type" class="type-select">
                                    <option value="user" <?= $user['type'] == 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <input type="hidden" name="action" value="update">
                            </form>
                        </td>
                    <?php
                    }
                    if ($current_user_perms) {
                    ?>
                        <td>
                            <form method="post" class="perms-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="new_delete_perms" class="perms-select">
                                    <option value="1" <?= $user['deleteUserPerms'] ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= !$user['deleteUserPerms'] ? 'selected' : '' ?>>No</option>
                                </select>
                                <input type="hidden" name="action" value="update_perms">
                            </form>
                        </td>
                        <td>
                            <form method="post" class="delete-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button name="action" value="delete" class="updateButtonDelete" type="submit" <?php echo !$current_user_perms ? 'disabled' : ''; ?>>Verwijder</button>
                            </form>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        document.querySelectorAll('.type-select').forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        document.querySelectorAll('.perms-select').forEach(select => {
            select.addEventListener('change', function() {
                const userType = this.closest('tr').querySelector('.type-select').value;
                if (userType === 'user') {
                    this.querySelector('option[value="1"]').disabled = true;
                    this.value = "0";
                } else {
                    this.querySelector('option[value="1"]').disabled = false;
                }
                this.closest('form').submit();
            });
        });

        document.querySelectorAll('.type-select').forEach(select => {
            const userType = select.value;
            const deletePermsSelect = select.closest('tr').querySelector('.perms-select');
            if (userType === 'user') {
                deletePermsSelect.querySelector('option[value="1"]').disabled = true;
                if (deletePermsSelect.value === "1") {
                    deletePermsSelect.value = "0";
                }
            } else {
                deletePermsSelect.querySelector('option[value="1"]').disabled = false;
            }
        });
    </script>
</body>

</html>