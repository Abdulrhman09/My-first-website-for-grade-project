<?php 
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit;
    }
    $pageTitle = 'عرض الجهاز';
    /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';
    $id = $_POST['id'];

    /**
     * جلب بيانات الاجهزه ودمجها مع جدول الصور 
     */
    $sql  = "SELECT devices.name , users.id AS 'association' , users.city AS 'city' , pics.pic FROM devices 
    INNER JOIN pics 
    ON devices.id = pics.device
    INNER JOIN users
    ON users.id = devices.association
    WHERE devices.id = $id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $device = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <h4 class="text-center py-1 mt-2">
        جهاز :
        <?=$device[0]['name']?>
    </h4>
    <div class="container">
        <div class="p-4">
            <div class="row">
                <?php/** لوب لوضع البيانات والصور بالجدول **/ foreach($device as $pic) : ?>
                    <div class="col-md-3">
                        <img class="rounded d-block w-75 mx-auto" src="<?=$pic['pic']?>" height="250">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <form action="./request.php" method="post" enctype="multipart/form-data">
            <input type="text" name="device" value="<?=$id?>" hidden>   
            <input type="text" name="association" value="<?=$device[0]['association']?>" hidden> 
            <label for="report" class="form-label d-block text-center">التقرير الطبي</label>
            <input type="file" name="report" required class="form-control w-50 d-block mx-auto mb-2">  
            <button class="btn btn-primary w-50 d-block mx-auto mb-2">طلب الجهاز</button>
        </form>
    </div>
</main>
<?php 
    require_once './includes/footer.php';
?>