<!-- // File: project-root/index.php -->
<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

session_start();

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ZonMart — Everything You Need — Fast & Reliable</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <script>
    // Product modal logic (deferred execution works because script tag has defer)
    document.addEventListener('DOMContentLoaded', function () {
      const productModalEl = document.getElementById('productModal');
      const productModal = new bootstrap.Modal(productModalEl);
      const modalTitle = productModalEl.querySelector('.modal-title');
      const modalImage = productModalEl.querySelector('#modalProductImage');
      const modalPrice = productModalEl.querySelector('#modalProductPrice');
      const modalColors = productModalEl.querySelector('#modalColorSwatches');
      const modalDesc = productModalEl.querySelector('#modalProductDesc');

      // Click handler: any element with .product-card and data-* attributes
      document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', () => {
          const name = card.dataset.name || 'Product';
          const price = card.dataset.price || '0.00';
          const image = card.dataset.image || '';
          const desc = card.dataset.desc || 'High quality product.';
          const colors = (card.dataset.colors || 'Red,Blue,Black').split(',');

          modalTitle.textContent = name;
          modalPrice.textContent = '₹' + parseFloat(price).toFixed(2);
          modalImage.src = image;
          modalDesc.textContent = desc;

          // build color swatches
          modalColors.innerHTML = '';
          colors.forEach(c => {
            const sw = document.createElement('button');
            sw.type = 'button';
            sw.className = 'btn btn-sm me-2 mb-2 color-swatch';
            sw.title = c.trim();
            sw.dataset.color = c.trim();
            sw.style.background = c.trim().toLowerCase();
            sw.innerHTML = '&nbsp;';
            sw.addEventListener('click', () => {
              // highlight selected
              modalColors.querySelectorAll('.color-swatch').forEach(b=>b.classList.remove('active'));
              sw.classList.add('active');
            });
            modalColors.appendChild(sw);
          });

          // show modal
          productModal.show();
        });
      });
    });

  </script>

</head>
<body>
<?php include 'includes/header.php'; ?>


<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1600&q=60" class="d-block w-100" alt="Shopping">
        <div class="carousel-caption d-none d-md-block text-start">
          <h1 class="display-5">Big Savings on Electronics</h1>
          <p class="lead">Latest gadgets, exclusive deals — shop now.</p>
          <a class="btn btn-primary" href="#sales">Shop Now</a>
        </div>
      </div>

      <div class="carousel-item">
        <img src="http://www.darngoodyarn.com/cdn/shop/articles/Boho_Chic__How_to_Style_Boho_Clothing_for_Every_Season.jpg?v=1731891981" class="d-block w-100" alt="Fashion">
        <div class="carousel-caption d-none d-md-block text-start">
          <h1 class="display-5">Fashion For Every Season</h1>
          <p class="lead">Trendy styles and comfortable wear.</p>
          <a class="btn btn-primary" href="#categories">View Deals</a>
        </div>
      </div>

      <div class="carousel-item"> 
      <img src="https://static.vecteezy.com/system/resources/previews/033/634/249/non_2x/various-kitchen-utensils-are-sitting-on-a-table-ai-generated-free-photo.jpg" class="d-block w-100" alt="Home & Kitchen">
        <div class="carousel-caption d-none d-md-block text-start">
          <h1 class="display-5">Home & Kitchen Essentials</h1>
          <p class="lead">Quality products for your home.</p>
          <a class="btn btn-primary" href="#categories">Shop Home</a>
        </div>
      </div>
    </div>

    

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <nav class="category-bar d-flex align-items-center px-3" aria-label="Product categories">
    <div class="category-scroll w-100">
      <a class="cat-link" href="#categories">Home & Kitchen</a>
      <a class="cat-link" href="#categories">Fashion</a>
      <a class="cat-link" href="#categories">Electronics</a>
      <a class="cat-link" href="#sell">Sell</a>
      <a class="cat-link highlight" href="#deals">Today's Deals</a>
    </div>
  </nav>


