<?php

include('../configs/database.php');


if (isset($_GET['table_id'])) {
    $table_id = $_GET['table_id'];
    $sql = "DELETE FROM tables WHERE table_id = '$table_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: http://localhost/quynh/component/home.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} else {
    echo "Không tìm thấy số bàn để xóa.";
}

// Đóng kết nối
mysqli_close($conn);
