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

// Fetch the customer ID from URL
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
if ($customer_id === null) {
    echo "No customer ID specified.";
    exit();
}

// Fetch customer data by ID
$customer = $handleForms->getCustomerById($customer_id);
if (!$customer) {
    echo "Customer not found.";
    exit();
}

// Fetch current logged-in user's email for the updated_by field
$current_user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT email FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $current_user_id]);
$current_user = $stmt->fetch(PDO::FETCH_ASSOC);
$updated_by_email = $current_user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated data from form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Update the customer data
    $handleForms->updateCustomer($customer_id, $first_name, $last_name, $email, $phone_number, $address, $current_user_id);

    // Redirect to the customer list page after updating
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <style>
        /* Add some styling for the form */
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Customer</h1>
        <form action="edit_customer.php?customer_id=<?php echo htmlspecialchars($customer['customer_id']); ?>" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($customer['phone_number']); ?>">

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($customer['address']); ?></textarea>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Update Customer</button>
                <a href="index.php" class="btn btn-danger" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
