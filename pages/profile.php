<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once '../classes/Database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$user->id = $_SESSION['user_id'];
$user->readOne();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي</title>
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
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .profile-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .profile-info > div {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .btn {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #764ba2;
        }
        .logout-btn {
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>الملف الشخصي</h1>
    </div>

    <div class="nav">
        <a href="../index.php">الرئيسية</a>
        <a href="products.php">المنتجات</a>
        <a href="cart.php">عربة التسوق</a>
    </div>

    <div class="container">
        <div class="profile-container">
            <h2>مرحباً <?php echo $_SESSION['username']; ?>!</h2>
            
            <div class="profile-info" style="margin-top: 2rem;">
                <div>
                    <strong>اسم المستخدم:</strong> <?php echo $_SESSION['username']; ?>
                </div>
                <div>
                    <strong>البريد الإلكتروني:</strong> <?php echo $user->email; ?>
                </div>
                <div>
                    <strong>تاريخ الإنشاء:</strong> <?php echo $user->created_at; ?>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <a href="logout.php" class="btn logout-btn">تسجيل الخروج</a>
            </div>
        </div>
    </div>
</body>
</html>
