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
    //** تحديث حاله المستفيد اذا تم تحديثها من قبل الجمعيه  **/
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $beneficiary = $_SESSION['user']['id'];
        $id      = $_POST['id'];
        $status      = $_POST['status'];
        $device      = $_POST['device'];
         //** تحديث حاله الطلب  **/
        $sql = "UPDATE Requests SET status=? WHERE id=?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$status,$id]);
         //** اذا كانت الحاله 1 يتم قبول الطلب **/
        if($status == 1) {
            $_SESSION['request_status'] = "تم قبول الطلب بنجاح";
            $sql = "UPDATE Devices SET devices.status = '0' WHERE id=$device";
            $stmt= $pdo->prepare($sql);
            $stmt->execute();
           
 //** رفض الطلب   **/
        }else{
            $_SESSION['request_status'] = "تم رفض الطلب ";
        }
        header('Location: requests.php');
        exit;
    }