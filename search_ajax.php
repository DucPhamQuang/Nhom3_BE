<?php
// Kết nối cơ sở dữ liệu
require_once 'config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Tìm kiếm sản phẩm có tên giống từ khóa, giới hạn 5 sản phẩm
    $stmt = $conn->prepare("SELECT product_id, product_name, product_img 
                            FROM product 
                            WHERE product_name LIKE ? 
                            ORDER BY CHAR_LENGTH(product_name) ASC 
                            LIMIT 5");
    $searchQuery = '%' . $query . '%';
    $stmt->bind_param('s', $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hiển thị danh sách sản phẩm
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

    $stmt->close();
}

$conn->close();
