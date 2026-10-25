<?php
session_start();
require_once 'db.php';

// Guard check: redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['email'];
$user_role = $_SESSION['role'] ?? 'user';
$message = '';

// Process Admin Action: Change User Role
if ($user_role === 'admin' && isset($_POST['update_role'])) {
    $target_user_id = (int)$_POST['user_id'];
    $new_role = $_POST['new_role'];

    // Basic validation on role inputs
    if (in_array($new_role, ['user', 'admin'])) {
        $update_stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_role, $target_user_id);
        
        if ($update_stmt->execute()) {
            $message = "User role updated successfully.";
        } else {
            $message = "Error updating role.";
        }
        $update_stmt->close();
    }
}

// Fetch all users if current user is Admin
$users_list = [];
if ($user_role === 'admin') {
    $result = $conn->query("SELECT id, email, role, created_at FROM users ORDER BY id ASC");
    if ($result) {
        $users_list = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <h2>Dashboard</h2>
        <p>Logged in as: <strong><?php echo htmlspecialchars($user_email); ?></strong></p>
        <p>Role: <span style="text-transform: uppercase; font-weight: bold; color: #007bff;"><?php echo htmlspecialchars($user_role); ?></span></p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success" style="margin-top: 15px;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- ADMIN-ONLY SECTION -->
        <?php if ($user_role === 'admin'): ?>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <div style="background-color: #f8f9fa; border: 1px solid #e9ecef; padding: 20px; border-radius: 6px; text-align: left;">
                <h3 style="margin-bottom: 15px; color: #333;">User Management</h3>
                
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #dee2e6; background-color: #e9ecef;">
                            <th style="padding: 8px;">ID</th>
                            <th style="padding: 8px;">Email</th>
                            <th style="padding: 8px;">Role</th>
                            <th style="padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_list as $user): ?>
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 8px;"><?php echo $user['id']; ?></td>
                                <td style="padding: 8px;"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td style="padding: 8px;">
                                    <span style="font-weight: bold; color: <?php echo $user['role'] === 'admin' ? '#dc3545' : '#28a745'; ?>;">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td style="padding: 8px;">
                                    <form action="dashboard.php" method="POST" style="display: flex; gap: 5px; align-items: center;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <select name="new_role" style="padding: 4px; border-radius: 4px; border: 1px solid #ccc;">
                                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                        <button type="submit" name="update_role" class="btn" style="padding: 4px 8px; font-size: 12px; width: auto;">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>