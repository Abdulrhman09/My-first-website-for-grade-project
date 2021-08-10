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

    /**
     *  جلب بيانات الجمعيات الخيرية
     */
    $sql  = "SELECT * FROM users WHERE role = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $associations = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <th scope="col">الجمعية</th>
                        <th scope="col">المدينة</th>
                        <th scope="col">الهاتف</th>
                        <th scope="col">العملية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php/** لوب لوضع البيانات والصور بالجدول **/ foreach($associations as $association) : ?>
                        <tr>
                            <td><?= $association['name'] ?></td>
                            <td><?= $association['city'] ?></td>
                            <td><?= $association['phone'] ?></td>
                            <td>
                                <form action="./devices_associations.php" method="post">
                                    <input type="text" name="id" value="<?=$association['id']?>" hidden>
                                    <button class="btn btn-warning">عرض الأجهزة</button>
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