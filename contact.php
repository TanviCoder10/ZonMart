<?php
// contact.php
session_start();

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success text-center">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']); // Clear message after showing
}

include 'includes/header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h1 class="mb-4 text-center fw-bold">Contact Us</h1>
      <p class="text-center text-muted mb-5">
        Have a question or feedback? We’d love to hear from you!  
        Fill out the form below and our team will get back to you soon.
      </p>

      <div class="card border-0 shadow-sm p-4">
        <form action="contact_submit.php" method="post">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success px-5">Send Message</button>
          </div>
        </form>
      </div>

      <div class="text-center mt-5">
        <h5>Or reach us directly</h5>
        <p class="mb-1"><i class="fa-solid fa-envelope"></i> support@zonmart.com</p>
        <p><i class="fa-solid fa-phone"></i> +91 98765 43210</p>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
