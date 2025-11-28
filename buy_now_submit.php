<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data safely
    $product_id = (int)$_POST['product_id'];
    $product_name = htmlspecialchars($_POST['product_name']);
    $product_price = (float)$_POST['product_price'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    // Example: Save order in database (optional)
    /*
    $stmt = $pdo->prepare("INSERT INTO orders (product_id, name, email, phone, payment_method, amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$product_id, $name, $email, $phone, $payment_method, $product_price]);
    */

    // Simulate success
    $_SESSION['payment_success'] = "✅ Payment successful! Thank you, $name. Your order for '$product_name' has been received.";

    // Redirect back to the same product page with ID
    header("Location: buy_now.php?id=" . urlencode($product_id));
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
