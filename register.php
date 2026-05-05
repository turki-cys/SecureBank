<?php
require 'db.php';
$message = '';
$alertType = 'danger';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = htmlspecialchars(trim($_POST['username']));
    $pass = trim($_POST['password']);

    if (strlen($user) < 3 || strlen($pass) < 5) {
        $message = 'Server Validation Failed. Invalid input length.';
    } else {
        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        
        try {
            $stmt->execute([$user, $hashed_password]);
            $message = 'User registered securely. You can now login.';
            $alertType = 'success';
        } catch(PDOException $e) {
            $message = 'Username already exists.';
        }
    }
}

include 'header.php';
?>
<div class="container" style="max-width: 500px; width: 100%;">
    <div class="card shadow-lg border-0 rounded-4 mt-5">
        <div class="card-body p-5">
            <h2 class="text-center text-dark mb-4 fw-bold">Create Account</h2>
            <?php if($message): ?>
                <div class="alert alert-<?php echo $alertType; ?> text-center fw-bold" role="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" onsubmit="return validateRegistration()">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Username</label>
                    <input type="text" name="username" id="username" class="form-control form-control-lg" required>
                    <div id="userError" class="text-danger mt-1 fw-bold" style="display: none; font-size: 14px;">Username must be at least 3 characters.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                    <div id="passError" class="text-danger mt-1 fw-bold" style="display: none; font-size: 14px;">Password must be at least 5 characters.</div>
                </div>
                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold">Register Now</button>
            </form>
            <div class="text-center mt-4">
                <a href="login.php" class="text-decoration-none text-dark fw-bold">Already have an account? Login here</a>
            </div>
        </div>
    </div>
</div>

<script>
function validateRegistration() {
    let isValid = true;
    let u = document.getElementById('username').value;
    let p = document.getElementById('password').value;
    
    document.getElementById('userError').style.display = 'none';
    document.getElementById('passError').style.display = 'none';

    if (u.length < 3) {
        document.getElementById('userError').style.display = 'block';
        isValid = false;
    }
    if (p.length < 5) {
        document.getElementById('passError').style.display = 'block';
        isValid = false;
    }
    return isValid;
}
</script>
<?php include 'footer.php'; ?>