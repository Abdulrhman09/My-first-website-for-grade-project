<?php 
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit;
    }
    $pageTitle = 'الأجهزة المتوفرة';
       /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';
    $id = $_POST['id'];
    /**
     * جلب بيانات الاجهزه وربطها مع جدول المستخدمين
     */
    $sql  = "SELECT devices.id , devices.name , users.name AS 'association' , users.phone , users.city FROM devices 
    INNER JOIN users ON users.id = devices.association WHERE association = $id AND devices.status = '1'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <h4 class="text-center py-1 mt-2">الأجهزة المتوفرة</h4>
    <div class="container">
        <div class="p-4">
            <table class="table table-success table-striped">
                <thead>
                    <tr>
                        <td colspan="5" class="text-center">الأجهزة المتوفرة</td>
                    </tr>
                    <tr>
                        <th scope="col">إسم الجهاز</th>
                        <th scope="col">الجمعية</th>
                        <th scope="col">المدينة</th>
                        <th scope="col">الهاتف</th>
                        <th scope="col">العملية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php/** لوب لوضع البيانات بالجدول **/ foreach($devices as $device) : ?>
                        <tr>
                            <td><?= $device['name'] ?></td>
                            <td><?= $device['association'] ?></td>
                            <td><?= $device['city'] ?></td>
                            <td><?= $device['phone'] ?></td>
                            <td>
                                <form action="./device.php" method="post">
                                    <input type="text" name="id" value="<?=$device['id']?>" hidden>
                                    <button class="btn btn-success">عرض الجهاز</button>
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