<?php
// buy_now.php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
include 'includes/header.php';

// Redirect if no product ID
if (!isset($_GET['id'])) {
    echo '<div class="container py-5 text-center">
            <div class="alert alert-warning">No product selected. <a href="index.php">Go back to shop</a></div>
          </div>';
    include 'includes/footer.php';
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch product details from database
$stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If product not found
if (!$product) {
    echo '<div class="container py-5 text-center">
            <div class="alert alert-danger">Product not found. <a href="index.php">Return to home</a></div>
          </div>';
    include 'includes/footer.php';
    exit;
}

// Fetch user details from session (if logged in)
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_phone = $_SESSION['user_phone'] ?? '';

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="mb-4 text-center fw-bold">Buy Now</h1>

            <?php
            if (isset($_SESSION['payment_success'])) {
                echo '<div class="alert alert-success text-center">'.$_SESSION['payment_success'].'</div>';
                unset($_SESSION['payment_success']);
            }
            ?>

            <div class="card border-0 shadow-sm p-4">
                <form action="buy_now_submit.php" method="post">
                    <!-- Hidden Product ID -->
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

                    <!-- Product Info -->
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control"
                               value="<?= htmlspecialchars($product['name']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="text" name="product_price" class="form-control"
                               value="<?= number_format((float)$product['price'], 2) ?>" readonly>
                    </div>

                    <!-- User Details (Auto-Filled) -->
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($user_name) ?>" <?= $user_name ? 'readonly' : 'required' ?>>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($user_email) ?>" <?= $user_email ? 'readonly' : 'required' ?>>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control"
                               value="<?= htmlspecialchars($user_phone) ?>" <?= $user_phone ? 'readonly' : 'required' ?>>
                    </div>

                    <!-- Payment Options -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Select Method</option>
                            <option value="upi">UPI</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="netbanking">Net Banking</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">Pay Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
