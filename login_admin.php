<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - NRSC</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body style="background: #0f172a;"> <!-- Darker background for Admin -->
    <div class="login-wrapper">
        <div class="login-box card" style="border-top: 4px solid var(--danger);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="color: var(--danger); font-weight: 700; letter-spacing: 1px; margin-bottom: 0.5rem;">RESTRICTED ACCESS</div>
                <h1 style="color: var(--text-main); font-size: 1.5rem; font-weight: 700;">Admin Console</h1>
                <p style="color: var(--text-muted);">National Remote Sensing Centre</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="auth_login.php">
                <input type="hidden" name="role" value="admin">
                
                <div class="form-group">
                    <label class="form-label">Admin Username</label>
                    <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Secure Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    Authenticate
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <a href="login.php" style="color: var(--text-muted); font-size: 0.875rem; text-decoration: none;">&larr; Back to Student Portal</a>
            </div>
        </div>
    </div>
</body>
</html>
