<?php 
/**
 * التاكد من دخول المستخدم اذا لم يسجل دخول يذهب الى صفحه تسجيل الدخول
 */
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit;
    }
    $pageTitle = 'صفحة تبرع الآن';
                 /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';

   //** جلب بيانات الجمعيات   **/
    $sql  = "SELECT * FROM users WHERE role = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $associations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //** اذا ادخل البيانات **/
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name   = $_POST['name'];
        $association  = $_POST['association'];
        $donor  = $_SESSION['user']['id'];
        $pics   = array(); 
        $total = count($_FILES['pics']['name']);

        /** هنا واجهت مشكله لعدم معرفتي براسال الصور الى الملفات وقاعده البيانات **/
        /** لوب لارسال الصوره الى الملفات وقاعده البيانات **/
        for( $i=0 ; $i < $total ; $i++ ) {
            $targetFile = "./uploads/devices/" . time() . $_FILES['pics']['name'][$i];
            move_uploaded_file($_FILES['pics']['tmp_name'][$i], $targetFile);
            array_push($pics,$targetFile);
        }
        $sql = "INSERT INTO Devices (name, association, donor) VALUES (?,?,?)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$name, $association, $donor]);
        $device = $pdo->lastInsertId();
        /** لوب لارسال البيانات بالجدول **/
        foreach($pics as $pic){
            $sql = "INSERT INTO Pics (device, pic) VALUES (?,?)";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$device, $pic]);
        }
        $success = "تم التبرع بنجاح";
    }
?>
<main>
    <h4 class="text-center py-1 mt-2">تبرع الآن</h4>
    <div class="w-50 mx-auto p-3">
        <?php if(isset($success)) : ?>
            <p class="alert alert-success text-center"><?=$success?></p>
        <?php endif; ?>
        <form class="form" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <input class="form-control" type="text"   placeholder="إسم الجهاز" name="name" required/>
            </div>
            <div class="mb-2">
                <select class="form-select" name="association">
                    <option value="-1" selected>-- الجمعية المتبرع لها --</option>
                    <?php/** لوب لعرض بيانات الجمعيه **/ foreach($associations as $association) : ?>
                        <option value="<?=$association['id']?>"><?=$association['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label" for="pic">صور الجهاز</label>
                <input class="form-control" type="file" name="pics[]" multiple required/>
            </div>
            <button class="btn btn-primary">تبرع الآن</button>
        </form>
    </div>
</main>
<?php 
    require_once './includes/footer.php';
?>