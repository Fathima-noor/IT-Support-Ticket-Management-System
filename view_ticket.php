
<?php
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit("Invalid ticket ID.");
}

if ($_SESSION["role"] === "admin") {
    $stmt = $conn->prepare(
        "SELECT t.*, u.name AS user_name, u.email AS user_email
         FROM tickets t JOIN users u ON t.user_id = u.id
         WHERE t.id = ?"
    );
    $stmt->bind_param("i", $id);
} else {
    $userId = (int)$_SESSION["user_id"];
    $stmt = $conn->prepare(
        "SELECT t.*, u.name AS user_name, u.email AS user_email
         FROM tickets t JOIN users u ON t.user_id = u.id
         WHERE t.id = ? AND t.user_id = ?"
    );
    $stmt->bind_param("ii", $id, $userId);
}

$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) {
    http_response_code(404);
    exit("Ticket not found.");
}

$back = $_SESSION["role"] === "admin" ? "admin.php" : "dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?= $ticket["id"] ?> | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="<?= e($back) ?>">SupportDesk</a>
    <nav><a href="<?= e($back) ?>">Dashboard</a><a href="logout.php">Logout</a></nav>
</header>

<main class="form-container">
    <a class="back-link" href="<?= e($back) ?>">← Back</a>
    <section class="panel">
        <div class="page-heading">
            <div>
                <span class="muted">Ticket #<?= $ticket["id"] ?></span>
                <h1><?= e($ticket["title"]) ?></h1>
            </div>
            <span class="status"><?= e($ticket["status"]) ?></span>
        </div>

        <div class="detail-grid">
            <div><span>Submitted By</span><strong><?= e($ticket["user_name"]) ?></strong></div>
            <div><span>Email</span><strong><?= e($ticket["user_email"]) ?></strong></div>
            <div><span>Category</span><strong><?= e($ticket["category"]) ?></strong></div>
            <div><span>Priority</span><strong><?= e($ticket["priority"]) ?></strong></div>
            <div><span>Created</span><strong><?= e($ticket["created_at"]) ?></strong></div>
        </div>

        <h3>Description</h3>
        <p class="description"><?= nl2br(e($ticket["description"])) ?></p>

        <?php if ($_SESSION["role"] === "admin"): ?>
            <a class="button" href="update_ticket.php?id=<?= $ticket["id"] ?>">
                Update Ticket Status
            </a>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
