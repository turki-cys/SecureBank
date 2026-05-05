<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$alertType = 'danger';
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT username, balance FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();
$currentBalance = $currentUser['balance'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $recipient = htmlspecialchars(trim($_POST['recipient']));

    if ($amount > 0 && $amount <= $currentBalance && !empty($recipient)) {
        
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$recipient]);
        $recipientData = $stmt->fetch();

        if ($recipientData) {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare('UPDATE users SET balance = balance - ? WHERE id = ?');
                $stmt->execute([$amount, $user_id]);

                $stmt = $pdo->prepare('UPDATE users SET balance = balance + ? WHERE id = ?');
                $stmt->execute([$amount, $recipientData['id']]);

                $stmt = $pdo->prepare('INSERT INTO transactions (user_id, amount, recipient) VALUES (?, ?, ?)');
                $stmt->execute([$user_id, $amount, $recipient]);

                $pdo->commit();
                $message = 'Fund transfer completed successfully.';
                $alertType = 'success';
                $currentBalance -= $amount; 
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = 'System Error: ' . $e->getMessage();
            }
        } else {
            $message = 'Recipient username does not exist.';
        }
    } else {
        $message = 'Invalid transfer details or insufficient balance.';
    }
}

$stmt = $pdo->prepare('
    SELECT t.*, u.username as sender_username 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.user_id = ? OR t.recipient = ? 
    ORDER BY t.id DESC
');
$stmt->execute([$user_id, $currentUser['username']]);
$transactions = $stmt->fetchAll();

include 'header.php';
?>

<div class="container w-100">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Welcome, <span class="text-dark"><?php echo htmlspecialchars($currentUser['username']); ?></span></h2>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold text-center">Current Balance</h4>
                    <h2 class="mb-0 fw-bold text-center mt-2"><?php echo htmlspecialchars(number_format($currentBalance, 2)); ?> SAR</h2>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Transfer Funds</h5>
                    <?php if($message): ?>
                        <div class="alert alert-<?php echo $alertType; ?> fw-bold" role="alert"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <form method="POST" onsubmit="return validateTransfer()">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Transfer Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control form-control-lg" required>
                            <div id="amountError" class="text-danger mt-1 fw-bold" style="display: none; font-size: 14px;">Amount must be greater than zero.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Recipient Username</label>
                            <input type="text" name="recipient" id="recipient" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold">Send Funds Securely</button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Transaction History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="py-3">Amount</th>
                                    <th class="py-3">Transaction Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($transactions)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No transactions found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $t): ?>
                                    <tr>
                                        <?php if($t['user_id'] == $user_id): ?>
                                            <td class="py-3 fw-bold text-danger">- <?php echo htmlspecialchars($t['amount']); ?> SAR</td>
                                            <td class="py-3">Sent to <strong class="text-primary"><?php echo htmlspecialchars($t['recipient']); ?></strong></td>
                                        <?php else: ?>
                                            <td class="py-3 fw-bold text-success">+ <?php echo htmlspecialchars($t['amount']); ?> SAR</td>
                                            <td class="py-3">Received from <strong class="text-primary"><?php echo htmlspecialchars($t['sender_username']); ?></strong></td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateTransfer() {
    let amount = document.getElementById('amount').value;
    document.getElementById('amountError').style.display = 'none';

    if (amount <= 0) {
        document.getElementById('amountError').style.display = 'block';
        return false;
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>