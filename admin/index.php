<?php
include('config.php');

class Admin {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function login($username, $password) {
        // Chuẩn bị câu lệnh SQL để kiểm tra tên đăng nhập
        $stmt = $this->conn->prepare("SELECT ad_id, ad_pwd FROM admin WHERE ad_user = ?");
        
        if ($stmt) {
            // Ràng buộc tham số và thực thi câu lệnh
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            // Kiểm tra nếu có kết quả
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($ad_id, $ad_pwd);
                $stmt->fetch();

                // So sánh mật khẩu
                if ($password === $ad_pwd) { // Nếu mật khẩu lưu dưới dạng plaintext
                    // Lưu thông tin đăng nhập vào session
                    $_SESSION['ad_id'] = $ad_id;
                    $_SESSION['ad_user'] = $username;

                    $stmt->close();
                    return true; // Đăng nhập thành công
                } else {
                    $stmt->close();
                    return "Mật khẩu không chính xác!";
                }
            } else {
                $stmt->close();
                return "Tên đăng nhập không tồn tại!";
            }
        } else {
            return "Đã xảy ra lỗi khi kết nối tới cơ sở dữ liệu!";
        }
    }

    public function __destruct() {
        $this->db->closeConnection();
    }
}
session_start(); // Khởi động session


$error = ""; // Biến chứa thông báo lỗi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Khởi tạo đối tượng Admin và gọi phương thức login
    $admin = new Admin();
    $result = $admin->login($username, $password);

    if ($result === true) {
        // Nếu đăng nhập thành công, chuyển hướng đến trang chính
        header("Location: custommer.php");
        exit;
    } else {
        // Nếu có lỗi, lưu thông báo lỗi
        $error = $result;
    }
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Đăng nhập quản trị | Website quản trị v2.0</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="images/team.jpg" alt="IMG">
                </div>
                <!-- Form Đăng Nhập -->
                <form class="login100-form validate-form" method="POST" action="">
                    <span class="login100-form-title">
                        <b>ĐĂNG NHẬP HỆ THỐNG POS</b>
                    </span>

                    

                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script>
        // Bật/Tắt hiển thị mật khẩu
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const passwordIcon = document.querySelector(".btn-outline-secondary i");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
