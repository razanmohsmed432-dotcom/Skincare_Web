<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

include_once '../classes/Database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = "";
$success = "";

if($_POST) {
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($user->password !== $confirm_password) {
        $error = "كلمات المرور غير متطابقة";
    } elseif($user->userExists()) {
        $error = "اسم المستخدم أو البريد الإلكتروني موجود مسبقاً";
    } else {
        if($user->register()) {
            $success = "تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن.";
        } else {
            $error = "حدث خطأ أثناء إنشاء الحساب";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .nav {
            background-color: #333;
            padding: 1rem;
        }

        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .nav a:hover {
            background-color: #555;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .register-form {
            max-width: 450px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #764ba2;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }

        .success {
            color: green;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }

        .register-form p {
            margin-top: 1rem;
            text-align: center;
        }

        .register-form a {
            color: #667eea;
            text-decoration: none;
        }

        .register-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>إنشاء حساب جديد</h1>
    </div>

    <div class="nav">
        <a href="../index.php">الرئيسية</a>
        <a href="login.php">تسجيل الدخول</a>
    </div>

    <div class="container">
        <div class="register-form">
            
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>اسم المستخدم:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور:</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn">إنشاء الحساب</button>
            </form>

            <p>لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a></p>
        </div>
    </div>

</body>
</html>
