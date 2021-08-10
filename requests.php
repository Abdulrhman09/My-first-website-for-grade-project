<?php
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$pageTitle = 'طلبات الأجهزة المتوفرة';
/**
 *التاكد من ان قاعدة البيانات متصله 
 */
require_once './database.php';
require_once './includes/header.php';

/**
 * جلب بيانات الجمعيه
 */
function get_association($id){
    global $pdo;
    $sql  = "SELECT users.name FROM users WHERE users.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $association = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['name'];
    return $association;
}
/**
 * جلب بيانات المستخدم وجمعها مع بيانات الجهاز عن طريق inner join 
 */
$sql  = "SELECT requests.id , requests.device as 'device_id', requests.status , requests.report , requests.association , users.id AS 'user_id' , devices.name AS 'device' , users.name AS 'beneficiary' , devices.id AS 'device_id' FROM requests
INNER JOIN devices ON devices.id = requests.device
INNER JOIN users ON users.id = requests.beneficiary WHERE requests.status = 0 AND requests.association = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user']['id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<main>
    <h4 class="text-center py-1 mt-2">الطلبات</h4>
    <div class="container">
        <div class="p-4">
     
            <?php /** شرط التاكد من الحاله **/if (isset($_SESSION['request_status'])) : ?>
                <p class="alert alert-info text-center"><?=$_SESSION['request_status'] ?></p>
            <?php endif;
                unset($_SESSION['request_status']);
            ?>
            <table class="table table-success table-striped">
                <thead>
                    <tr>
                        <th scope="col">المستفيد</th>
                        <th scope="col">الجمعية</th>
                        <th scope="col">الجهاز</th>
                        <th scope="col">التقرير الطبي</th>
                        <th scope="col">العملية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php/** لوب لوضع البيانات والتقرير بالجدول **/ foreach ($requests as $request) : ?>
                        <tr>
                            <td><?= $request['beneficiary'] ?></td>
                            <td><?= get_association($request['association'])?></td>
                            <td><?= $request['device'] ?></td>
                            <td>
                                <a class="btn btn-primary" href="<?= $request['report'] ?>">عرض التقرير</a>
                            </td>
                            <td class="d-flex">
                                <form class="mx-2" action="./request_post.php" method="post">
                                    <input type="text" name="id" value="<?= $request['id'] ?>" hidden>
                                    <input type="text" name="status" value="1" hidden>
                                    <input type="text" name="device" value="<?= $request['device_id'] ?>" hidden>
                                    <button class="btn btn-success">قبول الطلب</button>
                                </form>
                                <form action="./request_post.php" method="post">
                                    <input type="text" name="id" value="<?= $request['id'] ?>" hidden>
                                    <input type="text" name="status" value="2" hidden>
                                    <button class="btn btn-danger">رفض الطلب</button>
                                </form>
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