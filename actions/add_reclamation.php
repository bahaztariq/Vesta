<?php
session_start();
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/ReclamationRepository.php';

use App\Repositories\ReclamationRepository;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to report an issue.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $logementId = isset($_POST['logement_id']) ? (int)$_POST['logement_id'] : 0;
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Basic Validation
    if ($logementId <= 0 || empty($message)) {
        $_SESSION['error'] = "Please provide a valid message.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $reclamationRepo = new ReclamationRepository($pdo);
        // Note: Ideally check for reservation here too, relying on UI/Session for MVP.

        if ($reclamationRepo->save($userId, $logementId, $message)) {
            $_SESSION['success'] = "Issue reported successfully. We will look into it.";
        } else {
            $_SESSION['error'] = "Failed to report issue.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
