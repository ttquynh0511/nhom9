<?php

include('../configs/database.php');
$table_id = $_GET['table_id'];
$sql = "SELECT 
    o.*, 
    od.*, 
    m.* 
FROM `order` o
JOIN `order_details` od ON o.order_id = od.order_id
JOIN `menu` m ON od.menu_id = m.menu_id
WHERE o.table_id = $table_id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Don hang</title>
    <style>
        .box {
            display: flex;
            justify-content: space-between;
        }

        .box1 {
            width: 400px;
            position: fixed;
        }

        .box11 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box2 {
            font-family: Arial, sans-serif;
            margin-left: 450px;
            padding: 0;
        }

        .box11 img {
            width: 100px;
            height: 100px;
        }


        .menu {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .menu-item img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .item-details {
            flex: 1;
            padding: 0 15px;
        }

        .item-details h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }

        .item-details p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9em;
        }

        .item-price {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-price p {
            margin: 0;
            font-size: 1.1em;
            font-weight: bold;
            color: #333;
        }

        .add-btn,
        .contact-btn {
            background-color: #f5a623;
            border: none;
            color: #fff;
            font-size: 1.2em;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover,
        .contact-btn:hover {
            background-color: #e09317;
        }

        .contact-btn {
            background-color: #666;
        }

        .contact-btn:hover {
            background-color: #555;
        }

        .menu-item:last-child {
            border-bottom: none;
        }


        .box3 {
            font-family: Arial, sans-serif;
            align-items: center;
            position: fixed;
            right: 10px;
        }

        .order-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 450px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h3 {
            margin: 0;
            padding: 10px 0;
            font-size: 1.2em;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .menu {
            width: 855px;
        }

        .menu-item {

            height: 100px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            margin: 0;
            font-size: 1.1em;
            color: #333;
        }

        .item-note {
            font-size: 0.8em;
            color: #888;
        }

        .remove-item {
            font-size: 0.8em;
            color: #d9534f;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-btn {
            background-color: #f5a623;
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            font-size: 1.2em;
            border-radius: 50%;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background-color: #e09317;
        }

        .item-price {
            font-size: 1.1em;
            color: #333;
        }

        .total-section {
            display: flex;
        }

        .order-btn {
            color: white;
            background-color: red;
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            height: 50px;
            border-radius: 10px;
            border: none;
            box-shadow: #555;
            cursor: pointer;
        }
        </style>
</head>
<body>
<div class="box2">

            <div class="menu">
            <a href="http://localhost/nhom9/component/admin.php" class="icon"> <i class="fa-solid fa-arrow-left"></i></a>
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { 
                       ?>
                        <div class="menu-item">
                            <img src="<?php echo $row['img'] ?>" alt="<?php echo $row["Alt_text"] ?>">
                            <div class="item-details">
                                <h3><?php echo $row["name"] ?></h3>
                                <!-- <div class="aaa">
                                <button class="edit">Sửa</button>||
                                <button class="delete">Xóa</button>
                            </div> -->
                                
                            </div>
                            <div class="item-price">
                            <p>Số lượng:<?php echo $row["quantity"] ?></p>
                                <p>Giá:<?php echo $row["price"] ?>₫</p>
                            </div>
                            
                        </div>
                        <div class="total-section">
                    <p>Tổng cộng:</p>
                    <p class="total-price"><?php echo $row["total_price"] ?>₫</p>
                </div>
                        
                <?php }
                } ?>
            </div>
        </div>
</body>
</html>