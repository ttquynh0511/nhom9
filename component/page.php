<?php
session_start();
include('../configs/database.php');
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

// Thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$id])) {
        // Nếu sản phẩm đã tồn tại, tăng số lượng
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        // Nếu sản phẩm chưa có, thêm mới vào giỏ hàng
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

// Tăng số lượng sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['increase_quantity'])) {
    $id = $_POST['id'];
    $_SESSION['cart'][$id]['quantity']++;
}

// Giảm số lượng sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decrease_quantity'])) {
    $id = $_POST['id'];
    if ($_SESSION['cart'][$id]['quantity'] > 1) {
        $_SESSION['cart'][$id]['quantity']--;
    }
}

// Xóa sản phẩm khỏi giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item'])) {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);
}

// Tính tổng giá trị đơn hàng
$totalPrice = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $table_id = $_GET['table_id']; // Lấy ID bàn từ query string
    $totalPrice = 0;

    // Tính tổng giá trị đơn hàng
    foreach ($_SESSION['cart'] as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // Bước 1: Lưu đơn hàng vào bảng orders
    // Bước 1: Lưu đơn hàng vào bảng orders
    $sql_order = "INSERT INTO `order` (table_id, total_price) VALUES (?, ?)";
    if ($stmt_order = $conn->prepare($sql_order)) {
        $stmt_order->bind_param("id", $table_id, $totalPrice);
        if ($stmt_order->execute()) {
            // Lấy order_id vừa tạo
            $order_id = $conn->insert_id;

            // Bước 2: Lưu các sản phẩm vào bảng order_details
            foreach ($_SESSION['cart'] as $id => $item) {
                $menu_id = $id;
                $quantity = $item['quantity'];
                $price = $item['price'];
                $sugar_level = 1; // Mặc định mức đường, có thể thay đổi theo yêu cầu
                $ice_level = 1; // Mặc định mức đá, có thể thay đổi theo yêu cầu

                $sql_item = "INSERT INTO order_details (menu_id, order_id, quantity, price, sugar_level, ice_level) 
                         VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt_item = $conn->prepare($sql_item)) {
                    $stmt_item->bind_param("iiiiii", $menu_id, $order_id, $quantity, $price, $sugar_level, $ice_level);
                    $stmt_item->execute();
                } else {
                    echo "Lỗi khi chuẩn bị truy vấn chi tiết đơn hàng: " . $conn->error;
                }
            }

            // Bước 3: Cập nhật trạng thái bàn trong bảng tables
            $sql_update_table = "UPDATE tables SET status = 1 WHERE table_id = ?";
            if ($stmt_update = $conn->prepare($sql_update_table)) {
                $stmt_update->bind_param("i", $table_id);
                $stmt_update->execute();
            } else {
                echo "Lỗi khi cập nhật trạng thái bàn: " . $conn->error;
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            unset($_SESSION['cart']);
            echo "<script>alert('Đặt hàng thành công!'); window.location.href = 'http://localhost/quynh/component/home.php';</script>";
        } else {
            echo "Lỗi khi thực thi truy vấn đơn hàng: " . $conn->error;
        }
    } else {
        echo "Lỗi khi chuẩn bị truy vấn đơn hàng: " . $conn->error;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Document</title>
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
            padding: 0 20px;
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
    <div class="box">
        <div class="box1">
            <a href="http://localhost/quynh/component/home.php" class="icon"> <i class="fa-solid fa-arrow-left"></i></a>
            <div class="box11">
                <img src="../img/Remove-bg.ai_1729216392878.png" />
            </div>
            <div class="box12">
                <div class="text1">Lưu ý :</div>
                <ul>
                    <li>Sau khi đặt hàng sẽ có nhân viên liên hệ cho nhân viên xác nhận đơn hàng</li>
                    <li>Tùy vào số lượng đơn hàng mà thời gian chuẩn bị sẽ khác nhau</li>
                    <li>Quý khách vui lòng kiểm tra sản phẩm trước khi nhận hàng</li>
                </ul>
            </div>
        </div>
        <div class="box2">
            <div class="menu">
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <div class="menu-item">
                            <img src="<?php echo $row["img"] ?>" alt="<?php echo $row["alt_text"] ?>">
                            <div class="item-details">
                                <h3><?php echo $row["name"] ?></h3>
                                <p><?php echo $row["description"] ?></p>
                            </div>
                            <div class="item-price">
                                <p><?php echo $row["price"] ?>₫</p>
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $row['menu_id']; ?>">
                                    <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                                    <button type="submit" name="add_to_cart" class="add-btn">+</button>
                                </form>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
        <div class="box3">
            <div class="order-details">
                <h3>🔹 CHI TIẾT ĐƠN HÀNG</h3>
                <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $id => $item) { ?>
                        <div class="order-item">
                            <div class="item-info">
                                <p class="item-name"><?php echo $item['name']; ?></p>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="remove_item" class="remove-item">× Xóa</button>
                                </form>
                            </div>
                            <div class="item-quantity">
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="decrease_quantity" class="quantity-btn">-</button>
                                </form>
                                <p><?php echo $item['quantity']; ?></p>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="increase_quantity" class="quantity-btn">+</button>
                                </form>
                            </div>
                            <p class="item-price"><?php echo $item['price'] * $item['quantity']; ?>₫</p>
                        </div>
                <?php }
                } else {
                    echo "<p>Giỏ hàng của bạn đang trống.</p>";
                }
                ?>
                <div class="total-section">
                    <p>Tổng cộng:</p>
                    <p class="total-price"><?php echo $totalPrice; ?>₫</p>
                </div>
                <form action="" method="POST">
                    <button type="submit" name="place_order" class="order-btn">ĐẶT HÀNG</button>
                </form>
            </div>
        </div>
    </div>
    </div>
</body>

</html>