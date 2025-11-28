<?php
session_start();

// Demo product list (replace with DB query in real app)
$products = [
    1 => [
        'name' => 'Classic Leather Bag',
        'price' => 2499.00,
        'image' => 'https://i.pinimg.com/originals/27/77/8f/27778ff491410c9c2b09a2e92eb946cd.jpg',
        'desc' => 'Stylish leather bag with roomy compartments. Available in multiple colors.',
        'colors' => 'Brown,Black,Tan',
        'rating' => '★★★★★'
    ],
    2 => [
        'name' => 'Running Shoes',
        'price' => 3499.00,
        'image' => 'https://tse2.mm.bing.net/th/id/OIP._2d8j5qIqK0g9AechWyCFAHaHa?pid=Api&P=0&h=180',
        'desc' => 'Lightweight running shoes with breathable mesh and supportive sole.',
        'colors' => 'Blue,Red,Black,White',
        'rating' => '★★★★☆'
    ],
    3 => [
        'name' => 'Mirrorless Camera',
        'price' => 45999.00,
        'image' => 'https://tse2.mm.bing.net/th/id/OIP.DsE0NtOKvgCefkXbWicJ4wHaFj?pid=Api&P=0&h=180',
        'desc' => 'Compact mirrorless camera with excellent low-light performance.',
        'colors' => 'Black,Silver',
        'rating' => '★★★★★'
    ],
    4 => [
        'name' => 'Casual Shirt',
        'price' => 799.00,
        'image' => 'https://tse3.mm.bing.net/th/id/OIP.06RrlfqTJe5_pmIyirgRWAHaJl?pid=Api&P=0&h=180',
        'desc' => 'Comfortable casual shirt available in multiple colors and sizes.',
        'colors' => 'White,Blue,Gray',
        'rating' => '★★★★☆'
    ]
];

// Get search query
$q = trim($_GET['q'] ?? '');

$results = [];
if ($q !== '') {
    foreach ($products as $id => $product) {
        if (stripos($product['name'], $q) !== false) {
            $results[$id] = $product;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Results - ZonMart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .product-card { cursor: pointer; transition: transform 0.2s; }
  .product-card:hover { transform: scale(1.03); }
  .price { color: #d9534f; }
  .color-swatch.active { outline: 2px solid #000; }
</style>
</head>
<body class="p-4">

<div class="container">
  <h2 class="mb-4 text-center">Search Results for "<?= htmlspecialchars($q) ?>"</h2>

  <?php if (empty($results)): ?>
    <p class="text-center text-muted">No products found.</p>
  <?php else: ?>
  <div class="row g-3">
    <?php foreach ($results as $id => $product): ?>
      <div class="col-6 col-md-3">
        <div class="card product-card h-100"
             data-id="<?= $id ?>"
             data-name="<?= htmlspecialchars($product['name']) ?>"
             data-price="<?= $product['price'] ?>"
             data-image="<?= htmlspecialchars($product['image']) ?>"
             data-desc="<?= htmlspecialchars($product['desc']) ?>"
             data-colors="<?= htmlspecialchars($product['colors']) ?>">
          <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
          <div class="card-body text-center">
            <h6 class="card-title mb-1"><?= htmlspecialchars($product['name']) ?></h6>
            <div class="text-warning mb-2"><?= $product['rating'] ?></div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="price fw-bold">₹<?= number_format($product['price']) ?></div>
              <button class="btn btn-sm btn-outline-primary quick-view-btn">Quick View</button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">Back to Home</a>
  </div>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6 text-center">
            <img id="modalProductImage" src="" class="img-fluid rounded" alt="Product image">
          </div>
          <div class="col-md-6">
            <h4 id="modalProductName">Product Name</h4>
            <div id="modalProductPrice" class="h4 text-danger fw-bold mb-2">₹0.00</div>
            <p id="modalProductDesc" class="text-muted">Product short description</p>

            <div class="mb-3">
              <label class="form-label">Available colors</label>
              <div id="modalColorSwatches" class="mb-2"></div>
            </div>

            <div class="d-flex gap-2">
              <button id="modalAddToCart" class="btn btn-outline-primary flex-fill">Add to Cart</button>
              <button id="modalBuyNow" class="btn btn-primary flex-fill" onclick="window.location.href='buy_now.php'">Buy Now</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const productModalEl = document.getElementById('productModal');
  const productModal = new bootstrap.Modal(productModalEl);
  const modalTitle = productModalEl.querySelector('.modal-title');
  const modalImage = document.getElementById('modalProductImage');
  const modalPrice = document.getElementById('modalProductPrice');
  const modalDesc = document.getElementById('modalProductDesc');
  const modalColors = document.getElementById('modalColorSwatches');

  // Quick View click handler
  document.querySelectorAll('.quick-view-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const card = btn.closest('.product-card');
      const name = card.dataset.name;
      const price = card.dataset.price;
      const image = card.dataset.image;
      const desc = card.dataset.desc;
      const colors = (card.dataset.colors || '').split(',');

      modalTitle.textContent = name;
      modalImage.src = image;
      modalPrice.textContent = '₹' + parseFloat(price).toLocaleString();
      modalDesc.textContent = desc;

      // build color swatches
      modalColors.innerHTML = '';
      colors.forEach(c => {
        const sw = document.createElement('button');
        sw.type = 'button';
        sw.className = 'btn btn-sm me-2 mb-2 color-swatch';
        sw.style.background = c.trim().toLowerCase();
        sw.title = c.trim();
        sw.innerHTML = '&nbsp;';
        sw.addEventListener('click', () => {
          modalColors.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('active'));
          sw.classList.add('active');
        });
        modalColors.appendChild(sw);
      });

      productModal.show();
    });
  });

  // Add to Cart (demo)
  document.getElementById('modalAddToCart').addEventListener('click', () => {
    alert('✅ Added to cart (demo). Integrate with backend.');
  });
});
</script>
</body>
</html>
