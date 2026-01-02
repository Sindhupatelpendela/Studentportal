<?php
require_once "includes/config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

$total_students = $conn->query("SELECT COUNT(*) FROM student_profiles")->fetch_row()[0];
$branches = $conn->query("SELECT COUNT(DISTINCT branch) FROM student_profiles")->fetch_row()[0];

// Prepare Chart Data (Branch Distribution)
$chart_labels = [];
$chart_data = [];
$c_res = $conn->query("SELECT branch, COUNT(*) as c FROM student_profiles GROUP BY branch");
while($row = $c_res->fetch_assoc()) {
    $chart_labels[] = (!empty($row['branch'])) ? $row['branch'] : 'Unassigned';
    $chart_data[] = $row['c'];
}

// Search
$search = "";
$query = "SELECT * FROM student_profiles p JOIN users u ON p.user_id = u.id";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);
    $query .= " WHERE p.full_name LIKE '%$search%' OR p.college_id LIKE '%$search%'";
}
$query .= " LIMIT 50";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Console</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                NRSC Admin
            </div>
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Overview
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Students
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Analytics
                </a>
            </nav>
            <div style="padding: 24px; border-top: 1px solid var(--border);">
                <a href="logout.php" class="nav-item" style="color: var(--danger-text);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Sign Out
                </a>
            </div>
        </aside>

        <!-- Content -->
        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <div class="page-subtitle">Welcome back, Administrator. Here's what's happening today.</div>
                </div>
            </header>

            <!-- Stats Rows -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">
                <div class="card" style="margin:0; text-align: center;">
                    <div style="font-size: 36px; font-weight: 700; color: var(--primary);"><?= $total_students ?></div>
                    <div style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-top: 4px;">Total Students</div>
                </div>
                <div class="card" style="margin:0; text-align: center;">
                    <div style="font-size: 36px; font-weight: 700; color: var(--text-main);"><?= $branches ?></div>
                    <div style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-top: 4px;">Active Depts</div>
                </div>
                <div class="card" style="margin:0; grid-column: span 2; display: flex; align-items: center; justify-content: center;">
                     <div style="width: 100%; height: 150px;">
                        <canvas id="branchChart"></canvas>
                     </div>
                </div>
            </div>

            <!-- Enhanced Table -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3 style="font-size: 18px;">Student Directory</h3>
                    <form style="display: flex; gap: 8px;">
                        <input name="q" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search students..." style="width: 300px;">
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>College ID</th>
                                <th>Profile</th>
                                <th>Branch</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 600;"><?= htmlspecialchars($row['college_id']) ?></td>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($row['full_name']) ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        Year: <?= !empty($row['year']) ? htmlspecialchars($row['year']) : '<span style="color:red; font-style:italic;">Pending</span>' ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if(!empty($row['branch'])): ?>
                                        <span style="background: var(--info-bg); color: var(--info-text); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase;"><?= htmlspecialchars($row['branch']) ?></span>
                                    <?php else: ?>
                                        <span style="background: #f4f5f7; color: #6b778c; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase;">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: var(--success-text);">
                                        <span style="width: 6px; height: 6px; background: var(--success-text); border-radius: 50%;"></span> Active
                                    </span>
                                </td>
                                <td>
                                    <a href="admin_view_student.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="padding: 4px 12px; font-size: 12px;">Manage</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php if($result->num_rows == 0): ?>
                        <div style="padding: 40px; text-align: center; color: var(--text-muted);">No records found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Initialize Charts
    const ctx = document.getElementById('branchChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Students per Branch',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: '#0052cc',
                borderRadius: 4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } },
            maintainAspectRatio: false
        }
    });
    </script>
</body>
</html>
