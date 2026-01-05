<?php
require_once "includes/config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

$total_students = $conn->query("SELECT COUNT(*) FROM student_profiles")->fetch_row()[0];
$branches = $conn->query("SELECT COUNT(DISTINCT branch) FROM student_profiles WHERE branch IS NOT NULL AND branch != ''")->fetch_row()[0];
$recent_signups = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_row()[0];

// Prepare Chart Data (Branch Distribution)
$chart_labels = [];
$chart_data = [];
$chart_colors = ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
$c_res = $conn->query("SELECT branch, COUNT(*) as c FROM student_profiles WHERE branch IS NOT NULL AND branch != '' GROUP BY branch ORDER BY c DESC LIMIT 7");
while($row = $c_res->fetch_assoc()) {
    $chart_labels[] = $row['branch'];
    $chart_data[] = $row['c'];
}

// Recent Activity
$activity = $conn->query("SELECT u.username, sp.full_name, u.created_at FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id ORDER BY u.created_at DESC LIMIT 5");

// Search
$search = "";
$query = "SELECT sp.*, u.id as user_id, u.email, u.username, u.created_at as registered_at FROM student_profiles sp JOIN users u ON sp.user_id = u.id";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);
    $query .= " WHERE sp.full_name LIKE '%$search%' OR sp.college_id LIKE '%$search%' OR u.email LIKE '%$search%'";
}
$query .= " ORDER BY u.created_at DESC LIMIT 50";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console - NRSC Enterprise Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .stat-icon svg { width: 28px; height: 28px; }
        
        .chart-card {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-card);
        }
        
        .quick-action {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: var(--bg-surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none !important;
        }
        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }
        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Premium Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <span>NRSC Admin</span>
            </div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="add_student.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    <span>Add Student</span>
                </a>
                <a href="#students-section" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>All Students</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>Analytics</span>
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <div class="page-subtitle">Welcome back, Administrator. Here's an overview of your portal.</div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="btn btn-outline" onclick="location.reload()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                        Refresh
                    </button>
                    <a href="add_student.php" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Student
                    </a>
                </div>
            </header>

            <!-- Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">
                <!-- Total Students -->
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0.1) 100%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="stat-value"><?= $total_students ?></div>
                    <div class="stat-label">Total Students</div>
                    <div class="stat-trend up">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline></svg>
                        Active
                    </div>
                </div>

                <!-- Departments -->
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(6, 182, 212, 0.1) 100%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <div class="stat-value"><?= $branches ?></div>
                    <div class="stat-label">Departments</div>
                    <div class="stat-trend up">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline></svg>
                        Growing
                    </div>
                </div>

                <!-- Recent Signups -->
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0.1) 100%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    </div>
                    <div class="stat-value"><?= $recent_signups ?></div>
                    <div class="stat-label">New This Week</div>
                    <div class="stat-trend up">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline></svg>
                        +<?= $recent_signups ?>
                    </div>
                </div>

                <!-- System Status -->
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(139, 92, 246, 0.1) 100%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="stat-value" style="font-size: 28px;">Online</div>
                    <div class="stat-label">System Status</div>
                    <div class="stat-trend up">
                        <span class="badge-dot"></span>
                        All Systems Go
                    </div>
                </div>
            </div>

            <!-- Charts & Activity Row -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 32px;">
                <!-- Branch Distribution Chart -->
                <div class="chart-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <div>
                            <h3 style="font-size: 18px; margin-bottom: 4px;">Department Distribution</h3>
                            <p style="color: var(--text-muted); font-size: 13px;">Students by branch</p>
                        </div>
                        <div class="badge badge-primary">Live Data</div>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="branchChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="chart-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h3 style="font-size: 18px; margin-bottom: 4px;">Recent Activity</h3>
                            <p style="color: var(--text-muted); font-size: 13px;">Latest registrations</p>
                        </div>
                    </div>
                    <div>
                        <?php while($act = $activity->fetch_assoc()): ?>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(6, 182, 212, 0.2));">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?= htmlspecialchars($act['full_name'] ?? $act['username']) ?></div>
                                <div class="activity-time"><?= date('M j, Y g:i A', strtotime($act['created_at'])) ?></div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
                <a href="add_student.php" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary);">Add New Student</div>
                        <div style="font-size: 13px; color: var(--text-muted);">Register a new student manually</div>
                    </div>
                </a>
                <a href="#" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary);">Export Data</div>
                        <div style="font-size: 13px; color: var(--text-muted);">Download student records</div>
                    </div>
                </a>
                <a href="#" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary);">Generate Report</div>
                        <div style="font-size: 13px; color: var(--text-muted);">Create analytics report</div>
                    </div>
                </a>
            </div>

            <!-- Student Directory -->
            <div class="card" id="students-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h3 style="font-size: 20px; margin-bottom: 4px;">Student Directory</h3>
                        <p style="color: var(--text-muted); font-size: 14px;"><?= $result->num_rows ?> students found</p>
                    </div>
                    <form style="display: flex; gap: 12px;">
                        <div style="position: relative;">
                            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input name="q" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search by name, ID, or email..." style="width: 320px; padding-left: 44px;">
                        </div>
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>College ID</th>
                                <th>Department</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-muted);">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.3; margin-bottom: 16px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <div style="font-size: 16px; font-weight: 600;">No students found</div>
                                    <div style="font-size: 14px;">Try adjusting your search criteria</div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 14px;">
                                        <div class="avatar" style="width: 44px; height: 44px; font-size: 16px;">
                                            <?= strtoupper(substr($row['full_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($row['full_name']) ?></div>
                                            <div style="font-size: 12px; color: var(--text-muted);">Year: <?= !empty($row['year']) ? htmlspecialchars($row['year']) : 'Not set' ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code style="background: var(--bg-hover); padding: 6px 10px; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600;">
                                        <?= htmlspecialchars($row['college_id']) ?>
                                    </code>
                                </td>
                                <td>
                                    <?php if(!empty($row['branch'])): ?>
                                        <span class="badge badge-primary"><?= htmlspecialchars($row['branch']) ?></span>
                                    <?php else: ?>
                                        <span class="badge" style="background: var(--bg-hover); color: var(--text-muted);">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 13px; color: var(--text-secondary);"><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <span class="badge badge-success">
                                        <span class="badge-dot"></span>
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <a href="admin_view_student.php?id=<?= $row['user_id'] ?>" class="btn btn-ghost" style="padding: 8px 14px; font-size: 13px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">🌙</button>

    <script>
        // Theme Toggle
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

        // Premium Chart with Gradients
        const ctx = document.getElementById('branchChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
        gradient.addColorStop(1, 'rgba(6, 182, 212, 0.4)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Students',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: gradient,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 14, weight: '600' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: { font: { size: 12 }, color: '#64748b' }
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    } 
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
        
        // Animate stat values on load
        document.querySelectorAll('.stat-value').forEach(el => {
            const target = parseInt(el.textContent);
            if(!isNaN(target)) {
                let current = 0;
                const increment = target / 30;
                const timer = setInterval(() => {
                    current += increment;
                    if(current >= target) {
                        el.textContent = target;
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current);
                    }
                }, 30);
            }
        });
    </script>
</body>
</html>
