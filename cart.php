<?php
session_start();
include 'includes/db.php'; // Use your real PDO connection

// Initialize cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Migrate old cart format if necessary
if (!empty($_SESSION['cart']) && isset($_SESSION['cart'][0]['id']) === false) {
    $oldCart = $_SESSION['cart'];
    $_SESSION['cart'] = [];
    foreach ($oldCart as $pid => $qty) {
        $_SESSION['cart'][] = ['id' => $pid, 'qty' => $qty];
    }
}

// Handle Add / Remove
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);

    if ($action === 'add' && $id > 0) {
        $_SESSION['cart'][] = ['id' => $id, 'qty' => $qty];
        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart'])
        ]);
        exit;
    }

    if ($action === 'remove' && isset($_POST['index'])) {
        $index = intval($_POST['index']);
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        header('Location: cart.php');
        exit;
    }
}

// --- Fetch product details for all IDs in the cart ---
$productDetails = [];
if (!empty($_SESSION['cart'])) {
    $ids = array_column($_SESSION['cart'], 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productDetails = $stmt->fetchAll(PDO::FETCH_UNIQUE);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart - ZonMart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4 fw-bold text-center">🛒 Your Cart</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info text-center">
      Your cart is empty.<br>
      <a href="index.php" class="btn btn-primary mt-3">Browse Products</a>
    </div>
  <?php else: ?>
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $index => $item): 
                $pid = $item['id'];
                $qty = $item['qty'];
                if (!isset($productDetails[$pid])) continue;

                $name = $productDetails[$pid]['name'];
                $price = $productDetails[$pid]['price'];
                $subtotal = $price * $qty;
                $total += $subtotal;
            ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($name) ?></td>
              <td>₹<?= number_format($price, 2) ?></td>
              <td><?= htmlspecialchars($qty) ?></td>
              <td>₹<?= number_format($subtotal, 2) ?></td>
              <td>
                <form method="post" style="display:inline">
                  <input type="hidden" name="action" value="remove">
                  <input type="hidden" name="index" value="<?= $index ?>">
                  <button class="btn btn-sm btn-danger">Remove</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="text-end mt-3">
          <h5>Total: ₹<?= number_format($total, 2) ?></h5>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <a href="index.php" class="btn btn-outline-primary">← Continue Shopping</a>
          <a href="buy_now.php" class="btn btn-success px-4">Proceed to Checkout</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
