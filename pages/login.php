<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location:../index.php");
    exit;
}

include_once '../classes/Database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = "";
if($_POST) {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    
    if($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header("Location: ../index.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>

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

        .login-form {
            max-width: 400px;
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

        .login-form p {
            margin-top: 1rem;
            text-align: center;
        }

        .login-form a {
            color: #667eea;
            text-decoration: none;
        }

        .login-form a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="header">
        <h1>تسجيل الدخول</h1>
    </div>

    <div class="nav">
        <a href="../index.php">الرئيسية</a>
        <a href="register.php">إنشاء حساب</a>
    </div>

    <div class="container">
        <div class="login-form">
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>اسم المستخدم:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn">تسجيل الدخول</button>
            </form>

            <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
        </div>
    </div>

</body>
</html>
