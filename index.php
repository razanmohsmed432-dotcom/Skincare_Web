<?php
session_start();
include_once 'classes/Database.php';
include_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$products = $product->readAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر العناية بالبشرة</title>
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
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-card h3 {
            margin: 1rem 0;
            color: #333;
        }
        .price {
            color: #667eea;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 1rem 0;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🌷 متجر العناية بالبشرة 🌷</h1>
        <p>أفضل مستحضرات العناية بالبشرة الطبيعية</p>
    </div>

    <div class="nav">
        <a href="index.php">الرئيسية</a>
        <a href="pages/products.php">المنتجات</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="pages/cart.php">عربة التسوق</a>
            <a href="pages/profile.php">الملف الشخصي</a>
            <a href="pages/logout.php">تسجيل الخروج</a>
        <?php else: ?>
            <a href="pages/login.php">تسجيل الدخول</a>
            <a href="pages/register.php">إنشاء حساب</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h2>أحدث المنتجات</h2>
        <div class="products-grid">
            <?php while($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="product-card">
                <img src="img/<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <div class="price"><?php echo $row['price']; ?> ريال</div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="pages/cart.php?add_to_cart=<?php echo $row['id']; ?>" class="btn">إضافة إلى العربة</a>
                <?php else: ?>
                    <a href="pages/login.php" class="btn">تسجيل الدخول للشراء</a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

