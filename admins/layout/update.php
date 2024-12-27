<?php
$mode = getIndex("mode", '');
$page = getIndex('page');
if ($mode == 'category') {
    $cat_id = getIndex('cat_id');
    $cat_name = getIndex('cat_name');
    echo "<div class='col-sm-12 check-order' style='background-color:lavender;'>   
                <h2 style='text-align:center; margin:15px 0 15px 0;'>Sửa đổi Category</h3>
                <div class='form-group'>
                    <form action='index.php' method='get'>
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Tên category</label>
                        </div>
                        
                        <div class=\"col-sm-8\">
                            <input type=\"text\" class=\"form-control\" name='cat_name' value='$cat_name'>
                        </div>                     
                        <div class=\"col-sm-2\">
                        <button class=\"btn btn-primary float-right\" name='update-sm' value='update' title='Thêm mới'>Lưu thay đổi</button>
                        </div>
                        <input type=\"hidden\" name='page' value='$page'>
                        <input type=\"hidden\" name='mode' value='category'>
                        <input type=\"hidden\" name='ac' value='update'>
                        <input type=\"hidden\" name='cat_id' value='$cat_id'>
                    </form>
                </div>           
            </div>";
} else if ($mode == 'product') {
    $cats_ad = new Category();
    $list_cats = $cats_ad->getAll();
    $pros_ad = new Provider();
    $list_pros = $pros_ad->getAll();

    $product_id = getIndex('pro_id');
    $product_name = getIndex('pro_name');
    $product_price = getIndex('pro_price');
    $product_img = getIndex('pro_img');
    $product_date = getIndex('pro_date');
    $product_quantity = getIndex('pro_quantity');
    $cat_id = getIndex('cat_id');
    $provider_id = getIndex('provider_id');

    //echo "$product_id - $product_name - $product_price - $product_img - $product_date - $product_quantity - $cat_id - $provider_id";

    $str1 = '';
    foreach ($list_cats as $value) {
        if ($value['cat_id'] == $cat_id) $str1 .= "<option value='{$value['cat_id']}' selected>{$value['cat_name']}</option>";
        else $str1 .= "<option value='{$value['cat_id']}'>{$value['cat_name']}</option>";
    }
    $str2 = '';
    foreach ($list_pros as $key => $value) {
        if ($value['provider_id'] == $provider_id) $str2 .= "<option value='{$value['provider_id']}' selected>{$value['provider_name']}</option>";
        else $str2 .= "<option value='{$value['provider_id']}'>{$value['provider_name']}</option>";
    }
    echo "<div class='col-sm-12 check-order' style='background-color:lavender;'>   
                <h2 style='text-align:center; margin:15px 0 15px 0;'>Chỉnh sửa Product</h3>
                <div class='form-group'>
                    <form action='index.php?mode=product&ac=update&product_id=$product_id&old_img=$product_img&page=$page' method='post' enctype='multipart/form-data'>
                    <div class='row element'>
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Tên sản phẩm</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                            <input type=\"text\" class=\"form-control\" name='product_name' value='$product_name'>
                        </div>      
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Giá</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                            <input type=\"text\" class=\"form-control\" name='product_price' value='$product_price'>
                        </div>                                         
                    </div>

                    <div class='row element'>
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Hình ảnh</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                            <input type='file' name='product_img'>
                            <img src='../image/$product_img' style='width:100px;height:100px;'></img>
                        </div>                            
                        <div class='col-sm-2 label'>
                        <label for='from-date'>Nhà cung cấp</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                            <select class='form-control' name='provider_id'>
                                    " . $str2 . "
                            </select>                    
                        </div>
                    </div>

                    <div class='row element'>
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Số lượng</label>
                        </div>                        
                        <div class=\"col-sm-1\">
                            <input type='number' value='$product_quantity' min='0' max='999' class=\"form-control\" name='product_quantity'>
                        </div>    
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Ngày tạo</label>
                        </div>                        
                        <div class=\"col-sm-3\">
                            <input type='date' value='$product_date' class=\"form-control\" name='product_date' disabled>
                        </div>      
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Loại sản phẩm</label>
                        </div>                        
                        <div class=\"col-sm-2\">
                           <select class='form-control' name='cat_id'>
                                " . $str1 . "
                           </select>
                        </div>                                                                         
                    </div>

                     <div class='row element'>
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Mô tả</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                            <div class=\"form-group\">
                                <textarea name=\"product_description\" class=\"input\" placeholder=\"Điền mô tả của sản phẩm\"></textarea>
                            </div>
                        </div>      
                        <div class='col-sm-2 label'>
                            <label for='from-date'>Chi tiết</label>
                        </div>                        
                        <div class=\"col-sm-4\">
                           <div class=\"form-group\">
                                <textarea name=\"product_detail\" class=\"input\" placeholder=\"Điền chi tiết của sản phẩm\"></textarea>
                            </div>
                        </div>                                                                              
                    </div>
                    
                    <div class='row element'>
                        <div class=\"col-sm-12\">
                            <button class=\"btn btn-primary float-right\" name='update-sm' value='update' title='Thay đổi'>Lưu thay đổi</button>
                        </div>
                    </div>
                    </form>
                </div>           
            </div> ";
}
