
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

$summary = $conn->query(
    "SELECT COUNT(*) AS total,
            SUM(status = 'Open') AS open_count,
            SUM(status = 'In Progress') AS progress_count,
            SUM(status = 'Resolved') AS resolved_count
     FROM tickets"
)->fetch_assoc();

$tickets = $conn->query(
    "SELECT t.id, t.title, t.category, t.priority,
            t.status, t.created_at, u.name AS user_name
     FROM tickets t
     JOIN users u ON t.user_id = u.id
     ORDER BY t.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="admin.php">SupportDesk Admin</a>
    <nav>
        <span class="welcome"><?= e($_SESSION["name"]) ?></span>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container">
    <div class="page-heading">
        <div>
            <h1>Admin Dashboard</h1>
            <p class="muted">Monitor and manage all support requests.</p>
        </div>
    </div>

    <section class="stats-grid">
        <div class="stat-card"><span>Total Tickets</span><strong><?= $summary["total"] ?? 0 ?></strong></div>
        <div class="stat-card"><span>Open</span><strong><?= $summary["open_count"] ?? 0 ?></strong></div>
        <div class="stat-card"><span>In Progress</span><strong><?= $summary["progress_count"] ?? 0 ?></strong></div>
        <div class="stat-card"><span>Resolved</span><strong><?= $summary["resolved_count"] ?? 0 ?></strong></div>
    </section>

    <section class="panel">
        <h2>All Support Tickets</h2>
        <?php if ($tickets->num_rows === 0): ?>
            <div class="empty-state">
                <h3>No tickets found</h3>
                <p>Tickets submitted by users will appear here.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>User</th><th>Title</th>
                            <th>Category</th><th>Priority</th>
                            <th>Status</th><th>Created</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($t = $tickets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $t["id"] ?></td>
                            <td><?= e($t["user_name"]) ?></td>
                            <td><?= e($t["title"]) ?></td>
                            <td><?= e($t["category"]) ?></td>
                            <td><?= e($t["priority"]) ?></td>
                            <td><span class="status"><?= e($t["status"]) ?></span></td>
                            <td><?= e($t["created_at"]) ?></td>
                            <td>
                                <a href="view_ticket.php?id=<?= $t["id"] ?>">View</a>
                                |
                                <a href="update_ticket.php?id=<?= $t["id"] ?>">Update</a>
                            </td>
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
