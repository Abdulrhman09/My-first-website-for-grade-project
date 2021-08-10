<?php 
    $pageTitle = 'صفحة التسجيل';
                 /**
 *التاكد من ان قاعدة البيانات متصله 
 */
    require_once './database.php';
    require_once './includes/header.php';

    /**
     * جلب بيانات الأدوار 
     */
    $sql  = "SELECT * FROM roles";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * ارسال بيانات المستخدم الى قاعدة البيانات
     */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name   = $_POST['name'];
        $phone  = $_POST['phone'];
        $email  = $_POST['email'];
        $pwd  = $_POST['pwd'];
        $city   = $_POST['city'];
        $role   = $_POST['role'];
        $pic    = null;
        //** التاكد من مساحه الصوره اذا كانت موجوده  **/
        if($_FILES['pic']['size'] > 0){
            $targetDir  = 'uploads/users';
            $targetFile =  $targetDir . '/' . time() . basename($_FILES['pic']['name']);
            move_uploaded_file($_FILES['pic']['tmp_name'],$targetFile);
            $pic = $targetFile;
        }
        //**  ارسال جميع البيانات الى جدول المستخدمين  **/
        $sql = "INSERT INTO Users (name, email, password, city, phone, pic, role) VALUES (?,?,?,?,?,?,?)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$name, $email, $pwd, $city, $phone, $pic, $role]);
        $success = "تم إنشاء الحساب بنجاج";
    }
?>
<main>
    <h4 class="text-center py-1 mt-2">تسجل معنا</h4>
    <div class="w-50 mx-auto p-3">
        <?php if(isset($success)) : ?>
            <p class="alert alert-success text-center"><?=$success?></p>
        <?php endif; ?>
        <form class="form" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input class="form-control" type="text"   placeholder="الإسم" name="name" required/>
            </div>
            <div class="mb-3">
                <input class="form-control" type="number" placeholder="رقم الهاتف" name="phone" required/>
            </div>
            <div class="mb-3">
                <input class="form-control" type="email"  placeholder="البريد الإلكتروني" name="email" required/>
            </div>
            <div class="mb-3">
                <input class="form-control" type="password"  placeholder="الرمز السري" name="pwd" required/>
            </div>
            <div class="mb-3">
                <input class="form-control" type="city"   placeholder="المدينة" name="city" required/>
            </div>
            <div class="mb-3">
                <select class="form-select" name="role">
                    <option value="-1" selected>-- المرجو إختيار دورك في الموقع --</option>
                    <?php /* دور المستخدم   */foreach($roles as $role) : ?>
                        <option value="<?=$role['id']?>"><?=$role['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="pic">الصورة</label>
                <input class="form-control" type="file" name="pic"/>
            </div>
            <button class="btn btn-primary">التسجيل</button>
        </form>
    </div>
</main>
<?php 
    require_once './includes/footer.php'
?>