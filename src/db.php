<?php
// PostgreSQL Connection (Render recommended)
$database_url = $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'] ?? getenv('DATABASE_URL') ?? '';

if (!empty($database_url)) {
    // Parse PostgreSQL connection string: postgresql://user:pass@host:port/dbname
    $db_config = parse_url($database_url);
    $host = $db_config['host'] ?? '';
    $user = $db_config['user'] ?? '';
    $pass = $db_config['pass'] ?? '';
    $db_name = ltrim($db_config['path'] ?? '', '/');
    $port = $db_config['port'] ?? 5432;
    
    // Build DSN with all parsed values
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name";
} else {
    // Fallback: individual environment variables (MySQL compatibility)
    $host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?? '';
    $user = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?? '';
    $pass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS') ?? '';
    $db_name = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?? '';
    $port = $_ENV['MYSQL_PORT'] ?? $_SERVER['MYSQL_PORT'] ?? getenv('MYSQL_PORT') ?? 3306;
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name";
}

// Debug: Show if variables are missing
if (empty($host) || empty($user) || empty($db_name) || empty($dsn)) {
    die("<h2>Database Configuration Error</h2>
    <p>Missing database credentials:</p>
    <ul>
        <li>DATABASE_URL: " . (empty($database_url) ? '❌ NOT SET' : '✓ SET') . "</li>
        <li>DB_HOST: " . (empty($host) ? '❌ NOT SET' : '✓ ' . $host) . "</li>
        <li>DB_USER: " . (empty($user) ? '❌ NOT SET' : '✓ ' . $user) . "</li>
        <li>DB_PASS: " . (empty($pass) ? '❌ NOT SET' : '✓ SET') . "</li>
        <li>DB_NAME: " . (empty($db_name) ? '❌ NOT SET' : '✓ ' . $db_name) . "</li>
        <li>DB_PORT: " . (empty($port) ? '❌ NOT SET' : '✓ ' . $port) . "</li>
        <li>DSN: " . (empty($dsn) ? '❌ NOT SET' : '✓ ' . htmlspecialchars($dsn)) . "</li>
    </ul>
    <p><strong>For Render PostgreSQL:</strong> Go to Render Dashboard → PostgreSQL service → Connections → Copy the <strong>Internal Database URL</strong> to the <strong>DATABASE_URL</strong> environment variable in your Web Service.</p>");
}

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Auto-create table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($create_table_sql);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
