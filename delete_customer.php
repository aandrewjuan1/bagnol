<?php
require_once 'dbConfig.php';
require_once 'HandleForms.php';

// Initialize the HandleForms class
$handleForms = new HandleForms($pdo);

// Get the customer ID from the query parameters
$customer_id = $_POST['customer_id'] ?? null;

if ($customer_id) {
    // Delete customer
    if ($handleForms->deleteCustomer($customer_id)) {
        // Redirect back to the index page or customer list after deletion
        header("Location: index.php?message=Customer+deleted+successfully");
        exit;
    } else {
        echo "Error deleting customer.";
    }
} else {
    echo "Invalid customer ID.";
}
?>
