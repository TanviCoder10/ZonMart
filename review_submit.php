<?php
session_start();
include 'includes/db.php'; // PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $product_id = (int)$_POST['product_id'];
    $user_id = (int)$_SESSION['user_id']; // logged-in user
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $created_at = date('Y-m-d H:i:s');

    // Basic validation
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        $_SESSION['review_success'] = "Please fill in all required fields.";
        header("Location: review.php?product_id=$product_id");
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO reviews (product_id, user_id, rating, comment, created_at) 
            VALUES (:product_id, :user_id, :rating, :comment, :created_at)
        ");
        $stmt->execute([
            ':product_id' => $product_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':comment' => $comment,
            ':created_at' => $created_at
        ]);

        $_SESSION['review_success'] = "Your review has been submitted successfully!";

    } catch (PDOException $e) {
        $_SESSION['review_success'] = "Error: Unable to save your review. Please try again.";
    }

    header("Location: review.php?product_id=$product_id");
    exit;

} else {
    header("Location: index.php");
    exit;
}
