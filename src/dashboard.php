<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <div class="nav-content">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    <div class="container dashboard">
        <h1>Dashboard</h1>
        <p>You have successfully logged in.</p>
        <p>This is a protected page tailored for <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>.</p>
    </div>
</body>
</html>
