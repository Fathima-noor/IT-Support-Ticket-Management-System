
<?php
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Access denied.");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit("Invalid ticket ID.");
}

$stmt = $conn->prepare(
    "SELECT id, title, status FROM tickets WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) {
    http_response_code(404);
    exit("Ticket not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $_POST["status"] ?? "";
    $allowed = ["Open", "In Progress", "Resolved"];

    if (!in_array($status, $allowed, true)) {
        $error = "Choose a valid status.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE tickets SET status = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        header("Location: view_ticket.php?id=" . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ticket | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="admin.php">SupportDesk</a>
    <nav><a href="admin.php">Admin Dashboard</a><a href="logout.php">Logout</a></nav>
</header>

<main class="form-container">
    <section class="panel">
        <h1>Update Ticket Status</h1>
        <h3><?= e($ticket["title"]) ?></h3>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Status</label>
            <select name="status" required>
                <?php foreach (["Open", "In Progress", "Resolved"] as $s): ?>
                    <option value="<?= e($s) ?>"
                        <?= $ticket["status"] === $s ? "selected" : "" ?>>
                        <?= e($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="button full-width" type="submit">Save Status</button>
        </form>

        <p><a href="view_ticket.php?id=<?= $id ?>">Cancel</a></p>
    </section>
</main>
</body>
</html>
