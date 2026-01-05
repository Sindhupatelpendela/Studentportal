# Admin's Guide to Deploying to Railway

This project is fully ready for deployment on **Railway** (or any other cloud PHP host).

## 1. Prerequisites
- A GitHub account.
- A [Railway.app](https://railway.app/) account (login with GitHub).
- The code pushed to your GitHub repository.

## 2. Deploying
1.  **Dashboard**: Go to your Railway Dashboard.
2.  **New Project**: Click **+ New Project** > **Deploy from GitHub repo**.
3.  **Select Repo**: Choose your `Student_Portal` repository.
4.  **Deploy Now**: Click **Deploy Now**.
    *   Railway will automatically detect that this is a PHP project and build it.
    *   *Note*: The first build might fail or the app might crash if the database isn't connected yet. This is normal.

## 3. Adding the Database
1.  In your Railway project view, click **+ New** (or "Create").
2.  Select **Database** > **MySQL**.
3.  Wait for the MySQL service to initialize.

## 4. Connecting the Database
You need to tell your PHP app where the database is.
1.  Click on the **MySQL** card in Railway.
2.  Go to the **Variables** tab.
3.  You will see variables like `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, etc.
4.  **Go back** to your **PHP App** card (the one running your code).
5.  Go to the **Variables** tab.
6.  Add the following variables (copying values from the MySQL service):

    | Variable Name | Value (Source from MySQL Service) |
    | :--- | :--- |
    | `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
    | `DB_USER` | `${{MySQL.MYSQLUSER}}` |
    | `DB_PASS` | `${{MySQL.MYSQLPASSWORD}}` |
    | `DB_NAME` | `${{MySQL.MYSQLDATABASE}}` |
    | `DB_PORT` | `${{MySQL.MYSQLPORT}}` |

    *Tip: Railway allows referencing variables. If you type `${{ MYSQL` it often auto-completes.*

## 5. Setup Complete
Once the variables are saved, Railway will **automatically redeploy** your app.
*   **Auto-Migration**: The application detects if the tables exist. If not, it creates them automatically (`users`, `student_profiles`) and creates the default Admin account.
*   **Default Admin**:
    *   **Username**: `admin`
    *   **Password**: `admin`
    *   *Please change this password immediately after logging in!*

## 6. Accessing Your App
- Click the **Public Networking** domain provided by Railway (e.g., `web-production-xxxx.up.railway.app`).
- Default Admin Login: `admin` / `admin`.

## Troubleshooting
- **502 Bad Gateway**: This usually means the app crashed. Check the **Deploy Logs**.
- **Database Error**: Check if your Variables are set correctly.
- **Connection Failed**: Ensure `DB_HOST` is correct (it is usually NOT `localhost` on Railway).

---
**Technical Note**:
The application uses `includes/config.php` as the single source of truth for database connections. It uses `getenv()` to look for the variables above. If running locally, it falls back to `localhost`.
