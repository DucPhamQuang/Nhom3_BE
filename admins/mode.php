<?php
$mode = getIndex("mode", '');

if ($mode == 'category')
     include 'module/category/index.php';

else if ($mode == 'product')
     include 'module/product/index.php';

else if ($mode == 'exit') {
     if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
     echo "<script>location.href = 'login.php'</script>";
     exit;
}
