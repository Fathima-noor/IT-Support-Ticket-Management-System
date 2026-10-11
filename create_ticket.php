
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

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $category = $_POST["category"] ?? "";
    $priority = $_POST["priority"] ?? "Medium";

    $categories = ["Hardware", "Software", "Network", "Account", "Other"];
    $priorities = ["Low", "Medium", "High"];

    if ($title === "" || $description === "") {
        $error = "Please complete all required fields.";
    } elseif (
        !in_array($category, $categories, true) ||
        !in_array($priority, $priorities, true)
    ) {
        $error = "Select a valid category and priority.";
    } else {
        $userId = (int)$_SESSION["user_id"];

        $stmt = $conn->prepare(
            "INSERT INTO tickets
             (user_id, title, description, category, priority)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "issss",
            $userId,
            $title,
            $description,
            $category,
            $priority
        );
        $stmt->execute();

        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket | SupportDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="dashboard.php">SupportDesk</a>
    <nav><a href="dashboard.php">Dashboard</a><a href="logout.php">Logout</a></nav>
</header>

<main class="form-container">
    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
    <section class="panel">
        <h1>Create Support Ticket</h1>
        <p class="muted">Describe the technical issue you are experiencing.</p>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Issue Title</label>
            <input name="title" maxlength="200" required
                   placeholder="e.g. Laptop is not starting"
                   value="<?= e($_POST["title"] ?? "") ?>">

            <label>Category</label>
            <select name="category" required>
                <option value="">Choose a category</option>
                <?php foreach (["Hardware", "Software", "Network", "Account", "Other"] as $c): ?>
                    <option value="<?= e($c) ?>"
                        <?= ($_POST["category"] ?? "") === $c ? "selected" : "" ?>>
                        <?= e($c) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Priority</label>
            <select name="priority" required>
                <?php foreach (["Low", "Medium", "High"] as $p): ?>
                    <option value="<?= e($p) ?>"
                        <?= ($_POST["priority"] ?? "Medium") === $p ? "selected" : "" ?>>
                        <?= e($p) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Problem Description</label>
            <textarea name="description" rows="6" required
                      placeholder="Explain the problem and what happened..."><?= e($_POST["description"] ?? "") ?></textarea>

            <button class="button full-width" type="submit">Submit Ticket</button>
        </form>
    </section>
</main>
</body>
</html>
