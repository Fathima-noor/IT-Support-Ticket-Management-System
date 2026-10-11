
<?php
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header(
        "Location: " .
        ($_SESSION["role"] === "admin" ? "admin.php" : "dashboard.php")
    );
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, name, email, password, role
         FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    }

    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <a class="brand" href="index.php">SupportDesk</a>
    <h1>Welcome Back</h1>
    <p class="muted">Sign in to access your support dashboard.</p>

    <?php if (isset($_GET["registered"])): ?>
        <div class="alert success">Registration successful. Please sign in.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="button full-width" type="submit">Sign In</button>
    </form>

    <p class="auth-switch">
        Don't have an account? <a href="register.php">Register</a>
    </p>
</div>
</body>
</html>