<main class="container mt-0">
  <!-- Categories grid -->
  <section id="categories" class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">Our Products</h3>
      <small class="text-muted">Showing popular categories</small>
    </div>

  <div class="row g-3">
  <?php
  // Fetch all products from the database
  $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($products) > 0):
      foreach ($products as $product):
          $id = htmlspecialchars($product['id']);
          $name = htmlspecialchars($product['name']);
          $price = (float)$product['price'];
          $image = htmlspecialchars($product['image'] ?: 'placeholder.png');
  ?>
      <div class="col-6 col-md-3">
        <div class="card product-card h-100" role="button"
             data-id="<?= $id ?>"
             data-name="<?= $name ?>"
             data-price="<?= $price ?>"
             data-image="<?= $image ?>"
             data-desc="<?= $name ?> — a top quality product available now."
             data-colors="Red,Blue,Black,White">
          <img src="<?= $image ?>" class="card-img-top" alt="<?= $name ?>">
          <div class="card-body text-center">
            <h6 class="card-title mb-1"><?= $name ?></h6>
            <div class="text-warning mb-2">★★★★★</div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="price fw-bold">₹<?= $price ?></div>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                Quick View
              </button>
            </div>
          </div>
        </div>
      </div>
  <?php
      endforeach;
  else:
  ?>
      <div class="col-12 text-center py-5">
        <p class="text-muted mb-0">No products found. Please add some using <strong>admin.php</strong>.</p>
      </div>
  <?php endif; ?>
</div>

</div>


  </section>

  <!-- Sales Section -->
  <section id="sales" class="py-5 bg-light">
    <div class="container">
      <h3 class="mb-4 text-center">Sales & Hot Deals</h3>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card h-100 sale-card">
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=60" class="card-img-top" alt="Laptop Sale">
            <div class="card-body text-center">
              <h5 class="card-title">Laptop Mega Sale</h5>
              <p class="mb-0">Up to 40% off — limited time</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 sale-card">
            <img src="https://tse1.mm.bing.net/th/id/OIP.3UUTNFw6oeZ8UKnfwhebbQHaEK?pid=Api&P=0&h=180" class="card-img-top" alt="Watches">
            <div class="card-body text-center">
              <h5 class="card-title">Smart Watches</h5>
              <p class="mb-0">Top brands at special prices</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 sale-card">
            <img src="https://static-assets.business.amazon.com/assets/in/24th-jan/705_Website_Blog_Appliances_1450x664.jpg.transform/1450x664/image.jpg" class="card-img-top" alt="Home Appliances">
            <div class="card-body text-center">
              <h5 class="card-title">Home Appliances</h5>
              <p class="mb-0">Make your home smarter</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Offers Section -->
  <section id="offers" class="container py-5">
    <h3 class="mb-4 text-center">Special Offers</h3>
    <div class="row g-3">
      <div class="col-md-6">
        <div class="offer-card p-4 rounded">
          <h5 class="fw-bold">WELCOME10</h5>
          <p class="mb-0">Get 10% off on your first order (min ₹500)</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="offer-card p-4 rounded offer-green">
          <h5 class="fw-bold">FREESHIP</h5>
          <p class="mb-0">Free shipping on orders above ₹999</p>
        </div>
      </div>
    </div>
  </section>

 
  

    <!-- Reviews Section (cards with avatars) -->
  <section id="reviews" class="py-5 bg-white">
    <div class="container">
      <h3 class="text-center mb-5">What Our Customers Say</h3>

      <div class="row g-4 justify-content-center">
        <div class="col-md-4">
          <div class="card review-card p-3 shadow-sm border-0 text-center">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-3 review-avatar" width="80" height="80" alt="Amit">
            <h6 class="mb-1 fw-bold">Amit Kumar</h6>
            <div class="text-warning mb-2">★★★★★</div>
            <p class="text-muted small mb-0">“Amazing service and fast delivery! Products are genuine and packaging was great.”</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card review-card p-3 shadow-sm border-0 text-center">
            <img src="https://randomuser.me/api/portraits/women/45.jpg" class="rounded-circle mb-3 review-avatar" width="80" height="80" alt="Sneha">
            <h6 class="mb-1 fw-bold">Sneha Patel</h6>
            <div class="text-warning mb-2">★★★★☆</div>
            <p class="text-muted small mb-0">“Loved the product quality and packaging. Great experience overall.”</p>
          </div>
        </div>

      </div>
<br> 

    <?php
