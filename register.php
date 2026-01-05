<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'dashboard.php' : 'student_portal.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - NRSC Enterprise Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
</head>
<body>
    <div class="auth-layout">
        <!-- Left Side: Branding -->
        <div class="auth-sidebar">
            <div class="auth-message">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 2rem;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="23" y1="11" x2="17" y2="11"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; opacity: 0.7; font-weight: 600; letter-spacing: 2px;">JOIN THE PORTAL</div>
                    </div>
                </div>
                
                <h1 style="font-size: 3rem; line-height: 1.1; margin-bottom: 1.5rem;">Create Your<br>Account</h1>
                <p style="font-size: 1.15rem; line-height: 1.8; opacity: 0.8; max-width: 380px;">
                    Join thousands of students already using the NRSC Portal to manage their academic journey.
                </p>
                
                <!-- Benefits -->
                <div style="margin-top: 3rem;">
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; margin-bottom: 4px;">Digital ID Card</div>
                            <div style="font-size: 14px; opacity: 0.7;">Get your verified digital identity instantly</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; margin-bottom: 4px;">Profile Management</div>
                            <div style="font-size: 14px; opacity: 0.7;">Update your academic information anytime</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; margin-bottom: 4px;">Secure Access</div>
                            <div style="font-size: 14px; opacity: 0.7;">Your data is protected with encryption</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 4rem; font-size: 0.85rem; opacity: 0.5;">
                    &copy; 2026 NRSC, ISRO. All rights reserved.
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="auth-content">
            <div class="auth-box">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--text-primary);">Get Started</h2>
                    <p style="color: var(--text-muted); font-size: 15px;">Create your student account in minutes</p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="auth_register.php">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">College ID</label>
                        <input type="text" name="college_id" class="form-control" placeholder="STU-2026-001" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="johndoe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="you@email.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a strong password" required minlength="6">
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                            <input type="checkbox" required style="width: 18px; height: 18px; accent-color: var(--primary); margin-top: 2px;">
                            I agree to the Terms of Service and Privacy Policy of NRSC Portal.
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        Create Account
                    </button>
                </form>

                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted); font-size: 14px;">
                        Already have an account? 
                        <a href="login.php" style="font-weight: 600;">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">🌙</button>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            document.querySelector('.theme-toggle').textContent = newTheme === 'dark' ? '☀️' : '🌙';
        }
        
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if(savedTheme === 'dark') document.querySelector('.theme-toggle').textContent = '☀️';
    </script>
</body>
</html>
