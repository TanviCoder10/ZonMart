<?php
session_start();
include 'includes/db.php'; // PDO connection

// --- Handle Delete Product ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Product deleted successfully.";
    header("Location: admin.php");
    exit;
}

// --- Handle Add or Edit Product ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $image = trim($_POST['image']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $created_at = date('Y-m-d H:i:s');

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Edit Product
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, image=?, featured=? WHERE id=?");
        $stmt->execute([$name, $category_id, $price, $image, $featured, $id]);
        $_SESSION['msg'] = "Product updated successfully.";
    } else {
        // Add Product
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, image, featured, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category_id, $price, $image, $featured, $created_at]);
        $_SESSION['msg'] = "Product added successfully.";
    }

    header("Location: admin.php");
    exit;
}

// --- Fetch Products ---
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Fetch Categories ---
$catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">🛠 Admin - Manage Products</h2>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <!-- Add/Edit Product Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3" id="formTitle">Add Product</h5>
            <form method="POST" id="productForm">
                <input type="hidden" name="id" id="productId">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Image URL</label>
                        <input type="text" name="image" id="image" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="featured" id="featured">
                            <label class="form-check-label" for="featured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-success px-4">Save Product</button>
                    <button type="reset" class="btn btn-secondary px-3" onclick="resetForm()">Clear</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Existing Products</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><img src="<?= htmlspecialchars($p['image']) ?>" width="60" height="60" class="rounded"></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['category_name']) ?></td>
                            <td><?= number_format($p['price'], 2) ?></td>
                            <td><?= $p['featured'] ? '✅' : '❌' ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-2" 
                                        onclick='editProduct(<?= json_encode($p) ?>)'>Edit</button>
                                <a href="?delete=<?= $p['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Fill form with existing data for editing
function editProduct(p) {
    document.getElementById('formTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = p.id;
    document.getElementById('name').value = p.name;
    document.getElementById('category_id').value = p.category_id;
    document.getElementById('price').value = p.price;
    document.getElementById('image').value = p.image;
    document.getElementById('featured').checked = p.featured == 1;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Reset form for adding new
function resetForm() {
    document.getElementById('formTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
}
</script>

<?php include 'includes/footer.php'; ?>
