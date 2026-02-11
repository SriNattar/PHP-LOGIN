# Railway Deployment Guide

This guide details how to deploy your PHP Login System to [Railway.app](https://railway.app/).

## Prerequisites
1. A [GitHub](https://github.com/) account.
2. A [Railway](https://railway.app/) account (free tier available).
3. [Git](https://git-scm.com/) installed locally.

## Step 1: Push Code to GitHub
If you haven't already, push this project to a new GitHub repository.

```bash
# Initialize git
git init

# Add files
git add .

# Commit
git commit -m "Initial commit"

# Add remote (replace YOUR_REPO_URL)
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git

# Push
git push -u origin main
```

## Step 2: Create Project on Railway
1. Log in to your Railway dashboard.
2. Click **"New Project"**.
3. Select **"Deploy from GitHub repo"**.
4. Select your `php-login-system` repository.
5. Click **"Deploy Now"**.

*Note: The deploy might fail initially because the database isn't connected yet. This is normal.*

## Step 3: Add MySQL Database
1. In your project view (Canvas), right-click or click the **"New"** button.
2. Select **"Database"** -> **"MySQL"**.
3. Railway will spin up a MySQL container.

## Step 4: Configure Environment Variables
1. Click on your **PHP Service** (the GitHub repo card).
2. Go to the **"Variables"** tab.
3. You need to add the following variables. Railway allows you to reference the MySQL service variables directly so you don't have to copy-paste hardcoded values.

   | Variable Name | Value | Description |
   |--------------|-------|-------------|
   | `DB_HOST`    | `${{MySQL.HOST}}` | links to MySQL host |
   | `DB_USER`    | `${{MySQL.USER}}` | links to MySQL user |
   | `DB_PASS`    | `${{MySQL.PASSWORD}}` | links to MySQL password |
   | `DB_NAME`    | `${{MySQL.DATABASE}}` | links to MySQL database name |
   | `MYSQL_PORT` | `${{MySQL.PORT}}` | links to MySQL port |

   *Note: Type `${{` and Railway will autocomplete the service variables for you.*

4. Railway will automatically restart your PHP service when variables are saved.

## Step 5: Verify Deployment
1. One the deployment resolves to "Success", click the generated URL (e.g., `https://php-login-production.up.railway.app`).
2. **First Run**: The `db/users.sql` file might not run automatically on Railway's MySQL service unless you manually import it, because Railway's MySQL image is standard. 
   
   **How to Import the SQL:**
   - Click on the **MySQL** service in Railway.
   - Go to the **"Data"** tab.
   - You can copy and paste the content of `db/users.sql` into the query runner, or connect via an external tool (like TablePlus) using the "Connect" credentials.
   
   *Alternative:* You can modify `src/db.php` to create the table if it doesn't exist (e.g., add the `CREATE TABLE` query inside the PHP connection logic), but manual import is safer for production.

## Step 6: Done!
Your app should now be live.
