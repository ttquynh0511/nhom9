<?php

include('../configs/database.php'); // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $table = $_POST['table'];
    $guests = $_POST['guests'];

    // Kiểm tra xem số bàn đã tồn tại trong cơ sở dữ liệu chưa
    $check_sql = "SELECT * FROM tables WHERE table_num = '$table' AND status = 1";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Nếu số bàn đã tồn tại
        echo "<script>alert('Số bàn này đã được đặt trước. Vui lòng chọn số bàn khác.');</script>";
    } else {
        // Thực hiện thêm mới nếu số bàn chưa tồn tại
        $sql = "INSERT INTO tables (table_num, seats) VALUES ('$table', '$guests')";

        if (mysqli_query($conn, $sql)) {
            $table_id = mysqli_insert_id($conn);
            header("Location: http://localhost/nhom9/component/page.php?table_id=" . urlencode($table_id));
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <style>
        .reservation-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 600px;
            margin: auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #f9f9f9;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            width: 100%;
            transition: border-color 0.3s ease;
            border: 1px solid black;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
        }

        .reservation-form .form-group:nth-child(odd) {
            grid-column: 1;
        }

        .reservation-form .form-group:nth-child(even) {
            grid-column: 2;
        }


        .main {
            width: 900px;
            height: 400px;
            margin: 0 auto;
            background-color: #ccc;
            opacity: 0.8;
            position: fixed;
            top: 280px;
            left: 500px;
            border-radius: 15px;
            border: 1px solid black;
        }

        .title1,
        .title2 {
            text-align: center;
        }

        .title1 {
            font-size: 40px;
            font-weight: bold;
            margin-top: 20px;
        }

        .title2 {
            margin-top: 10px;
            font-weight: bold;
        }

        .reservation-form {
            margin-top: 30px;
        }

        #soluong,
        #thoi {
            margin-left: 20px;
        }

        body {
            background-image: url(../img/nen22.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
        }

        .submit-button {
            grid-column: 1 / span 2;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            margin-left: 10px;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

        .page {
            display: flex;
        }

        .admin,
        .user {
            width: 50px;
            height: 20px;
            background-color: #ccc;
            text-align: center;

        }

        .user {
            border-left: 3px solid black;
            border-bottom-right-radius: 5px;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="admin"><a href="http://localhost/nhom9/component/admin.php">Admin</a></div>
        <div class="user"><a href="http://localhost/nhom9/component/home.php">User</a></div>
    </div>
    <div class="main">
        <div class="title">
            <div class="title1">Đặt Bàn<br /></div>
            <div class="title2">Đặt bàn ngay,nhận ưu đãi liền tay</div>
        </div>
        <form class="reservation-form" id="reservationForm" method="POST">
            <div class="form-group">
                <label for="location">Số bàn</label>
                <input list="locations" id="location" name="table" placeholder="Chọn hoặc nhập số bàn">
                <datalist id="locations">
                    <option value="1">
                    <option value="2">
                    <option value="3">
                    <option value="4">
                    <option value="5">
                    <option value="6">
                    <option value="7">
                    <option value="8">
                    <option value="9">
                    <option value="10">
                </datalist>
                <small id="locationError" style="color: red; display: none;">Số bàn phải từ 1 đến 10</small>
            </div>

            <div class="form-group" id="soluong">
                <label for="guests">Số lượng khách</label>
                <input list="guestNumbers" id="guests" name="guests" placeholder="Chọn hoặc nhập số lượng">
                <datalist id="guestNumbers">
                    <option value="1 người">
                    <option value="2 người">
                    <option value="3 người">
                    <option value="4 người">
                    <option value="5 người">
                </datalist>
                <small id="guestsError" style="color: red; display: none;">Số lượng khách phải từ 1 đến 5</small>
            </div>
            <button type="submit" class="submit-button">Đặt Bàn Ngay</button>
        </form>
    </div>

    <script>
        document.getElementById('reservationForm').addEventListener('submit', function(event) {
            let valid = true;

            const locationInput = document.getElementById('location').value;
            const guestsInput = document.getElementById('guests').value;

            // Validate table number (between 1 and 10)
            const locationError = document.getElementById('locationError');
            if (locationInput < 1 || locationInput > 10 || isNaN(locationInput)) {
                locationError.style.display = 'block';
                valid = false;
            } else {
                locationError.style.display = 'none';
            }

            // Validate guests number (between 1 and 5)
            const guestsError = document.getElementById('guestsError');
            const guestCount = parseInt(guestsInput);
            if (guestCount < 1 || guestCount > 5 || isNaN(guestCount)) {
                guestsError.style.display = 'block';
                valid = false;
            } else {
                guestsError.style.display = 'none';
            }

            if (!valid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    </script>
</body>

</html>