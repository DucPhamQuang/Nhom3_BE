<?php
// Bao gồm tệp kết nối cơ sở dữ liệu
include('item.php');
$categoryObj = new Category();

// Thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $categoryName = $_POST['category'];
    $newCategoryId = $categoryObj->addCategory($categoryName);

    if ($newCategoryId) {
        echo "<tr id='category_{$newCategoryId}'>
                <td>{$newCategoryId}</td>
                <td>{$categoryName}</td>
                <td>
                    <a class='edit' title='Sửa' data-toggle='tooltip'><i class='fa fa-pencil' aria-hidden='true'></i></a>
                    <a class='delete' title='Xóa' data-toggle='tooltip'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
                </td>
              </tr>";
    } else {
        echo "Lỗi khi thêm danh mục!";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Lấy ID danh mục từ yêu cầu
    $categoryId = $_POST['cat_id'];

    // Tạo đối tượng Category và gọi phương thức xóa
    $categoryObj = new Category();
    $categoryObj->deleteCategory($categoryId);

    // Trả về phản hồi thành công
    echo json_encode(['status' => 'success']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $catId = $_POST['cat_id'];
    $catName = $_POST['cat_name'];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        echo json_encode(['success' => false, 'error' => 'Không thể kết nối cơ sở dữ liệu.']);
        exit;
    }

    // Chuẩn bị câu lệnh SQL để tránh SQL Injection
    $query = "UPDATE category SET cat_name = ? WHERE cat_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Lỗi chuẩn bị truy vấn.']);
        exit;
    }

    $stmt->bind_param('si', $catName, $catId);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $catId = $_POST['cat_id'] ?? null;
    $catName = $_POST['cat_name'] ?? null;

    if ($catId && $catName) {
        // Xử lý cập nhật cơ sở dữ liệu ở đây
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Dữ liệu không hợp lệ"]);
    }
    exit;
}

// Lấy tất cả danh mục
$categories = $categoryObj->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Bin-It">
    <meta property="og:url" />
    <meta property="og:type" content="truongbinit" />
    <meta property="og:title" content="Website TruongBin" />
    <meta property="og:description" content="Wellcome to my Website" />

    <title>Nhân Viên | Quản Lý Bán Hàng</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="css/style.css">
    <!-- Latest compiled and minified CSS -->
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!-- jQuery library -->
    <!--===============================================================================================-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <!--===============================================================================================-->
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
    <!--===============================================================================================-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <script type="text/javascript">

    </script>
</head>

