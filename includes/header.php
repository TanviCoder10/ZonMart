<!-- File: project-root/includes/header.php -->
 
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$logged = isset($_SESSION['user_id']);
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ZonMart</title>

  <!-- ✅ Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Optional Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- ✅ Your custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="/zonmart">ZonMart</a>
    <form class="d-flex mx-auto" action="search.php" method="get" style="max-width:600px; width:100%">
      <input class="form-control me-2" type="search" placeholder="Search products..." aria-label="Search" name="q">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <?php if(!$logged): ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?=htmlspecialchars($_SESSION['user_name'])?></a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="account.php">Account</a></li>
              <li><a class="dropdown-item" href="orders.php">Orders</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart (<?=$cartCount?>)</a></li>
      </ul>
    </div>
  </div>
</nav>