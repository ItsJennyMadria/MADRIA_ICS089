<?php
session_start();

// Guard check: redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['email'];
$user_role = $_SESSION['role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Dashboard</h2>
        <p>Logged in as: <strong><?php echo htmlspecialchars($user_email); ?></strong></p>
        <p>Role: <span style="text-transform: uppercase; font-weight: bold; color: #007bff;"><?php echo htmlspecialchars($user_role); ?></span></p>

        <!-- ADMIN-ONLY SECTION -->
        <?php if ($user_role === 'admin'): ?>
            <div style="background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 12px; border-radius: 4px; margin: 15px 0; text-align: left;">
                <strong>Admin Features Enabled</strong>
                <p style="font-size: 13px; margin-top: 5px;">You have administrative access to manage users or view system metrics.</p>
            </div>
        <?php endif; ?>

        <br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>