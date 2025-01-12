<?php
include 'config.php';
// Truy vấn dữ liệu từ bảng `product`
$sql = "SELECT product_id, product_name, product_price, product_img, product_date, product_quantity, product_description FROM product";
$result = $conn->query($sql);
// Lấy `cat_id` và `page` từ URL
$cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0; // Mặc định là 0 (tất cả loại)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;        // Mặc định là trang 1

// Số sản phẩm trên mỗi trang
$products_per_page = 8;

// Tính toán offset
$offset = ($page - 1) * $products_per_page;

// Truy vấn tổng số sản phẩm
if ($cat_id > 0) {
    $count_query = "SELECT COUNT(*) AS total FROM product WHERE cat_id = $cat_id";
} else {
    $count_query = "SELECT COUNT(*) AS total FROM product";
}
$count_result = $conn->query($count_query);
$total_products = $count_result->fetch_assoc()['total'];

// Tính tổng số trang
$total_pages = ceil($total_products / $products_per_page);

// Truy vấn sản phẩm cho trang hiện tại
if ($cat_id > 0) {
    $products_query = "SELECT * FROM product WHERE cat_id = $cat_id LIMIT $offset, $products_per_page";
} else {
    $products_query = "SELECT * FROM product LIMIT $offset, $products_per_page";
}

$products_result = $conn->query($products_query);

$products = [];
if ($products_result->num_rows > 0) {
    while ($row = $products_result->fetch_assoc()) {
        $products[] = $row;
    }
}
// Lấy danh sách loại sản phẩm
$categories_query = "SELECT * FROM category";
$categories_result = $conn->query($categories_query);

$categories = [];
if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}
// Xử lí tìm kiếm
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT product_id, product_name, product_img FROM product WHERE product_name LIKE ?");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div style="display: flex; align-items: center; margin-bottom: 15px;">';
            echo '<a href="single-product.php?product_id=' . htmlspecialchars($row['product_id']) . '" style="display: flex; align-items: center; text-decoration: none;">';
            echo '<img src="img/' . htmlspecialchars($row['product_img']) . '" alt="' . htmlspecialchars($row['product_name']) . '" style="width: 50px; height: 50px; margin-right: 10px;">';
            echo '<span style="font-size: 16px; color: #333; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">' . htmlspecialchars($row['product_name']) . '</span>';
            echo '</a>';
            echo '</div>';
        }
    }
}
// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop Page- Ustora Demo</title>

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet'
        type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

</head>

<body>

    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                        <ul>
                            <li><a href="#"><i class="fa fa-user"></i> My Account</a></li>
                            <li><a href="#"><i class="fa fa-heart"></i> Wishlist</a></li>
                            <li><a href="cart.php"><i class="fa fa-user"></i> My Cart</a></li>
                            <li><a href="checkout.html"><i class="fa fa-user"></i> Checkout</a></li>
                            <li><a href="login.php"><i class="fa fa-user"></i> Login</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="header-right">
                        <form id="search-form" class="search-form">
                            <input type="text" id="search-query" name="query" placeholder="Search products..."
                                autocomplete="off">
                            <input type="submit" value="Search">
                        </form>
                        <div id="search-results" class="search-results"></div>

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End header area -->

    <div class="site-branding-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="logo">
                        <h1><a href="./"><img src="img/logo.png"></a></h1>
                    </div>
                </div>


            </div>
        </div>
    </div> <!-- End site branding area -->

    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Home</a></li>
                        <li class="active"><a href="shop.php">Shop page</a></li>
                        <li><a href="single-product.php">Single product</a></li>
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="checkout.html">Checkout</a></li>
                        <li><a href="#">Category</a></li>
                        <li><a href="#">Others</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- End mainmenu area -->

    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shop</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="shop.php">Tất cả</a></li> <!-- Nút để hiển thị tất cả sản phẩm -->
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="shop.php?cat_id=<?php echo $category['cat_id']; ?>">
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row">
                <?php foreach ($products as $index => $product): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="single-shop-product">
                            <div class="product-upper">
                                <img src="img/<?php echo htmlspecialchars($product['product_img']); ?>"
                                    alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            </div>
                            <h2><a href="single-product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo htmlspecialchars($product['product_name']); ?></a></h2>
                            <div class="product-carousel-price">
                                <ins>$<?php echo number_format($product['product_price'], 2); ?></ins>
                            </div>

                            <div class="product-option-shop">
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="product_id"
                                        value="<?php echo $product['product_id']; ?>">
                                    <div class="quantity">
                                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                                            step="1">
                                    </div>
                                    <button type="submit" class="add_to_cart_button">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if (($index + 1) % 4 == 0): ?>
            </div>
            <div class="row">
            <?php endif; ?>
        <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center">
                        <nav>
                            <ul class="pagination">
                                <!-- Nút "Trang đầu" -->
                                <?php if ($page > 1): ?>
                                    <li>
                                        <a href="?page=1" aria-label="First">
                                            <span aria-hidden="true">Trang đầu</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="disabled">
                                        <a href="#" aria-label="First">
                                            <span aria-hidden="true">Trang đầu</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Số trang -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="<?php echo $i === $page ? 'active' : ''; ?>">
                                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Nút "Trang cuối" -->
                                <?php if ($page < $total_pages): ?>
                                    <li>
                                        <a href="?page=<?php echo $total_pages; ?>" aria-label="Last">
                                            <span aria-hidden="true">Trang cuối</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="disabled">
                                        <a href="#" aria-label="Last">
                                            <span aria-hidden="true">Trang cuối</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <div class="footer-top-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="footer-about-us">
                        <h2>u<span>Stora</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis sunt id doloribus vero
                            quam laborum quas alias dolores blanditiis iusto consequatur, modi aliquid eveniet eligendi
                            iure eaque ipsam iste, pariatur omnis sint! Suscipit, debitis, quisquam. Laborum commodi
                            veritatis magni at?</p>
                        <div class="footer-social">
                            <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">User Navigation </h2>
                        <ul>
                            <li><a href="">My account</a></li>
                            <li><a href="">Order history</a></li>
                            <li><a href="">Wishlist</a></li>
                            <li><a href="">Vendor contact</a></li>
                            <li><a href="">Front page</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Categories</h2>
                        <ul>
                            <li><a href="">Mobile Phone</a></li>
                            <li><a href="">Home accesseries</a></li>
                            <li><a href="">LED TV</a></li>
                            <li><a href="">Computer</a></li>
                            <li><a href="">Gadets</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-newsletter">
                        <h2 class="footer-wid-title">Newsletter</h2>
                        <p>Sign up to our newsletter and get exclusive deals you wont find anywhere else straight to
                            your inbox!</p>
                        <div class="newsletter-form">
                            <input type="email" placeholder="Type your email">
                            <input type="submit" value="Subscribe">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="copyright">
                        <p>&copy; 2015 uCommerce. All Rights Reserved. <a href="http://www.freshdesignweb.com"
                                target="_blank">freshDesignweb.com</a></p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card-icon">
                        <i class="fa fa-cc-discover"></i>
                        <i class="fa fa-cc-mastercard"></i>
                        <i class="fa fa-cc-paypal"></i>
                        <i class="fa fa-cc-visa"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest jQuery form server -->
    <script src="https://code.jquery.com/jquery.min.js"></script>

    <!-- Bootstrap JS form CDN -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- jQuery sticky menu -->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>

    <!-- jQuery easing -->
    <script src="js/jquery.easing.1.3.min.js"></script>

    <!-- Main Script -->
    <script src="js/main.js"></script>
    <script src="js/search.js"></script>

</body>

</html>