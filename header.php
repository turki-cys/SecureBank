<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureBank Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=6">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <div class="brand-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l1 2h18l1-2-10-5zM4 10v7h2v-7H4zm6 0v7h2v-7h-2zm6 0v7h2v-7h-2zM2 19v2h20v-2H2z"/>
                    </svg>
                </div>
                <strong class="fs-4">SecureBank</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav fs-5 align-items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link text-warning" href="admin.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li class="nav-item ms-lg-3"><a class="btn btn-danger" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-2"><a class="btn btn-outline-light w-100 mb-2 mb-lg-0" href="login.php">Login</a></li>
                        <li class="nav-item ms-lg-2"><a class="btn btn-light text-dark fw-bold w-100" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="content-wrapper d-flex justify-content-center align-items-start">