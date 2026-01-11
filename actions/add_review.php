<?php
session_start();
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/ReviewRepository.php';

use App\Repositories\ReviewRepository;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to leave a review.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $logementId = isset($_POST['logement_id']) ? (int)$_POST['logement_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Basic Validation
    if ($logementId <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
        $_SESSION['error'] = "Please provide a valid rating and comment.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $reviewRepo = new ReviewRepository($pdo);

        if ($reviewRepo->save($userId, $logementId, $rating, $comment)) {
            $_SESSION['success'] = "Review submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit review.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
