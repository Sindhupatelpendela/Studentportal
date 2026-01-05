<?php
require_once "includes/config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $college_id = trim($_POST['college_id']);
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    
    $username = strtolower(str_replace([' ', '-'], '', $college_id));
    $raw_password = "password123"; 
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $email = $username . "@student.nrsc.gov.in";

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->bind_param("sss", $username, $email, $password);
        if (!$stmt->execute()) {
            throw new Exception("Username or Email already exists.");
        }
        $user_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO student_profiles (user_id, full_name, college_id, branch, year) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $college_id, $branch, $year);
        $stmt->execute();

        $conn->commit();
        $message = "Student created successfully! Login credentials: <strong>$username</strong> / <strong>$raw_password</strong>";
        $message_type = "success";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - NRSC Admin Console</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
</head>
<body>
    <div class="app-container">
        <!-- Premium Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <span>NRSC Admin</span>
            </div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="add_student.php" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    <span>Add Student</span>
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
            <header class="page-header">
                <div>
                    <h1 class="page-title">Add New Student</h1>
                    <div class="page-subtitle">Create a new student account and profile in the system.</div>
                </div>
                <a href="dashboard.php" class="btn btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to Dashboard
                </a>
            </header>

            <?php if($message): ?>
                <div class="alert alert-<?= $message_type ?>">
                    <?php if($message_type == 'success'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <?php else: ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php endif; ?>
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="card" style="max-width: 800px;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 28px;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(6, 182, 212, 0.2)); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 20px; margin-bottom: 4px;">Student Information</h3>
                        <p style="color: var(--text-muted); font-size: 14px;">Fill in the details below to create a new student record</p>
                    </div>
                </div>

                <form method="post">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">College ID <span style="color: var(--danger);">*</span></label>
                            <input name="college_id" class="form-control" placeholder="e.g. STU-2026-001" required>
                            <small style="color: var(--text-muted); font-size: 12px; margin-top: 6px; display: block;">This will be used to generate the username</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color: var(--danger);">*</span></label>
                            <input name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Branch / Department <span style="color: var(--danger);">*</span></label>
                            <select name="branch" class="form-control" required>
                                <option value="">Select Branch...</option>
                                <option>Remote Sensing</option>
                                <option>GIS & Geoinformatics</option>
                                <option>Earth Observation</option>
                                <option>Space Applications</option>
                                <option>Computer Science</option>
                                <option>Electronics</option>
                                <option>Mechanical</option>
                                <option>Civil Engineering</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Academic Year <span style="color: var(--danger);">*</span></label>
                            <select name="year" class="form-control" required>
                                <option value="">Select Year...</option>
                                <option>I Year</option>
                                <option>II Year</option>
                                <option>III Year</option>
                                <option>IV Year</option>
                                <option>M.Tech I</option>
                                <option>M.Tech II</option>
                                <option>PhD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(6, 182, 212, 0.08)); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 12px; padding: 20px; margin-top: 8px;">
                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <div>
                                <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">Auto-generated Credentials</div>
                                <div style="color: var(--text-secondary); font-size: 14px; line-height: 1.6;">
                                    The system will automatically generate login credentials based on the College ID. 
                                    Default password is <code style="background: var(--bg-hover); padding: 2px 6px; border-radius: 4px; font-family: 'JetBrains Mono', monospace;">password123</code>. 
                                    The student should change it on first login.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="dashboard.php" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                            Create Student
                        </button>
                    </div>
                </form>
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
    </script>
</body>
</html>
