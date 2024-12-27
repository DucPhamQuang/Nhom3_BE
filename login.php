<?php
require 'config.php'; // Connect to the database
session_start(); // Start the session

// Handle logout and clear cookies
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    setcookie("PHPSESSID", "", time() - 3600, "/");
    setcookie("user", "", time() - 3600, "/");
    header("Location: login.php");
    exit;
}

// Display message if redirected from registration
$message = '';
if (isset($_GET['message']) && $_GET['message'] === 'registered') {
    $message = "Đăng ký thành công! Vui lòng đăng nhập.";
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check credentials in the database
    $stmt = $conn->prepare("SELECT user_id, user_pwd FROM user WHERE user_name = ?");
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();

            // Debugging output
            error_log("Attempting to verify password for user: " . $username);
            error_log("Hashed Password from DB: " . $hashedPassword);

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Save user info in session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $message = "Mật khẩu không đúng.";
                // Redirect to shop.php (home page)
                header("Location: shop.php");
                exit;
            } else {
                header("Location: shop.php");
            }
        } else {
            $message = "Tên đăng nhập không tồn tại.";
        }

        $stmt->close();
    } else {
        $message = "Có lỗi trong quá trình xử lý. Vui lòng thử lại.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .btn-register {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        
        <form method="POST" action="">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
            <?php if (!empty($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <button type="submit">Đăng nhập</button>
            <a href="register.php" class="btn-register">Đăng ký</a>
        </form>
    </div>
</body>
</html> 