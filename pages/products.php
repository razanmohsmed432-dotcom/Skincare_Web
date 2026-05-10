<?php
session_start();
include_once '../classes/Database.php';
include_once '../classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// البحث
$search_results = null;
if(isset($_GET['search'])) {
    $keywords = $_GET['search'];
    $search_results = $product->search($keywords);
} else {
    $products = $product->readAll();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جميع المنتجات</title>

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

        /* بحث */
        form input[type="text"] {
            width: 70%;
            padding: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form .btn {
            padding: 0.7rem 1.5rem;
            background-color: #667eea;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form .btn:hover {
            background-color: #764ba2;
        }

        /* كروت المنتجات */
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

        .product-card p {
            color: #555;
            margin-bottom: 0.5rem;
        }

        .price {
            color: #667eea;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .stock {
            color: #444;
            margin-bottom: 0.7rem;
        }

        .btn {
            background-color: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
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
        <h1>منتجات العناية بالبشرة</h1>
    </div>

    <div class="nav">
        <a href="../index.php">الرئيسية</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="cart.php">عربة التسوق</a>
            <a href="profile.php">الملف الشخصي</a>
            <a href="logout.php">تسجيل الخروج</a>
        <?php else: ?>
            <a href="login.php">تسجيل الدخول</a>
            <a href="register.php">إنشاء حساب</a>
        <?php endif; ?>
    </div>

    <div class="container">

        <!-- نموذج البحث -->
        <form method="GET" style="margin-bottom: 2rem; text-align: center;">
            <input type="text" name="search" placeholder="ابحث في المنتجات..."
                   value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="btn">بحث</button>
        </form>

        <div class="products-grid">
            <?php 
            $products_to_show = isset($search_results) ? $search_results : $products;

            while($row = $products_to_show->fetch(PDO::FETCH_ASSOC)): 
            ?>
            <div class="product-card">
                <img src="../img/<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>

                <div class="price"><?php echo $row['price']; ?> ريال</div>
                <div class="stock">المخزون: <?php echo $row['stock']; ?></div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="cart.php?add_to_cart=<?php echo $row['id']; ?>" class="btn">إضافة إلى العربة</a>
                <?php else: ?>
                    <a href="login.php" class="btn">تسجيل الدخول للشراء</a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>

    </div>

</body>
</html>
