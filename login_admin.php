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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login - NRSC Enterprise Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔐</text></svg>">
</head>
<body>
    <div class="auth-layout">
        <!-- Left Side: Admin Branding -->
        <div class="auth-sidebar" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #0f172a 100%);">
            <div class="auth-message">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 2rem;">
                    <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 14px; opacity: 0.7; font-weight: 600; letter-spacing: 2px;">SECURE ACCESS</div>
                    </div>
                </div>
                
                <h1 style="font-size: 3rem; line-height: 1.1; margin-bottom: 1.5rem;">Admin<br>Console</h1>
                <p style="font-size: 1.15rem; line-height: 1.8; opacity: 0.8; max-width: 380px;">
                    Manage student records, view analytics, and configure portal settings through the administrative dashboard.
                </p>
                
                <!-- Security Pills -->
                <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 3rem;">
                    <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        2FA Protected
                    </div>
                    <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Audit Logged
                    </div>
                    <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); padding: 10px 18px; border-radius: 25px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Encrypted
                    </div>
                </div>
                
                <div style="margin-top: 4rem; font-size: 0.85rem; opacity: 0.5;">
                    &copy; 2026 NRSC, ISRO. Authorized Personnel Only.
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-content">
            <div class="auth-box">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--text-primary);">Administrator Login</h2>
                    <p style="color: var(--text-muted); font-size: 15px;">Enter your credentials to access the console</p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="auth_login.php">
                    <input type="hidden" name="role" value="admin">
                    
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="admin" required autocomplete="username">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%); box-shadow: 0 4px 14px rgba(139, 92, 246, 0.4);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Access Console
                    </button>
                </form>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); text-align: center;">
                    <a href="login.php" style="font-size: 14px; color: var(--text-muted); display: inline-flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Student Portal
                    </a>
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
