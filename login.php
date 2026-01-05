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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - NRSC Enterprise Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
</head>
<body>
    <div class="auth-layout">
        <!-- Left Side: Premium Branding -->
        <div class="auth-sidebar">
            <div class="auth-message">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 2rem;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; opacity: 0.7; font-weight: 600; letter-spacing: 2px;">NATIONAL REMOTE SENSING CENTRE</div>
                    </div>
                </div>
                
                <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem;">Student<br>Portal</h1>
                <p style="font-size: 1.15rem; line-height: 1.8; opacity: 0.8; max-width: 380px;">
                    Access your academic profile, digital ID card, and institutional resources through our secure, enterprise-grade platform.
                </p>
                
                <!-- Feature Pills -->
                <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 3rem;">
                    <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Secure Login
                    </div>
                    <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        24/7 Access
                    </div>
                    <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Real-time Sync
                    </div>
                </div>
                
                <div style="margin-top: 4rem; font-size: 0.85rem; opacity: 0.5;">
                    &copy; 2026 NRSC, ISRO. All rights reserved.
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-content">
            <div class="auth-box">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--text-primary);">Welcome back</h2>
                    <p style="color: var(--text-muted); font-size: 15px;">Sign in to access your student portal</p>
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
                        <label class="form-label">Username / Student ID</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your student ID" required autocomplete="username">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: var(--text-secondary);">
                            <input type="checkbox" style="width: 18px; height: 18px; accent-color: var(--primary);">
                            Remember me
                        </label>
                        <a href="#" style="font-size: 14px;">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Sign In
                    </button>
                </form>

                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted); margin-bottom: 1rem; font-size: 14px;">Don't have an account?</p>
                    <a href="register.php" class="btn btn-outline" style="width: 100%;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        Create Student Account
                    </a>
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <a href="login_admin.php" style="font-size: 13px; color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Administrator Access
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">
        🌙
    </button>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            document.querySelector('.theme-toggle').textContent = newTheme === 'dark' ? '☀️' : '🌙';
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if(savedTheme === 'dark') document.querySelector('.theme-toggle').textContent = '☀️';

        // Add input focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
