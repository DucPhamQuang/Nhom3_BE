<?php
session_start();

// Xóa session
session_destroy();

// Xóa cookie nếu đã lưu
setcookie("user", "", time() - 3600, "/");

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit;
?>
