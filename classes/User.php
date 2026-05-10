
<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // تسجيل مستخدم جديد
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET username=:username, email=:email, password=:password";
        
        $stmt = $this->conn->prepare($query);

        // تنظيف البيانات
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // تشفير كلمة المرور
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // ربط القيم
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // تسجيل الدخول
    public function login() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " 
                 WHERE username = :username";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                return true;
            }
        }
        return false;
    }

    // التحقق من وجود المستخدم
    public function userExists() {
        $query = "SELECT id FROM " . $this->table_name . " 
                 WHERE username = :username OR email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // قراءة بيانات مستخدم واحد
    public function readOne() {
        $query = "SELECT username, email, created_at FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }
}
?>
