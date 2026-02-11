<?php
// Get database credentials from environment variables
$host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?? '';
$user = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?? '';
$pass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS') ?? '';
$db_name = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?? '';
$port = $_ENV['MYSQL_PORT'] ?? $_SERVER['MYSQL_PORT'] ?? getenv('MYSQL_PORT') ?? 3306;

// Debug: Show if variables are missing
if (empty($host) || empty($user) || empty($db_name)) {
    die("<h2>Database Configuration Error</h2>
    <p>Missing environment variables:</p>
    <ul>
        <li>DB_HOST: " . (empty($host) ? '❌ NOT SET' : '✓ ' . $host) . "</li>
        <li>DB_USER: " . (empty($user) ? '❌ NOT SET' : '✓ ' . $user) . "</li>
        <li>DB_PASS: " . (empty($pass) ? '❌ NOT SET' : '✓ SET') . "</li>
        <li>DB_NAME: " . (empty($db_name) ? '❌ NOT SET' : '✓ ' . $db_name) . "</li>
        <li>MYSQL_PORT: " . (empty($port) ? '❌ NOT SET' : '✓ ' . $port) . "</li>
    </ul>
    <p>Go to your Render Web Service → Environment tab → Add the missing variables.</p>");
}

$conn = new mysqli($host, $user, $pass, $db_name, (int)$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
