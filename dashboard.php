
<?php
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] === "admin") {
    header("Location: admin.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT id, title, category, priority, status, created_at
     FROM tickets WHERE user_id = ?
     ORDER BY created_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$tickets = $stmt->get_result();

$counts = ["total" => 0, "open" => 0, "progress" => 0, "resolved" => 0];

$stmt = $conn->prepare(
    "SELECT
        COUNT(*) AS total,
        SUM(status = 'Open') AS open_count,
        SUM(status = 'In Progress') AS progress_count,
        SUM(status = 'Resolved') AS resolved_count
     FROM tickets WHERE user_id = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();

$counts["total"] = $summary["total"] ?? 0;
$counts["open"] = $summary["open_count"] ?? 0;
$counts["progress"] = $summary["progress_count"] ?? 0;
$counts["resolved"] = $summary["resolved_count"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="dashboard.php">SupportDesk</a>
    <nav>
        <span class="welcome">Hi, <?= e($_SESSION["name"]) ?></span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container">
    <div class="page-heading">
        <div>
            <h1>My Dashboard</h1>
            <p class="muted">Manage and track your IT support requests.</p>
        </div>
        <a class="button" href="create_ticket.php">+ Create Ticket</a>
    </div>

    <section class="stats-grid">
        <div class="stat-card"><span>Total Tickets</span><strong><?= $counts["total"] ?></strong></div>
        <div class="stat-card"><span>Open</span><strong><?= $counts["open"] ?></strong></div>
        <div class="stat-card"><span>In Progress</span><strong><?= $counts["progress"] ?></strong></div>
        <div class="stat-card"><span>Resolved</span><strong><?= $counts["resolved"] ?></strong></div>
    </section>

    <section class="panel">
        <h2>My Support Tickets</h2>
        <?php if ($tickets->num_rows === 0): ?>
            <div class="empty-state">
                <h3>No tickets yet</h3>
                <p>Create a ticket to report your first technical issue.</p>
                <a class="button" href="create_ticket.php">Create Ticket</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Title</th><th>Category</th>
                            <th>Priority</th><th>Status</th><th>Created</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($ticket = $tickets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $ticket["id"] ?></td>
                            <td><?= e($ticket["title"]) ?></td>
                            <td><?= e($ticket["category"]) ?></td>
                            <td><?= e($ticket["priority"]) ?></td>
                            <td><span class="status"><?= e($ticket["status"]) ?></span></td>
                            <td><?= e($ticket["created_at"]) ?></td>
                            <td><a href="view_ticket.php?id=<?= $ticket["id"] ?>">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