// Fetch the 2 most recent reviews
$reviews = $pdo->query("
    SELECT r.rating, r.comment, u.name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC 
    LIMIT 2
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row g-4 justify-content-center">
  <?php if (count($reviews) > 0): ?>
    <?php foreach ($reviews as $review): ?>
      <div class="col-md-4">
        <div class="card review-card p-3 shadow-sm border-0 text-center">
          <img src="https://randomuser.me/api/portraits/lego/<?= rand(1,9) ?>.jpg" 
               class="rounded-circle mb-3 review-avatar" width="80" height="80" alt="User">
          <h6 class="mb-1 fw-bold"><?= htmlspecialchars($review['name']) ?></h6>
          <div class="text-warning mb-2">
            <?= str_repeat('★', $review['rating']) ?>
            <?= str_repeat('☆', 5 - $review['rating']) ?>
          </div>
          <p class="text-muted small mb-0">“<?= htmlspecialchars($review['comment']) ?>”</p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12 text-center">
      <p class="text-muted">No reviews yet. Be the first to share your feedback!</p>
    </div>
  <?php endif; ?>
</div>
     <!-- ✅ Add this button below the title -->
    <div class="text-center mb-4 mt-5">
      <a href="review.php" class="btn btn-success px-4">
        <i class="fa fa-pen me-2"></i> Write a Review
      </a>
    </div>
    </div>

  </section>


  
  <!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 id="modalProductName" class="modal-title">Product Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6 text-center">
            <img id="modalProductImage" src="" class="img-fluid rounded" alt="Product image">
          </div>
          <div class="col-md-6">
            <div id="modalProductPrice" class="h4 text-danger fw-bold mb-2">₹0.00</div>
            <p id="modalProductDesc" class="text-muted">Product description here.</p>

            <div class="mb-3">
              <label class="form-label">Available colors</label>
              <div id="modalColorSwatches" class="mb-2"></div>
            </div>

            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary flex-fill" id="modalAddToCart">Add to Cart</button>
              <button class="btn btn-primary flex-fill" id="modalBuyNow">Buy Now</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <small class="text-muted">Click Add to Cart to test behavior.</small>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const productModal = document.getElementById('productModal');
  const modalProductName = document.getElementById('modalProductName');
  const modalProductPrice = document.getElementById('modalProductPrice');
  const modalProductDesc = document.getElementById('modalProductDesc');
  const modalProductImage = document.getElementById('modalProductImage');
  const modalColorSwatches = document.getElementById('modalColorSwatches');
  const addToCartBtn = document.getElementById('modalAddToCart');
  const buyNowBtn = document.getElementById('modalBuyNow');

  let currentProductId = null;

  document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', () => {
      const name = card.dataset.name;
      const price = card.dataset.price;
      const desc = card.dataset.desc;
      const image = card.dataset.image;
      const colors = card.dataset.colors ? card.dataset.colors.split(',') : [];
      const id = card.dataset.id;

      // Set modal info
      modalProductName.textContent = name;
      modalProductPrice.textContent = `₹${parseFloat(price).toFixed(2)}`;
      modalProductDesc.textContent = desc;
      modalProductImage.src = image;

      // Clear old colors
      modalColorSwatches.innerHTML = '';

      // Create proper colored swatches
      colors.forEach(c => {
        const sw = document.createElement('button');
        sw.type = 'button';
        sw.className = 'btn btn-sm me-2 mb-2 color-swatch';
        sw.dataset.color = c.trim();
        sw.style.background = c.trim().toLowerCase();
        sw.className = 'btn btn-sm me-2 mb-2 color-swatch';
        sw.style.border = '1px solid #ddd';
        
        sw.innerHTML = '';
        sw.addEventListener('click', () => {
          // Highlight selected
          modalColorSwatches.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('active'));
          sw.classList.add('active');
        });
        modalColorSwatches.appendChild(sw);
      });

      currentProductId = id;
      addToCartBtn.dataset.id = id;
    });
  });

  // Add to Cart
  addToCartBtn.addEventListener('click', () => {
    const productId = currentProductId;
    const qty = 1;

    fetch('cart.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=add&id=${productId}&qty=${qty}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.querySelector('a.nav-link[href="cart.php"]').textContent = `Cart (${data.cartCount})`;
        alert('✅ Added to cart!');
      }
    })
    .catch(err => console.error(err));
  });

  // ✅ Buy Now button redirect with product ID
buyNowBtn.addEventListener('click', () => {
  if (!currentProductId) {
    alert('Please select a product first.');
    return;
  }
  // Redirect to buy_now.php with the selected product ID
  window.location.href = `buy_now.php?id=${currentProductId}`;
});

});

</script>


</body>
</html>

</main>
<?php include 'includes/footer.php'; ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>