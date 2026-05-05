<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Access denied. Administrator privileges required.');
}

$stmt = $pdo->query('SELECT id, username, role, balance FROM users');
$users = $stmt->fetchAll();

$admin_msg = '';
$admin_msg_type = '';
if (isset($_SESSION['admin_msg'])) {
    $admin_msg = $_SESSION['admin_msg'];
    $admin_msg_type = $_SESSION['admin_msg_type'];
    
    unset($_SESSION['admin_msg']);
    unset($_SESSION['admin_msg_type']);
}

include 'header.php';
?>
<div class="container w-100">
    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-body p-5">
            
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <h2 class="text-dark mb-0 fw-bold"><span class="text-danger">Admin</span> Control Panel</h2>
                <a href="clear_history.php" class="btn btn-danger fw-bold px-4" onclick="return confirm('Are you sure you want to wipe all transaction history? This cannot be undone.');">Wipe Transaction History</a>
            </div>
            
            <p class="text-muted mb-4">Overview of all registered system accounts and balances.</p>
            
            <?php if($admin_msg): ?>
                <div class="alert alert-<?php echo $admin_msg_type; ?> fw-bold alert-dismissible fade show" role="alert">
                    <?php echo $admin_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3">User ID</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">System Role</th>
                            <th class="py-3">Account Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="py-3 fw-bold"><?php echo htmlspecialchars($u['id']); ?></td>
                            <td class="py-3"><?php echo htmlspecialchars($u['username']); ?></td>
                            <td class="py-3">
                                <?php if($u['role'] === 'admin'): ?>
                                    <span class="badge bg-danger px-3 py-2">Administrator</span>
                                <?php else: ?>
                                    <span class="badge bg-dark px-3 py-2">Standard User</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 fw-bold text-success"><?php echo htmlspecialchars(number_format($u['balance'], 2)); ?> SAR</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-outline-dark fw-bold px-4">Return to User Dashboard</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>