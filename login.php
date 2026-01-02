<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'student') {
    header("Location: student_portal.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login - NRSC</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body>
    <div class="auth-layout">
        <!-- Left Side: Branding -->
        <div class="auth-sidebar">
            <div class="auth-message">
                <h1 style="font-size: 3rem; margin-bottom: 1rem;">NRSC Portal</h1>
                <p style="font-size: 1.25rem; opacity: 0.9;">Secure access for students and faculty. Manage your academic profile with ease and reliable uptime.</p>
                <div style="margin-top: 2rem; font-size: 0.9rem; opacity: 0.7;">&copy; 2024 National Remote Sensing Centre</div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-content">
            <div class="auth-box">
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-main);">Welcome back</h2>
                    <p style="color: var(--text-muted);">Please enter your details to sign in.</p>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        <?= htmlspecialchars($_GET['success']) ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="auth_login.php">
                    <input type="hidden" name="role" value="student">
                    
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your student ID" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">Log in</button>
                </form>

                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted); margin-bottom: 1rem;">Don't have an account?</p>
                    <a href="register.php" class="btn btn-outline" style="width: 100%;">Create Student Account</a>
                </div>

                <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1rem; text-align: center;">
                    <a href="login_admin.php" style="font-size: 12px; color: var(--text-muted);">Administrator Access</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
