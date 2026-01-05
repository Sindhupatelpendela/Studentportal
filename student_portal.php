<?php
require_once "includes/config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = "";

if (isset($_POST['update_profile'])) {
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];
    $stmt = $conn->prepare("UPDATE student_profiles SET branch=?, year=?, phone=?, address=?, bio=? WHERE user_id=?");
    $stmt->bind_param("sssssi", $branch, $year, $phone, $address, $bio, $user_id);
    if($stmt->execute()) {
        $msg = "Your profile has been updated successfully!";
        $msg_type = "success";
    }
}
$profile = $conn->query("SELECT * FROM student_profiles WHERE user_id=$user_id")->fetch_assoc();
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Calculate profile completion percentage
$fields = ['full_name', 'college_id', 'branch', 'year', 'phone', 'address', 'bio'];
$filled = 0;
foreach($fields as $f) {
    if(!empty($profile[$f])) $filled++;
}
$completion = round(($filled / count($fields)) * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portal - NRSC Enterprise</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <style>
        /* Premium ID Card Styles */
        .id-card-container {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .id-card-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 140px;
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
            z-index: 0;
        }
        
        .id-header {
            position: relative;
            z-index: 1;
            padding: 28px 24px 60px;
            text-align: center;
            color: white;
        }
        
        .id-org {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            opacity: 0.9;
            text-transform: uppercase;
        }
        
        .id-title {
            font-size: 20px;
            font-weight: 800;
            margin-top: 6px;
        }
        
        .id-avatar-wrap {
            position: relative;
            z-index: 2;
            margin-top: -50px;
            text-align: center;
        }
        
        .id-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            font-weight: 800;
            border: 5px solid white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }
        
        .id-body { 
            padding: 20px 24px 28px; 
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .id-name { 
            font-size: 22px; 
            font-weight: 800; 
            color: var(--text-primary); 
            margin-bottom: 4px; 
        }
        
        .id-role { 
            color: var(--text-muted); 
            font-size: 13px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            font-weight: 600; 
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-hover);
            padding: 6px 14px;
            border-radius: 20px;
        }
        
        .id-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 12px; 
            text-align: left; 
        }
        
        .id-field { 
            background: var(--bg-hover); 
            padding: 14px 16px; 
            border-radius: 12px;
            transition: var(--transition);
        }
        
        .id-field:hover {
            background: rgba(99, 102, 241, 0.08);
        }
        
        .id-label { 
            font-size: 10px; 
            text-transform: uppercase; 
            color: var(--text-muted); 
            font-weight: 700; 
            letter-spacing: 0.5px;
            margin-bottom: 4px; 
        }
        
        .id-value { 
            font-weight: 700; 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 13px;
            color: var(--text-primary);
        }
        
        .qr-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #0f172a 25%, transparent 25%), 
                        linear-gradient(-45deg, #0f172a 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #0f172a 75%), 
                        linear-gradient(-45deg, transparent 75%, #0f172a 75%);
            background-size: 8px 8px;
            background-position: 0 0, 0 4px, 4px -4px, -4px 0px;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .qr-info {
            text-align: left;
        }
        
        /* Profile Completion */
        .completion-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .completion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .completion-title {
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .completion-percent {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #06b6d4 100%);
            border-radius: var(--radius-xl);
            padding: 32px 40px;
            color: white;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .welcome-content {
            position: relative;
            z-index: 1;
        }
        
        .welcome-greeting {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        
        .welcome-name {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        
        .welcome-subtitle {
            opacity: 0.8;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Premium Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <span>NRSC Student</span>
            </div>
            <nav class="nav-menu">
                <a href="student_portal.php" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>My Profile</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Schedule</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span>Documents</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span>Notifications</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>Settings</span>
                </a>
            </nav>
            <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="logout.php" class="nav-item" style="color: #f87171;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <div class="welcome-greeting">👋 Welcome back,</div>
                    <div class="welcome-name"><?= htmlspecialchars($profile['full_name']) ?></div>
                    <div class="welcome-subtitle">Manage your academic profile and view your digital identity card.</div>
                </div>
            </div>

            <?php if($msg): ?>
                <div class="alert alert-<?= $msg_type ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 32px;">
                
                <!-- Profile Form Section -->
                <div>
                    <!-- Profile Completion Card -->
                    <?php if($completion < 100): ?>
                    <div class="completion-card">
                        <div class="completion-header">
                            <div class="completion-title">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Profile Completion
                            </div>
                            <div class="completion-percent"><?= $completion ?>%</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $completion ?>%"></div>
                        </div>
                        <p style="margin-top: 12px; font-size: 13px; color: var(--text-muted);">
                            Complete your profile to unlock all features and get your verified digital ID.
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Profile Form -->
                    <div class="card">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 28px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(6, 182, 212, 0.2)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; margin-bottom: 2px;">Personal Details</h3>
                                <p style="color: var(--text-muted); font-size: 14px;">Update your academic information</p>
                            </div>
                        </div>
                        
                        <form method="post">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Branch / Department</label>
                                    <input name="branch" class="form-control" value="<?= htmlspecialchars($profile['branch'] ?? '') ?>" placeholder="e.g. Remote Sensing" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Academic Year</label>
                                    <input name="year" class="form-control" value="<?= htmlspecialchars($profile['year'] ?? '') ?>" placeholder="e.g. 2026-2027" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contact Number</label>
                                <input name="phone" class="form-control" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" placeholder="+91 98765 43210">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Your full address..."><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Bio / Academic Summary</label>
                                <textarea name="bio" class="form-control" rows="2" placeholder="Brief description about your academic interests..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 8px;">
                                <button type="reset" class="btn btn-outline">Reset</button>
                                <button name="update_profile" class="btn btn-primary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Digital ID Card Section -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                        <h3 style="font-size: 18px;">Digital Identity Card</h3>
                        <span class="badge badge-success">
                            <span class="badge-dot"></span>
                            Verified
                        </span>
                    </div>
                    
                    <div class="id-card-container">
                        <div class="id-header">
                            <div class="id-org">National Remote Sensing Centre</div>
                            <div class="id-title">Student Portal</div>
                        </div>
                        
                        <div class="id-avatar-wrap">
                            <div class="id-avatar">
                                <?= strtoupper(substr($profile['full_name'], 0, 1)) ?>
                            </div>
                        </div>
                        
                        <div class="id-body">
                            <div class="id-name"><?= htmlspecialchars($profile['full_name']) ?></div>
                            <div class="id-role">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                Student
                            </div>
                            
                            <div class="id-grid">
                                <div class="id-field">
                                    <div class="id-label">Student ID</div>
                                    <div class="id-value" style="color: #6366f1;"><?= htmlspecialchars($profile['college_id']) ?></div>
                                </div>
                                <div class="id-field">
                                    <div class="id-label">Valid Until</div>
                                    <div class="id-value">DEC 2027</div>
                                </div>
                                <div class="id-field" style="grid-column: span 2;">
                                    <div class="id-label">Department</div>
                                    <div class="id-value"><?= htmlspecialchars($profile['branch'] ?? 'Pending Assignment') ?></div>
                                </div>
                            </div>

                            <div class="qr-section">
                                <div class="qr-code"></div>
                                <div class="qr-info">
                                    <div style="font-weight: 700; font-size: 13px; color: var(--text-primary); margin-bottom: 4px;">
                                        Scan to Verify
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        Quick identity verification using any QR scanner app.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="card" style="margin-top: 24px;">
                        <h4 style="font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Account Information
                        </h4>
                        <div style="display: grid; gap: 16px;">
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
                                <span style="color: var(--text-muted); font-size: 14px;">Username</span>
                                <span style="font-weight: 600; font-family: 'JetBrains Mono', monospace;"><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
                                <span style="color: var(--text-muted); font-size: 14px;">Email</span>
                                <span style="font-weight: 600;"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                                <span style="color: var(--text-muted); font-size: 14px;">Member Since</span>
                                <span style="font-weight: 600;"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
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

        // Add smooth input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.2s';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
