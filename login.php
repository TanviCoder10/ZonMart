<!-- File: project-root/login.php -->

<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'] ?? '';
  if (!$email) $errors[] = 'Enter a valid email.';
  if (!$password) $errors[] = 'Enter password.';
  if (empty($errors)) {
    $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      header('Location: index.php');
      exit;
    } else {
      $errors[] = 'Invalid credentials.';
    }
  }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - ZonMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php include 'includes/header.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4">
          <h3 class="mb-4 text-center">Login to ZonMart</h3>
          <?php if ($errors): ?>
            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary" type="submit">Login</button>
            </div>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>