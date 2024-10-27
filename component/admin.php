<?php

include('../configs/database.php');

$sql = "SELECT * FROM tables WHERE status=1";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Document</title>
    <style>
        .admin_box {
            display: flex;

        }

        .admin_box1 {
            display: flex;
            width: 300px;
            height: 1080px;
            border-right: 2px solid black;
        }

        .admin_box2 {
            width: 1200px;
            margin: 0 auto;
            margin-top: 30px;
        }

        .h1_box {
            padding-bottom: 20px;
            margin-left: 80px;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons button {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        }

        .action-buttons .edit {
            background-color: #4CAF50;
            color: white;
        }

        .action-buttons .delete {
            background-color: #f44336;
            color: white;
        }

        .icon i {
            font-size: 25px;
            margin-top: 30px;
            color: black;
        }
    </style>
</head>

<body>
    <div class="admin_box">
        <div class="admin_box1">
            <a href="http://localhost/quynh/component/ho1me.php" class="icon"> <i class="fa-solid fa-arrow-left"></i></a>
            <h1 class="h1_box">Table</h1>
        </div>
        <div class="admin_box2">
            <table>
                <thead>
                    <tr>
                        <th>Thứ Tự</th>
                        <th>Số Bàn</th>
                        <th>Số Người</th>
                        <th>Thời Gian Đặt</th>
                        <th>Chức Năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $index = 1;
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $index ?></td>
                                <td><?php echo $row["table_num"] ?></td>
                                <td><?php echo $row["seats"] ?></td>
                                <td><?php echo $row["reservation_timestamp"] ?></td>
                                <td class="action-buttons">
                                    <a href="http://localhost/quynh/component/edit.php?table_id=<?php echo $row['table_id']; ?>">
                                        <button class="edit">Sửa</button>
                                    </a>
                                    <a href="http://localhost/quynh/component/delete.php?table_id=<?php echo $row['table_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa bàn này?')">
                                        <button class="delete">Xóa</button>
                                    </a>
                                </td>
                            </tr>
                    <?php
                            $index++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>