<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $image_url;
    public $stock;

    public function __construct($db) {
        $this->conn = $db;
    }

    // جلب جميع المنتجات
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // جلب منتج بواسطة ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->category = $row['category'];
            $this->image_url = $row['image_url'];
            $this->stock = $row['stock'];
            return true;
        }
        return false;
    }

    // البحث في المنتجات
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE name LIKE ? OR description LIKE ? OR category LIKE ?";
        
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->execute();

        return $stmt;
    }
}
?>

