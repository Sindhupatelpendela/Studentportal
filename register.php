<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration - NRSC Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="login-wrapper">
        <div class="login-box card" style="max-width: 500px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="color: var(--primary); font-size: 1.5rem; font-weight: 700;">NRSC Student Portal</h1>
                <p style="color: var(--text-muted);">Create your student account</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="auth_register.php">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                     <div class="form-group">
                        <label class="form-label">College ID</label>
                        <input type="text" name="college_id" class="form-control" placeholder="STD-001" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="johndoe" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create a strong password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Create Account
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
