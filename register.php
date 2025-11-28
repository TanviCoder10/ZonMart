<!-- File: project-root/register.php -->

<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'] ?? '';
  if (!$name) $errors[] = 'Enter your name.';
  if (!$email) $errors[] = 'Enter a valid email address.';
  if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters long.';
  if (empty($errors)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())');
    try {
      $stmt->execute([$name, $email, $hash]);
      header('Location: login.php');
      exit;
    } catch (Exception $e) {
      $errors[] = 'Email already registered. Please login.';
    }
  }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - ZonMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php include 'includes/header.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4">
          <h3 class="mb-4 text-center">Create Your ZonMart Account</h3>
          <?php if ($errors): ?>
            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input name="name" class="form-control" placeholder="Your name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" placeholder="Email address" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-success" type="submit">Register</button>
            </div>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>