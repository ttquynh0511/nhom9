<?php

include('../configs/database.php');


if (isset($_GET['table_id'])) {
    $table_id = $_GET['table_id'];
    $sql = "UPDATE tables
SET status=0
WHERE table_id=$table_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: http://localhost/nhom9/component/admin.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} else {
    echo "Không tìm thấy số bàn để xóa.";
}

// Đóng kết nối
mysqli_close($conn);
