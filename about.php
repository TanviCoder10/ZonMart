<?php
// about.php
session_start();

?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About - ZonMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php include 'includes/header.php'; ?>
  <div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h1 class="mb-4 text-center fw-bold">About ZonCart</h1>
      <p class="lead text-center text-muted mb-5">
        Your one-stop destination for high-quality products at unbeatable prices.
      </p>

      <div class="card shadow-sm border-0 p-4">
        <p>
          Welcome to <strong>ZonCart</strong> — an online shopping platform built with love and designed for simplicity.
          We aim to make online shopping effortless, enjoyable, and secure for everyone. From electronics and fashion
          to home essentials and more, we bring everything under one roof.
        </p>

        <p>
          Our mission is to empower customers with convenience and confidence by offering top-quality products, reliable
          delivery, and responsive customer support. Whether you're a regular shopper or a small business owner looking
          to reach new audiences, ZonCart is here for you.
        </p>

        <h4 class="mt-4">Why Choose ZonCart?</h4>
        <ul>
          <li>✅ Secure payments and data protection</li>
          <li>✅ Fast and reliable delivery</li>
          <li>✅ 24/7 customer support</li>
          <li>✅ Wide range of trusted brands and products</li>
        </ul>

        <p class="mt-4">
          Thank you for being part of our journey. Together, we’re building a smarter, faster, and friendlier way to
          shop online.
        </p>

        <p class="text-muted mb-0">— The ZonCart Team 💙</p>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
