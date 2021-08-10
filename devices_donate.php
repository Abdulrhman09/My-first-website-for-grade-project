<?php 
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit;
    }
    $pageTitle = 'الأجهزة المتبرع بها';
         /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';
    /**
     * فنكشن لجلب بيانات الصور من جدول الصور
     */
    function get_pics($id){
        global $pdo;
        $sql  = "SELECT pic FROM pics WHERE device = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * جلب بيانات الجهاز 
     */
    $sql  = "SELECT devices.id , devices.donor , devices.name FROM devices WHERE devices.donor = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user']['id']]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main>
    <h4 class="text-center py-1 mt-2">الأجهزة المتبرع بها</h4>
    <div class="container">
        <div class="p-4">
            <table class="table table-success table-striped">
                <thead>
                    <tr>
                        <td colspan="5" class="text-center">الأجهزة المتبرع بها</td>
                    </tr>
                    <tr>
                        <th scope="col">إسم الجهاز</th>
                        <th scope="col">صور الجهاز</th>
                    </tr>
                </thead>
                <tbody>
                    <?php/** لوب لوضع البيانات والصور بالجدول **/ foreach($devices as $device) : ?>
                        <tr>
                            <td><?= $device['name'] ?></td>
                            <td>
                               <?php foreach(get_pics($device['id']) as $pic) : ?>
                                <img src="<?=$pic['pic']?>" width="64" height="64">
                               <?php endforeach;?>
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