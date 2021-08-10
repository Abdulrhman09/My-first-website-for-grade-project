<?php 
    session_start();
    $pageTitle = 'صفحة الدخول';
                 /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';

 //** اذا المستخدم ادخل بيانات **/
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email   = $_POST['email'];
        $pwd = $_POST['pwd'];
        //** التاكد من وجود البيانات في قاعده البيانت   **/
        $sql = "SELECT * FROM Users WHERE email = ? AND password = ?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$email, $pwd]);
        //** اذا المعلومات صحيحه يتم تسجيل الدخول   **/
        if($stmt->rowCount() > 0){
            $_SESSION['user'] = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            header("Location: index.php");
            exit;
        }else{
            $error = "لقد أدخلت المعلومات خاطئة";
        }
    }
?>
<main>
    <div class="bg-image" style="background-image: url('./assets/images/bg.png')">
        <h1 class="pb-2">موقع للتبرع بالأجهزة</h1>
        <p>
        &#9789;
            وَمَنْ أَحْيَاهَا فَكَأَنَّمَا أَحْيَا النَّاسَ جَمِيعًا
        &#9790;
        </p>
        <div class="cover"></div>
    </div>
</main>
<?php 
    require_once './includes/footer.php'
?>