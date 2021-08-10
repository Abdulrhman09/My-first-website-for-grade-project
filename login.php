<?php 
    session_start();
    $pageTitle = 'صفحة الدخول';
                 /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';

  //** اذا المستخدم ادخل البيانات  **/
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email   = $_POST['email'];
        $pwd = $_POST['pwd'];
        //** التاكد من المدخلات اذا كانت موجوده في قاعده البيانات   **/
        $sql = "SELECT * FROM Users WHERE email = ? AND password = ?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$email, $pwd]);
        if($stmt->rowCount() > 0){
            $_SESSION['user'] = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            //** تحويل المستخدم الى صفحه الرئيسيه   **/
            header("Location: index.php");
            exit;
        }else{
            $error = "لقد أدخلت المعلومات خاطئة";
        }
    }
?>
<main>
    <h4 class="text-center py-1 mt-2">تسجيل الدخول</h4>
        <div class="w-50 mx-auto p-3">
            <?php if(isset($error)) : ?>
                <p class="alert alert-warning text-center"><?=$error?></p>
            <?php endif; ?>
            <form class="form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                <div class="mb-2">
                    <input class="form-control" type="email"  placeholder="البريد الإلكتروني" name="email" required/>
                </div>
                <div class="mb-2">
                    <input class="form-control" type="password"   placeholder="الرمز السري" name="pwd" required/>
                </div>
                <button class="btn btn-primary">الدخول</button>
            </form>
        </div>
</main>
<?php 
    require_once './includes/footer.php'
?>