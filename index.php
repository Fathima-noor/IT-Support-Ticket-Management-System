
<?php
require_once "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Desk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="index.php">SupportDesk</a>
    <nav>
        <a href="login.php">Login</a>
        <a class="nav-button" href="register.php">Get Started</a>
    </nav>
</header>

<main class="hero">
    <div class="hero-content">
        <span class="eyebrow">IT SUPPORT MANAGEMENT</span>
        <h1>Technical problems?<br><span>We've got you covered.</span></h1>
        <p>
            Report technical issues, track support requests,
            and get the help you need from one simple dashboard.
        </p>
        <div class="hero-actions">
            <a class="button" href="register.php">Create an Account</a>
            <a class="button button-outline" href="login.php">Sign In</a>
        </div>
    </div>

    <div class="hero-card">
        <div class="hero-icon">✓</div>
        <h2>Your IT Helpdesk</h2>
        <p>One place to report and manage technical issues.</p>
        <div class="feature">✓ Easy ticket creation</div>
        <div class="feature">✓ Real-time status tracking</div>
        <div class="feature">✓ Priority-based support</div>
    </div>
</main>

<footer class="footer">
    IT Support Ticket Management System
</footer>
</body>
</html>
