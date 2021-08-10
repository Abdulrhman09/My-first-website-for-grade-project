<?php
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$pageTitle = 'طلباتي';
/**
 *التاكد من ان قاعدة البيانات متصله 
 */
require_once './database.php';
require_once './includes/header.php';


/**
 * جلب بيانات الطلبات ودمجها مع جدول الاجهزه
 */
$sql  = "SELECT devices.name , requests.status FROM requests INNER JOIN devices ON devices.id = requests.device WHERE requests.beneficiary = {$_SESSION['user']['id']}";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<main>
    <h4 class="text-center py-1 mt-2">الطلبات</h4>
    <div class="container">
        <div class="p-4">
            <?php/** شرط التاكد من حاله الجهاز **/ if(isset($_SESSION['device_request'])) : ?>
                <p class="alert alert-success text-center"><?=$_SESSION['device_request']?></p>
            <?php endif; 
                unset($_SESSION['device_request']);
            ?>
            <table class="table table-success table-striped">
                <thead>
                    <tr>
                        <th scope="col">الجهاز</th>
                        <th scope="col">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php/** لوب لوضع البيانات بالجدول **/ foreach ($requests as $request) : ?>
                        <tr>
                            <td><?= $request['name'] ?></td>
                            <td>
                                <?php/** شروط حاله الطلب اذا كانت 0 فهي تحت المراجعه واذا كانت 1 يعني تم القبول وذا كانت غير ذالك مرفوضه **/ if($request['status'] == 0) : ?>
                                    <span class="badge bg-warning text-dark">مراجعة الطلب</span>
                                <?php elseif($request['status'] == 1) : ?>
                                    <span class="badge bg-success">تم قبول الطلب</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">طلبك مرفوض</span>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
require_once './includes/footer.php';
?>