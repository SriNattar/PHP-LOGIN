# PHP Login System with Docker

A production-ready Signup and Login system built with PHP 8, MySQL, and Docker.

## Project Structure
```
/
├── docker-compose.yml
├── Dockerfile
├── README.md
├── db/
│   └── users.sql
└── src/
    ├── css/
    │   └── style.css
    ├── db.php
    ├── index.php
    ├── signup.php
    ├── dashboard.php
    └── logout.php
```

## How to Run Locally

### Prerequisites
- Docker and Docker Compose installed.

### Steps
1. **Clone/Download** the project.
2. Open a terminal in the project root.
3. Run the following command:
   ```bash
   docker-compose up -d --build
   ```
4. Access the application at: `http://localhost:8080`

### Database
The `users` table is automatically created from `db/users.sql` when the MySQL container starts for the first time.

## Deployment Guide

### Option 1: Railway
1. **Push to GitHub**: specificy this repository.
2. **Create New Project** on Railway from GitHub.
3. **Add Database**: Add a MySQL service in Railway.
4. **Variables**: Link the MySQL service variables to the PHP service:
   - `DB_HOST`: `${{MySQL.HOST}}`
   - `DB_USER`: `${{MySQL.USER}}`
   - `DB_PASS`: `${{MySQL.PASSWORD}}`
   - `DB_NAME`: `${{MySQL.DATABASE}}`
   - `MYSQL_PORT`: `${{MySQL.PORT}}`
5. **Deploy**.

### Option 2: Render
1. Create a **Web Service** for the PHP app (Docker environment).
2. Create a **Private Service** for MySQL (or use an external DB provider).
3. Set Environment Variables in the Web Service settings matching the DB credentials.

### Security Notes
- Passwords are hashed using `password_hash()`.
- Uses `mysqli` prepared statements to prevent SQL injection.
- Session-based authentication.
