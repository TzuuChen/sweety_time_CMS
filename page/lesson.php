<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

$whereClause = "WHERE activation = 1";
//teacher
$sqlTeacher = "SELECT * FROM teacher ORDER BY teacher_id";
$resultTea = $conn->query($sqlTeacher);
$rowsTea = $resultTea->fetch_all(MYSQLI_ASSOC);

//關聯式陣列
$teacherArr = [];
foreach ($rowsTea as $teacher) {
    $teacherArr[$teacher["teacher_id"]] = $teacher["name"];
}

//分類
$sqlProductClass = "SELECT * FROM product_class ORDER BY product_class_id";
$resultPro = $conn->query($sqlProductClass);
$rowsPro = $resultPro->fetch_all(MYSQLI_ASSOC);

//關聯式陣列
$productClassArr = [];
foreach ($rowsPro as $productClass) {
    $productClassArr[$productClass["product_class_id"]] = $productClass["class_name"];
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Lesson</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <ul class="nav nav-tabs-custom">
                <li class="nav-item">
                    <a class="main-nav nav-link active" aria-current="page" href="lesson.php">全部</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link" href="lesson-online.php">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link" href="lesson-delete.php">已下架</a>
                </li>
            </ul>
            <!-- Content -->
            <table class="table table-hover">
                <thead class="text-center">
                    <th>課程編號</th>
                    <th>課程狀態</th>
                    <th>課程名稱</th>
                    <th>課程分類</th>
                    <th>授課老師</th>
                    <th>課程人數</th>
                    <th>報名人數</th>
                    <th>詳細資訊</th>
                </thead>
                <?php foreach ($rows as $row):
                    $id = $row["lesson_id"]; ?>
                    <tbody>
                        <tr class="text-center m-auto">
                            <td><?= $id ?></td>
                            <?php echo ($row["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                            <td><?= $row["name"] ?></td>
                            <td><?= $productClassArr[$row["product_class_id"]] ?></td>
                            <td><?= $teacherArr[$row["teacher_id"]] ?></td>
                            <td><?= $row["quota"] ?></td>
                            <td>
                                <?php
                                $sqlStudent = "SELECT * FROM student WHERE lesson_id = $id";
                                $resultStu = $conn->query($sqlStudent);
                                $count = $resultStu->num_rows;
                                $rowStu = $resultStu->fetch_assoc();
                                ?>
                                <?= $count ?></td>
                            <td><a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-eye"></i></i></a>
                                <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>
                                <a href="doDeleteLesson.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php include("../js.php") ?>
    <?php $conn->close() ?>
</body>

</html>