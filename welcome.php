<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="text-align: center;">
        <h1>Welcome!</h1>
        <p style="color: #555555; margin-bottom: 10px;">Logged in as:</p>
        <strong style="font-size: 18px; color: #333333;"><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
        <br><br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>