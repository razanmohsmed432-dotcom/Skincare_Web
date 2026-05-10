<?php
class Cart {
    private $conn;
    private $table_name = "cart";

    public $id;
    public $user_id;
    public $product_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    // إضافة منتج إلى السلة
    public function addToCart() {
        // التحقق إذا كان المنتج موجود بالفعل في السلة
        $check_query = "SELECT id, quantity FROM " . $this->table_name . " 
                       WHERE user_id = ? AND product_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $this->user_id);
        $check_stmt->bindParam(2, $this->product_id);
        $check_stmt->execute();

        if($check_stmt->rowCount() > 0) {
            // تحديث الكمية إذا كان المنتج موجود
            $row = $check_stmt->fetch(PDO::FETCH_ASSOC);
            $new_quantity = $row['quantity'] + $this->quantity;
            
            $update_query = "UPDATE " . $this->table_name . " 
                           SET quantity = ? WHERE id = ?";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(1, $new_quantity);
            $update_stmt->bindParam(2, $row['id']);
            return $update_stmt->execute();
        } else {
            // إضافة منتج جديد إلى السلة
            $query = "INSERT INTO " . $this->table_name . " 
                     (user_id, product_id, quantity) 
                     VALUES (?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->product_id);
            $stmt->bindParam(3, $this->quantity);
            
            return $stmt->execute();
        }
    }

    // جلب محتويات سلة المستخدم
    public function getCartItems() {
        $query = "SELECT c.*, p.name, p.price, p.image_url 
                 FROM " . $this->table_name . " c
                 JOIN products p ON c.product_id = p.id
                 WHERE c.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        
        return $stmt;
    }

    // إزالة منتج من السلة
    public function removeFromCart() {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE user_id = ? AND product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->product_id);
        
        return $stmt->execute();
    }

    // تحديث كمية المنتج في السلة
    public function updateQuantity() {
        $query = "UPDATE " . $this->table_name . " 
                 SET quantity = ? 
                 WHERE user_id = ? AND product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->quantity);
        $stmt->bindParam(2, $this->user_id);
        $stmt->bindParam(3, $this->product_id);
        
        return $stmt->execute();
    }
}
?>
