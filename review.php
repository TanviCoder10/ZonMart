<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Fetch all products for dropdown
$products = $pdo->query("SELECT id, name FROM products ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h1 class="mb-4 text-center fw-bold">Submit a Review</h1>
      <p class="text-center text-muted mb-5">
        We value your feedback! Please share your experience with ZonMart products.
      </p>

      <div class="card border-0 shadow-sm p-4">
        <?php if (isset($_SESSION['review_success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['review_success']; unset($_SESSION['review_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
          <div class="alert alert-warning text-center">
            Please <a href="login.php" class="alert-link">log in</a> to submit a review.
          </div>
        <?php else: ?>
          <form action="review_submit.php" method="post">
            <div class="mb-3">
              <label for="product_id" class="form-label">Select Product</label>
              <select name="product_id" id="product_id" class="form-select" required>
                <option value="">-- Choose Product --</option>
                <?php foreach ($products as $product): ?>
                  <option value="<?= htmlspecialchars($product['id']) ?>">
                    <?= htmlspecialchars($product['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="rating" class="form-label">Rating</label>
              <select name="rating" id="rating" class="form-select" required>
                <option value="">Select rating</option>
                <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (Good)</option>
                <option value="3">⭐⭐⭐ (Average)</option>
                <option value="2">⭐⭐ (Poor)</option>
                <option value="1">⭐ (Very Bad)</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="comment" class="form-label">Your Review</label>
              <textarea name="comment" id="comment" rows="5" class="form-control" required></textarea>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-success px-5">Submit Review</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
