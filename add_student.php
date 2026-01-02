<?php
require_once "includes/config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO student (college_id, Name, College, Branch, year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['college_id'], $_POST['name'], $_POST['college'], $_POST['branch'], $_POST['year']);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Registration - SIS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                SIS Enterprise
            </div>
            <nav style="margin-top: 2rem;">
                <a href="dashboard.php" class="nav-item">Dashboard</a>
                <a href="add_student.php" class="nav-item active">Registration</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1 class="page-title">New Student Registration</h1>
                    <p style="color: var(--text-muted); font-size: 0.875rem;">Create a new student record in the system.</p>
                </div>
                <a href="dashboard.php" class="btn" style="background: white; border: 1px solid var(--border-color);">Back to List</a>
            </header>

            <div class="card" style="max-width: 800px;">
                <?php if($message): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">College ID <span style="color: var(--danger)">*</span></label>
                            <input name="college_id" class="form-control" placeholder="e.g. STU-2024-001" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color: var(--danger)">*</span></label>
                            <input name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">College Name</label>
                        <input name="college" class="form-control" placeholder="Institute of Technology" value="XYZ Institute of Technology">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Branch/Major</label>
                            <select name="branch" class="form-control" required>
                                <option value="">Select Branch...</option>
                                <option>Computer Science</option>
                                <option>Electronics</option>
                                <option>Mechanical</option>
                                <option>Civil</option>
                                <option>Information Technology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Academic Year</label>
                            <select name="year" class="form-control" required>
                                <option value="">Select Year...</option>
                                <option>I</option>
                                <option>II</option>
                                <option>III</option>
                                <option>IV</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
                        <a href="dashboard.php" class="btn" style="background: white; border: 1px solid var(--border-color);">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Record</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
