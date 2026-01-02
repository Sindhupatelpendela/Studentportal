<?php
require_once "includes/config.php";

// Access Control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$student_id = intval($_GET['id']);
$msg = "";
$error = "";

// Handle Profile Updates by Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $college_id = $_POST['college_id'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];

    $stmt = $conn->prepare("UPDATE student_profiles SET full_name=?, college_id=?, branch=?, year=?, phone=?, address=?, bio=? WHERE user_id=?");
    $stmt->bind_param("sssssssi", $name, $college_id, $branch, $year, $phone, $address, $bio, $student_id);
    
    if ($stmt->execute()) {
        $msg = "Student profile updated successfully.";
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

// Fetch Student Data
$stmt = $conn->prepare("SELECT u.email, u.username, p.* FROM users u JOIN student_profiles p ON u.id = p.user_id WHERE u.id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details - Admin Console</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">NRSC Admin</div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Overview</a>
                <a href="dashboard.php" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg> Student Details</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <div>
                    <a href="dashboard.php" style="font-size: 12px; display: inline-flex; align-items: center; gap: 4px; color: var(--text-muted); margin-bottom: 8px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg> Back to Dashboard
                    </a>
                    <h1 class="page-title"><?= htmlspecialchars($student['full_name']) ?></h1>
                    <div class="page-subtitle"><?= htmlspecialchars($student['college_id']) ?> &bull; <?= htmlspecialchars($student['email']) ?></div>
                </div>
                <div>
                    <span class="btn" style="background: var(--success-bg); color: var(--success-text); pointer-events: none;">
                        <span style="width: 8px; height: 8px; background: currentColor; border-radius: 50%;"></span>
                        Active Student
                    </span>
                </div>
            </header>

            <?php if($msg): ?> <div class="alert alert-success"><?= $msg ?></div> <?php endif; ?>
            <?php if($error): ?> <div class="alert alert-error"><?= $error ?></div> <?php endif; ?>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
                
                <!-- Edit Form -->
                <div class="card">
                    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">Academic & Personal Record</h3>
                    <form method="post">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Full Legal Name</label>
                                <input name="full_name" class="form-control" value="<?= htmlspecialchars($student['full_name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">College ID (Unique)</label>
                                <input name="college_id" class="form-control" value="<?= htmlspecialchars($student['college_id']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Branch / Department</label>
                                <input name="branch" class="form-control" value="<?= htmlspecialchars($student['branch'] ?? '') ?>" placeholder="Not Assigned">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Year / Semester</label>
                                <input name="year" class="form-control" value="<?= htmlspecialchars($student['year'] ?? '') ?>" placeholder="Not Assigned">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contact Phone</label>
                            <input name="phone" class="form-control" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" placeholder="+91...">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Residential Address</label>
                            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Bio / Remarks</label>
                            <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($student['bio'] ?? '') ?></textarea>
                        </div>

                        <div style="text-align: right;">
                             <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

                <!-- Info Sidebar -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Quick Stats -->
                    <div class="card">
                        <h4 style="margin-bottom: 16px; font-size: 14px; text-transform: uppercase; color: var(--text-muted);">Account Info</h4>
                        <div style="margin-bottom: 12px;">
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700;">USERNAME</div>
                            <div style="font-family: monospace; font-size: 14px;"><?= htmlspecialchars($student['username']) ?></div>
                        </div>
                         <div style="margin-bottom: 12px;">
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700;">JOINED ON</div>
                            <div><?= date('d M Y, h:i A', strtotime($student['created_at'] ?? 'now')) ?></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card" style="border-left: 4px solid var(--danger-text);">
                        <h4 style="margin-bottom: 16px; font-size: 14px; color: var(--danger-text);">Danger Zone</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">
                            Removing a student record is irreversible. Their login access will be revoked immediately.
                        </p>
                        <form action="delete_student.php" method="GET" onsubmit="return confirm('CRITICAL WARNING: Are you sure you want to delete this student data?');">
                            <input type="hidden" name="id" value="<?= $student['college_id'] ?>"> <!-- Assuming legacy delete expects ID string -->
                            <button class="btn btn-outline" style="width: 100%; color: var(--danger-text); border-color: var(--danger-text);">Delete Student Record</button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