<body onload="time()">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#"><i class="fa fa-user-circle" aria-hidden="true"></i> QUẢN
                    LÝ NHÂN VIÊN</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="custommer.php" data-toggle="tooltip" data-placement="bottom" title="NHÂN VIÊN">Home</a>
                    </li>
                    <li><a href="category.php" data-toggle="tooltip" data-placement="bottom"
                            title="ĐIỂM DANH">Category</a></li>
                    <li><a href="a" data-toggle="tooltip" data-placement="bottom" title="TIỀN LƯƠNG">Product</a></li>
                    <li><a href="a" data-toggle="tooltip" data-placement="bottom" title="LỊCH CÔNG TÁC">LỊCH CÔNG
                            TÁC</a>
                    </li>
                    <li><a href="a" data-toggle="tooltip" data-placement="bottom" title="BÁO CÁO">BÁO CÁO</a>
                    </li>
                    <li><a href="a" data-toggle="tooltip" data-placement="bottom" title="SỰ KIỆN">SỰ KIỆN</a></li>
                    <li>
                        <a href="a" data-toggle="tooltip" data-placement="bottom" title="TÀI KHOẢN"><b>Tài Khoản</b>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown">
                            <li><a href="/index.html" data-toggle="tooltip" data-placement="bottom"
                                    title="ĐĂNG XUẤT"><b>Đăng xuất <i class="fas fa-sign-out-alt"></i></b></a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <div class="container-fluid al">

        <form action="">

        </form>
        <b>CHỨC NĂNG CHÍNH:</b><Br>
        <button class="nv btn add-new" type="button" data-toggle="tooltip" data-placement="top"
            title="Thêm Nhân Viên"><i class="fas fa-user-plus"></i></button>
        <button class="nv" type="button" onclick="sortTable()" data-toggle="tooltip" data-placement="top"
            title="Lọc Dữ Liệu"><i class="fa fa-filter" aria-hidden="true"></i></button>
        <button class="nv xuat" data-toggle="tooltip" data-placement="top" title="Xuất File"><i
                class="fas fa-file-import"></i></button>
        <button class="nv cog" data-toggle="tooltip" data-placement="bottom" title=""><i
                class="fas fa-cogs"></i></button>
        <div class="table-title">
            <div class="row">

            </div>

        </div>
        <table class="table table-bordered" id="myTable">
            <!-- Button để hiển thị form thêm danh mục -->
            <button id="addCategoryBtn" onclick="showAddForm()">Thêm danh mục</button>

            <!-- Form để nhập danh mục mới (ban đầu ẩn) -->
            <div id="addCategoryForm" style="display: none;">
                <form action="category.php" method="POST">
                    <label for="category">Tên danh mục:</label>
                    <input type="text" id="category" name="category" required>
                    <button type="submit">Thêm</button>
                    <button type="button" onclick="hideAddForm()">Hủy</button>
                </form>
            </div>

            <tbody>
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr class="ex">
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Tính năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($categories) {
                            while ($row = $categories->fetch_assoc()) {
                                echo "<tr id='category_{$row['cat_id']}'>";
                                echo "<td>" . $row['cat_id'] . "</td>";
                                echo "<td>" . $row['cat_name'] . "</td>";
                                echo "<td>
                            <a class='edit' title='Sửa' data-toggle='tooltip' onclick='editCategory({$row['cat_id']}, \"{$row['cat_name']}\")'><i class='fa fa-pencil' aria-hidden='true'></i></a>
                            <a class='delete' title='Xóa' data-toggle='tooltip' onclick='deleteCategory({$row['cat_id']})'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
                          </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Không có danh mục nào.</td></tr>";
                        }
                        ?>

                    </tbody>
                </table>
                <div id="pageNavPosition" class="text-right"></div>

    </div>
    <hr class="hr1">
    <div class="container-fluid end">
        <div class="row text-center">
            <div class="col-lg-12 link">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-youtube"></i>
                <i class="fab fa-google"></i>
            </div>
            <div class="col-lg-12">
                2019 CopyRight Phan mem quan ly | Design by <a href="#">TruongBinIT</a>
            </div>
        </div>
    </div>
    <script src="jquery.min.js"></script>
    <script type="text/javascript">



        function showAddForm() {
            document.getElementById("addCategoryForm").style.display = "block";
            document.getElementById("addCategoryBtn").style.display = "none";
        }

        function hideAddForm() {
            document.getElementById("addCategoryForm").style.display = "none";
            document.getElementById("addCategoryBtn").style.display = "block";
        }

        function editCategory(catId, catName) {
            // Thực hiện hành động sửa (có thể mở modal hoặc input để chỉnh sửa)
        }

        function deleteCategory(catId) {
            if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
                $.ajax({
                    url: 'category.php', // Bạn có thể gọi lại PHP để xử lý xóa
                    method: 'POST',
                    data: {
                        deleteCategory: true,
                        catId: catId
                    },
                    success: function (response) {
                        $('#category_' + catId).remove();
                    }
                });
            }
        }
        //Not use
        jQuery(function () {
            jQuery(".cog").click(function () {
                swal("Sorry!", "Tính Năng Này Chưa Có", "error");
            });
        });
        //Tool tip
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).ready(function () {
            // Check the current URL and add the 'active' class to the corresponding menu item
            var currentUrl = window.location.pathname;
            $('.navbar-nav li a').each(function () {
                if (this.href.indexOf(currentUrl) !== -1) {
                    $(this).parent().addClass('active');
                }
            });
        });
        $(document).on("click", ".edit", function () {
            var row = $(this).closest("tr");

            // Lấy giá trị cũ của danh mục
            var categoryId = row.find("td").eq(0).text();
            var categoryName = row.find("td").eq(1).text();

            // Thay đổi nội dung của các ô thành input để người dùng có thể chỉnh sửa
            row.find("td").eq(1).html('<input type="text" class="form-control" value="' + categoryName + '" />');
            row.find("td").eq(2).html('<a class="save" title="Lưu lại" data-toggle="tooltip"><i class="fa fa-save" aria-hidden="true"></i></a> <a class="cancel" title="Hủy" data-toggle="tooltip"><i class="fa fa-times" aria-hidden="true"></i></a>');
        });

        $(document).on("click", ".save", function () {
            var row = $(this).closest("tr");
            var categoryId = row.find("td").eq(0).text().trim(); // Lấy ID danh mục
            var categoryName = row.find("input").val().trim(); // Lấy tên danh mục mới từ ô input

            // Kiểm tra nếu tên danh mục không rỗng
            if (categoryName === "") {
                alert("Vui lòng nhập tên danh mục.");
                return;
            }

            // Gửi yêu cầu AJAX để lưu dữ liệu vào cơ sở dữ liệu
            $.ajax({
                url: 'category.php', // Tệp PHP để xử lý cập nhật danh mục
                method: 'POST',
                dataType: 'json', // Nhận phản hồi dạng JSON
                data: {
                    action: 'update',
                    cat_id: categoryId,
                    cat_name: categoryName
                },
                success: function (response) {
                    if (response.success) {
                        // Nếu cập nhật thành công, cập nhật lại nội dung trong bảng
                        row.find("td").eq(1).html(categoryName);
                        row.find("td").eq(2).html('<a class="edit" title="Sửa" data-toggle="tooltip"><i class="fa fa-pencil" aria-hidden="true"></i></a> <a class="delete" title="Xóa" data-toggle="tooltip"><i class="fa fa-trash-o" aria-hidden="true"></i></a>');
                        alert("Danh mục đã được cập nhật.");
                    } else {
                        alert("Lỗi khi cập nhật danh mục: " + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Lỗi khi gửi yêu cầu: ", error);
                    alert("Không thể kết nối tới máy chủ.");
                }
            });
        });



        $(document).on("click", ".cancel", function () {
            var row = $(this).closest("tr");
            var categoryName = row.find("td").eq(1).text(); // Lấy tên danh mục cũ
            row.find("td").eq(1).html(categoryName); // Quay lại tên danh mục cũ
            row.find("td").eq(2).html('<a class="edit" title="Sửa" data-toggle="tooltip"><i class="fa fa-pencil" aria-hidden="true"></i></a> <a class="delete" title="Xóa" data-toggle="tooltip"><i class="fa fa-trash-o" aria-hidden="true"></i></a>'); // Hiển thị lại nút sửa và xóa
        });


    </script>

</body>

</html>