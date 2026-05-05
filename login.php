<?php
session_start();
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // COUNTERMEASURE 1: Server-side input sanitization
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    // COUNTERMEASURE 2: PDO Prepared Statements
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    // Verifies the secure BCRYPT hash instead of raw text
    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'Invalid credentials provided.';
    }
}

include 'header.php';
?>
<div class="container" style="max-width: 500px; width: 100%;">
    <div class="card shadow-lg border-0 rounded-4 mt-5">
        <div class="card-body p-5">
            <h2 class="text-center text-dark mb-4 fw-bold">Account Login</h2>
            <?php if($message): ?>
                <div class="alert alert-danger text-center fw-bold" role="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold">Access Account</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>