<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once '../classes/Database.php';
include_once '../classes/Cart.php';
include_once '../classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);
$product = new Product($db);

$cart->user_id = $_SESSION['user_id'];

// إضافة منتج إلى العربة
if(isset($_GET['add_to_cart'])) {
    $cart->product_id = $_GET['add_to_cart'];
    $cart->quantity = 1;
    $cart->addToCart();
    header("Location: cart.php");
    exit;
}

// إزالة منتج من العربة
if(isset($_GET['remove_from_cart'])) {
    $cart->product_id = $_GET['remove_from_cart'];
    $cart->removeFromCart();
    header("Location: cart.php");
    exit;
}

// تحديث الكمية
if(isset($_POST['update_quantity'])) {
    $cart->product_id = $_POST['product_id'];
    $cart->quantity = $_POST['quantity'];
    $cart->updateQuantity();
    header("Location: cart.php");
    exit;
}

$cart_items = $cart->getCartItems();
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عربة التسوق</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            text-align: right;
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
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem;
        }

        .cart-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 5px;
            margin-left: 1rem;
        }

        .item-details {
            flex-grow: 1;
            padding-right: 1rem;
        }

        .item-details h3 {
            margin-bottom: 0.3rem;
            color: #333;
        }

        .price {
            color: #667eea;
            font-weight: bold;
        }

        /* التعديل المهم هنا */
        .quantity-controls {
            display: flex;
            flex-direction: column; /* يجعل الأزرار تحت بعض */
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-controls input {
            width: 60px;
            padding: 0.3rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .btn {
            background-color: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #764ba2;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
        }

        .total-price {
            text-align: left;
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #667eea;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>عربة التسوق</h1>
    </div>

    <div class="nav">
        <a href="../index.php">الرئيسية</a>
        <a href="products.php">المنتجات</a>
        <a href="profile.php">الملف الشخصي</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>

    <div class="container">
        <div class="cart-container">

            <?php if($cart_items->rowCount() > 0): ?>

                <?php while($item = $cart_items->fetch(PDO::FETCH_ASSOC)): 
                    $item_total = $item['price'] * $item['quantity'];
                    $total_price += $item_total;
                ?>
                <div class="cart-item">
                    <img src="../img/<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">

                    <div class="item-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <div class="price"><?php echo $item['price']; ?> ريال</div>
                    </div>

                    <div class="quantity-controls">
                        <!-- تحديث -->
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="number" name="quantity" min="1" max="10" value="<?php echo $item['quantity']; ?>">
                            <button type="submit" name="update_quantity" class="btn">تحديث</button>
                        </form>

                        <!-- إزالة -->
                        <a href="cart.php?remove_from_cart=<?php echo $item['product_id']; ?>" class="btn btn-danger">
                            إزالة
                        </a>
                    </div>

                    <div style="margin-right: 1rem; font-weight: bold;">
                        <?php echo $item_total; ?> ريال
                    </div>
                </div>
                <?php endwhile; ?>

                <div class="total-price">
                    المجموع الكلي: <?php echo $total_price; ?> ريال
                </div>

                <div style="text-align: left; margin-top: 2rem;">
                    <button class="btn" style="padding: 1rem 2rem; font-size: 1.2rem;">
                        إتمام الشراء
                    </button>
                </div>

            <?php else: ?>

                <div class="empty-cart">
                    عربة التسوق فارغة
                </div>

                <div style="text-align: center;">
                    <a href="products.php" class="btn">تصفح المنتجات</a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</body>
</html>
