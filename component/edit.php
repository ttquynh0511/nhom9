<?php

include('../configs/database.php');

if (isset($_GET['table_num'])) {
    $table_num = $_GET['table_num'];

    $sql = "SELECT * FROM tables WHERE table_num = '$table_num'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Không tìm thấy bàn này.";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_table_num = $_POST['table_num'];
    $seats = $_POST['seats'];
    $reservation_time = $_POST['reservation_time'];

    $sql = "UPDATE tables SET table_num='$new_table_num', seats='$seats', reservation_timestamp='$reservation_time' WHERE table_num='$table_num'";

    if (mysqli_query($conn, $sql)) {
        header("Location: http://localhost/quynh/component/admin.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Bàn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .cancel-button {
            margin-top: 10px;
            text-align: center;
        }

        .cancel-button a {
            text-decoration: none;
            color: #007bff;
        }

        .cancel-button a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="form">
        <h1>Sửa Thông Tin Bàn</h1>

        <form method="POST">
            <label for="table_num">Số Bàn:</label>
            <input type="text" id="table_num" name="table_num" value="<?php echo $row['table_num']; ?>" required><br><br>

            <label for="seats">Số Người:</label>
            <input type="text" id="seats" name="seats" value="<?php echo $row['seats']; ?>" required><br><br>

            <label for="reservation_time">Thời Gian Đặt:</label>
            <input type="text" id="reservation_time" name="reservation_time" value="<?php echo $row['reservation_timestamp']; ?>" required><br><br>

            <input type="submit" value="Cập Nhật">
        </form>
    </div>


</body>

</html>