<?php 
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'dbConfig.php';
require_once 'HandleForms.php'; 

// Initialize the HandleForms class with the PDO instance from dbConfig
$handleForms = new HandleForms($pdo);

// Fetch all customers
$customers = $handleForms->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affordable Furniture Management</title>
    <style>
        /* Styling (same as previous) */
    </style>
</head>
<body>
    <div class="container">
        <h1>Affordable Furniture Management System</h1>
        <div style="text-align: right;">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Add New Customer Button -->
        <div style="margin-bottom: 20px;">
            <a href="add_customer.php" class="btn btn-success">Add New Customer</a>
        </div>

        <!-- Display All Customers -->
        <h2>Customer List</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Created By</th>
                    <th>Last Updated By</th>
                    <th>Last Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($customers): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                            <td><?php echo htmlspecialchars($customer['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($customer['address']); ?></td>
                            <td><?php echo htmlspecialchars($customer['created_by_email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['updated_by_email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['updated_at']); ?></td>
                            <td>
                                <form action="edit_customer.php" method="GET" style="display: inline;">
                                    <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer['customer_id']); ?>">
                                    <button type="submit" class="btn">Edit</button>
                                </form>
                                <form action="delete_customer.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer['customer_id']); ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10" style="text-align: center;">No customers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

