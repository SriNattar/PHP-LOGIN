# Render Deployment Guide

This guide details how to deploy your PHP Login System to [Render.com](https://render.com/).

## Prerequisites
1. A [GitHub](https://github.com/) account with this repo pushed (✓ already done).
2. A [Render](https://render.com/) account (free tier available).

## Step 1: Create Render Account & Connect GitHub
1. Go to [render.com](https://render.com/) and sign up.
2. Click **"Dashboard"** and go to **"Account Settings"**.
3. Under **"Connected Services"**, click **"Connect GitHub"**.
4. Authorize Render to access your GitHub repositories.

## Step 2: Create a Web Service
1. From your Dashboard, click **"New +"** and select **"Web Service"**.
2. Select your `PHP-LOGIN` repository.
3. Fill in the details:

   | Field | Value |
   |-------|-------|
   | **Name** | `php-login` |
   | **Environment** | `Docker` |
   | **Region** | `Oregon` (or closest to you) |
   | **Branch** | `main` |

4. Keep other settings as default and scroll to **"Create Web Service"**.

*Note: The Dockerfile in your repo will be automatically used.*

## Step 3: Add a MySQL Database
1. From your Dashboard, click **"New +"** and select **"MySQL"**.
2. Fill in the details:

   | Field | Value |
   |-------|-------|
   | **Name** | `php-login-db` |
   | **Database** | `php_login` |
   | **Region** | `Oregon` (same as web service) |
   | **Plan** | `Free` |

3. Click **"Create Database"**.
4. Once created, go to the database's **"Connections"** tab and note the internal connection string (starts with `mysql://`).

## Step 4: Configure Environment Variables
1. Go back to your **Web Service** (php-login).
2. Click the **"Environment"** tab on the left sidebar.
3. Add the following environment variables:

   ```
   DB_HOST=php-login-db
   DB_USER=your_db_user
   DB_PASS=your_db_password
   DB_NAME=php_login
   MYSQL_PORT=3306
   ```

   **To get correct values:**
   - Click on your MySQL database service.
   - Go to **Connections** tab.
   - You'll see the connection string: `mysql://DB_USER:DB_PASS@db_host:port/php_login`
   - Extract the values from this string.

4. Click **"Save"** - the web service will redeploy automatically.

## Step 5: Import the Database Schema
Render doesn't auto-run SQL files, so you need to manually import `db/users.sql`:

### Option A: Using Render Dashboard
1. Go to your MySQL database service.
2. Click the **"Data"** or **"Browser"** tab (if available).
3. Paste the contents of `db/users.sql` and execute.

### Option B: Using External SQL Client
1. From the MySQL service's **Connections** tab, copy the connection string.
2. Use a tool like [TablePlus](https://tableplus.com/), [DBeaver](https://dbeaver.io/), or MySQL CLI:
   ```bash
   mysql -h host -u user -p password database_name < db/users.sql
   ```
3. Paste your connection details from Render.

### Option C: Modify PHP to Auto-Create Table (Recommended)
Edit `src/db.php` to create the table if it doesn't exist:

```php
<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'php_login';
$port = getenv('MYSQL_PORT') ?: 3306;

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Auto-create table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($create_table_sql)) {
    die("Error creating table: " . $conn->error);
}
?>
```

Then run `db/users.sql` manually for any seed data, or leave it as-is if the table creation is enough.

## Step 6: Verify Deployment
1. Go to your Web Service dashboard.
2. Under **Deploys**, wait for the status to show **"Live"** (green).
3. Click the service URL (e.g., `https://php-login.onrender.com`).
4. You should see your PHP login page.
5. Test signup/login functionality.

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **Service stuck in "Deploying"** | Check **Logs** tab for errors; common issue is Docker build failure. |
| **"Connection refused" to database** | Verify ENV variables match the MySQL service credentials exactly. |
| **Blank page or 500 error** | Check **Logs** tab; likely PHP or database connection error. |
| **Database not found** | Ensure table exists (import SQL) and `DB_NAME` matches the database name in Render. |

## Step 7: Enable Auto-Deploy on Push
By default, Render auto-deploys when you push to `main` on GitHub. To verify or change:

1. In your Web Service, go to **Settings**.
2. Under **Deploy Hook**, you can see the webhook (already configured).
3. Any push to `main` will trigger a new deployment.

## Done!
Your PHP Login app is now live on Render. Push new changes to GitHub and they'll auto-deploy.

### Optional: Custom Domain
1. Go to your Web Service **Settings**.
2. Under **Custom Domain**, add your domain (e.g., `myapp.com`).
3. Point your domain's DNS to Render (instructions provided).
