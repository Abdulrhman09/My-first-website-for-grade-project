<?php
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
                 /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
//** اذا المستفيد طلب جهاز يحصل الاتي  **/
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $beneficiary = $_SESSION['user']['id'];
        $device      = $_POST['device'];
        $association = $_POST['association'];
        //** رفع التقرير الى قاعدة البيانات ورفعه الى الملفات  **/
        $targetDir   = 'uploads/files';
        $targetFile  =  $targetDir . '/' . time() . basename($_FILES['report']['name']);
        move_uploaded_file($_FILES['report']['tmp_name'],$targetFile);
        $report = $targetFile;
        //** جلب بيانات الطلبات   **/
        $sql = "INSERT INTO Requests (beneficiary, device, association, report) VALUES (?,?,?,?)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$beneficiary,$device,$association,$report]);
        $_SESSION['device_request'] = "ثم طلب الجهاز بنجاح";
        //** ارسال المستخدم الى صفحه طلباتي  **/
        header('Location: device_request.php');
        exit;
    }