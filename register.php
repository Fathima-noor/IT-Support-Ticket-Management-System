
<?php
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid name and email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $name, $email, $hash);
            $stmt->execute();

            header("Location: login.php?registered=1");
            exit;
        } catch (mysqli_sql_exception $ex) {
            if ($ex->getCode() === 1062) {
                $error = "This email is already registered.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <a class="brand" href="index.php">SupportDesk</a>
    <h1>Create Account</h1>
    <p class="muted">Register to submit and track support tickets.</p>

    <?php if ($error): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required maxlength="100"
               value="<?= e($_POST["name"] ?? "") ?>">

        <label>Email Address</label>
        <input type="email" name="email" required maxlength="150"
               value="<?= e($_POST["email"] ?? "") ?>">

        <label>Password</label>
        <input type="password" name="password" required minlength="8">

        <button class="button full-width" type="submit">
            Create Account
        </button>
    </form>

    <p class="auth-switch">
        Already registered? <a href="login.php">Sign in</a>
    </p>
</div>
</body>
</html>
