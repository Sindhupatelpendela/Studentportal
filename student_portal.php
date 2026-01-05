<?php
require_once "includes/config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";
if (isset($_POST['update_profile'])) {
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];
    $stmt = $conn->prepare("UPDATE student_profiles SET branch=?, year=?, phone=?, address=?, bio=? WHERE user_id=?");
    $stmt->bind_param("sssssi", $branch, $year, $phone, $address, $bio, $user_id);
    if($stmt->execute()) $msg = "Changes saved successfully.";
}
$profile = $conn->query("SELECT * FROM student_profiles WHERE user_id=$user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Portal - NRSC</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .id-card-container {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }
        .id-header {
            background: var(--primary);
            color: white;
            padding: 24px;
            text-align: center;
            position: relative;
        }
        .id-header:after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0; right: 0;
            height: 40px;
            background: white;
            border-radius: 50% 50% 0 0;
        }
        .id-avatar {
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--primary);
            font-weight: 700;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .id-body { padding: 40px 24px 24px; text-align: center; }
        .id-name { font-size: 20px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
        .id-role { color: var(--text-muted); font-size: 14px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 24px; }
        .id-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; text-align: left; }
        .id-field { background: var(--bg-app); padding: 12px; border-radius: var(--radius); }
        .id-label { font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; margin-bottom: 2px; }
        .id-value { font-weight: 600; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
        
        .qr-placeholder {
            margin-top: 24px;
            background: white;
            padding: 12px;
            border: 1px solid var(--border);
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">NRSC Student</div>
            <nav class="nav-menu">
                <a href="#" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Academic Records</a>
            </nav>
            <div style="padding: 24px; border-top: 1px solid var(--border);">
                <a href="logout.php" class="nav-item" style="color: var(--danger-text);">Sign Out</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1 class="page-title">Profile Management</h1>
                    <div class="page-subtitle">Manage your personal information and view your digital ID.</div>
                </div>
            </header>

            <?php if($msg): ?>
                <div class="alert alert-success"><?= $msg ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
                
                <!-- Profile Form -->
                <div class="card">
                    <h3 style="margin-bottom: 24px;">Personal Details</h3>
                    <form method="post">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Branch / Major</label>
                                <input name="branch" class="form-control" value="<?= htmlspecialchars($profile['branch'] ?? '') ?>" placeholder="e.g. Remote Sensing" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Current Year</label>
                                <input name="year" class="form-control" value="<?= htmlspecialchars($profile['year'] ?? '') ?>" placeholder="e.g. 2026-2027" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input name="phone" class="form-control" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" placeholder="+91 98765 43210">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Permanent Address</label>
                            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="Brief academic summary..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button name="update_profile" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

                <!-- Digital ID Card -->
                <div>
                    <h3 style="margin-bottom: 16px; font-size: 16px;">Digital Identity</h3>
                    <div class="id-card-container">
                        <div class="id-header">
                            <div style="font-weight: 700; letter-spacing: 1px; font-size: 12px; opacity: 0.8;">NRSC OFFICIAL</div>
                            <div style="font-size: 18px; margin-top: 4px;">Student Portal</div>
                        </div>
                        <div class="id-avatar">
                            <?= strtoupper(substr($profile['full_name'], 0, 1)) ?>
                        </div>
                        <div class="id-body">
                            <div class="id-name"><?= htmlspecialchars($profile['full_name']) ?></div>
                            <div class="id-role">Student</div>
                            
                            <div class="id-grid">
                                <div class="id-field">
                                    <div class="id-label">ID Number</div>
                                    <div class="id-value" style="color: var(--primary);"><?= htmlspecialchars($profile['college_id']) ?></div>
                                </div>
                                <div class="id-field">
                                    <div class="id-label">Valid Until</div>
                                    <div class="id-value">DEC 2025</div>
                                </div>
                                <div class="id-field" style="grid-column: span 2;">
                                    <div class="id-label">Department</div>
                                    <div class="id-value"><?= htmlspecialchars($profile['branch'] ?? 'Unassigned') ?></div>
                                </div>
                            </div>

                            <div class="qr-placeholder">
                                <!-- Simulating a QR Code with CSS Pattern -->
                                <div style="width: 100px; height: 100px; background-image: radial-gradient(black 30%, transparent 31%), radial-gradient(black 30%, transparent 31%); background-size: 10px 10px; background-position: 0 0, 5px 5px; opacity: 0.8;"></div>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; color: var(--text-muted);">
                                Scan to verify identity instantly.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
