<?php

include('../configs/database.php');


if (isset($_GET['table_num'])) {
    $table_num = $_GET['table_num'];
    $sql = "DELETE FROM tables WHERE table_num = '$table_num'";

    if (mysqli_query($conn, $sql)) {
        header("Location: http://localhost/quynh/component/admin.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} else {
    echo "Không tìm thấy số bàn để xóa.";
}

// Đóng kết nối
mysqli_close($conn);
