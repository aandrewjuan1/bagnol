<?php 

require_once 'dbConfig.php'; 

class HandleForms {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addCustomer($first_name, $last_name, $email, $phone_number, $address, $created_by) {
        $sql = "INSERT INTO Customers (first_name, last_name, email, phone_number, address, created_by, updated_by, created_at, updated_at) 
                VALUES (:first_name, :last_name, :email, :phone_number, :address, :created_by, :created_by, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':address' => $address,
            ':created_by' => $created_by  // Set both created_by and updated_by to current user
        ]);
    }

    // Function to delete a customer
    public function deleteCustomer($customer_id) {
        $sql = "DELETE FROM Customers WHERE customer_id = :customer_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':customer_id' => $customer_id]);
    }

    public function updateCustomer($customer_id, $first_name, $last_name, $email, $phone_number, $address, $updated_by) {
        $sql = "UPDATE Customers 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    phone_number = :phone_number, 
                    address = :address, 
                    updated_by = :updated_by,
                    updated_at = CURRENT_TIMESTAMP
                WHERE customer_id = :customer_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':customer_id' => $customer_id,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':address' => $address,
            ':updated_by' => $updated_by  // Use user_id of the currently logged-in user
        ]);
    }

    // Function to fetch all customers with creator and updater emails
    public function getAllCustomers() {
        $sql = "SELECT 
                    c.customer_id, 
                    c.first_name, 
                    c.last_name, 
                    c.email, 
                    c.phone_number, 
                    c.address, 
                    c.updated_at,
                    creator.email AS created_by_email,
                    updater.email AS updated_by_email
                FROM Customers c
                LEFT JOIN Users creator ON c.created_by = creator.user_id
                LEFT JOIN Users updater ON c.updated_by = updater.user_id";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to fetch a single customer by ID
    public function getCustomerById($customer_id) {
        $sql = "SELECT * FROM Customers WHERE customer_id = :customer_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':customer_id' => $customer_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
