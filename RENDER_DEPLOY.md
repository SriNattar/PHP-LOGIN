# Render Deployment Guide (PostgreSQL)

This guide details how to deploy your PHP Login System to [Render.com](https://render.com/) using **PostgreSQL**.

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

4. Keep other settings as default and click **"Create Web Service"**.

*Note: The Dockerfile in your repo will be automatically used.*

## Step 3: Add PostgreSQL Database
1. From your Dashboard, click **"New +"** and select **"PostgreSQL"**.
2. Fill in the details:

   | Field | Value |
   |-------|-------|
   | **Name** | `php-login-db` |
   | **Database** | `php_login_db` |
   | **Region** | `Oregon` (same as web service) |
   | **Plan** | `Free` |

3. Click **"Create Database"**.
4. This will take a few minutes. Once created, go to the database's **"Connections"** tab.

## Step 4: Configure Environment Variables
1. Go back to your **Web Service** (php-login).
2. Click the **"Environment"** tab on the left sidebar.
3. Add the following environment variable:

   | Variable Name | Value |
   |--------------|-------|
   | `DATABASE_URL` | `postgresql://user:password@host:port/database` |

   **To get the DATABASE_URL:**
   - Click on your **PostgreSQL database** service.
   - Go to the **"Connections"** tab.
   - Copy the **"Internal Database URL"** (for best performance within Render).
   
   It looks like: `postgresql://php_login_db_user:password@dpg-xxxx.c99.us-east-1.postgres.render.com:5432/php_login_db`

4. Paste the full URL into the `DATABASE_URL` field in your Web Service environment.
5. Click **"Save"** - the web service will redeploy automatically.

## Step 5: Import the Database Schema
Render doesn't auto-run SQL files, so you need to manually import `db/users.sql`:

### Option A: Using Render Dashboard (Recommended)
1. Go to your PostgreSQL database service.
2. Click the **"Connect"** button.
3. Select **"psql"** (command line option).
4. Copy the connection command and run it in your terminal.
5. Once connected, paste the contents of `db/users.sql`:
   ```sql
   CREATE TABLE IF NOT EXISTS users (
       id SERIAL PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

### Option B: Using External PostgreSQL Client
1. Download [DBeaver](https://dbeaver.io/) (free) or [pgAdmin](https://www.pgadmin.org/).
2. Create a new connection using the connection details from Render.
3. Execute the SQL from `db/users.sql`.

### Option C: Auto-Create Table in PHP (Already Included)
Your `src/db.php` now automatically creates the table on first connection if it doesn't exist.

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
| **"Connection refused" to database** | Verify `DATABASE_URL` is set correctly in Environment tab. |
| **Blank page or 500 error** | Check **Logs** tab; likely PHP or database connection error. |
| **Table doesn't auto-create** | Manually run the SQL from `db/users.sql` in Render's PostgreSQL database. |
| **"Database configuration error" shown** | `DATABASE_URL` environment variable is missing or malformed. |

## Step 7: Enable Auto-Deploy on Push
By default, Render auto-deploys when you push to `main` on GitHub. To verify or change:

1. In your Web Service, go to **Settings**.
2. Under **Deploy Hook**, you can see the webhook (already configured).
3. Any push to `main` will trigger a new deployment.

## Done!
Your PHP Login app is now live on Render with PostgreSQL. Push new changes to GitHub and they'll auto-deploy.

### Optional: Custom Domain
1. Go to your Web Service **Settings**.
2. Under **Custom Domain**, add your domain (e.g., `myapp.com`).
3. Point your domain's DNS to Render (instructions provided).
