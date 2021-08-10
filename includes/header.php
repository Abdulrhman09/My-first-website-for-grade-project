<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="./assets/bootstrap.css">
    <link rel="stylesheet" href="./assets/main.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-info">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar1" aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar1">
                    <ul class="navbar-nav mx-auto py-2">
                        <li class="nav-item"><a href="./index.php" class="nav-link">الصفحة الرئيسية</a></li>
                        <?php if(!isset($_SESSION['user'])) : ?>
                            <li class="nav-item"><a href="./login.php" class="nav-link">تسجيل الدخول</a></li>
                            <li class="nav-item"><a href="./register.php" class="nav-link">إنشاء حساب</a></li>
                        <?php else : 
                            $user_role = $_SESSION['user']['role'];
                        ?>
                            
                            <!-- الجمعية الخيرية -->
                            <?php if($user_role == 1) : ?>
                                <li class="nav-item"><a href="./requests.php" class="nav-link">الطلبات</a></li>
                                <li class="nav-item"><a href="./devices_donated.php" class="nav-link">الأجهزة المتبرع بها</a></li>
                            <?php endif; ?>
                            <!-- المتبرع -->
                            <?php if($user_role == 2) : ?>
                                <li class="nav-item"><a href="./donate.php" class="nav-link">تبرع الآن</a></li>
                                <li class="nav-item"><a href="./devices_donate.php" class="nav-link">الأجهزة المتبرع بها</a></li>
                            <?php endif;?>  
                            <!-- المستفيد -->
                            <?php if($user_role == 3) : ?>
                                <li class="nav-item"><a href="./devices.php" class="nav-link">الأجهزة المتوفرة</a></li>
                                <li class="nav-item"><a href="./device_request.php" class="nav-link">طلباتي</a></li>
                            <?php endif;?>
                            <li class="nav-item"><a href="./logout.php" class="nav-link">الخروج</a></li>
                            <!-- صورة -->
                            <li class="nav-item">
                                <?php if($_SESSION['user']['pic'] != null) : ?>
                                <a href="./index.php" class="nav-link">
                                    <img  src="<?=$_SESSION['user']['pic']?>" width="32" height="32" />
                                </a>
                                <?php endif; ?>
                            </li>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>