<?php
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/ReservationRepository.php';

use App\Repositories\ReservationRepository;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php?redirect=Rooms.php?id=' . ($_POST['logement_id'] ?? ''));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $logementId = isset($_POST['logement_id']) ? (int)$_POST['logement_id'] : 0;
    $startDateStr = $_POST['start_date'] ?? '';
    $endDateStr = $_POST['end_date'] ?? '';
    $guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 1;
    $pricePerNight = isset($_POST['price_per_night']) ? (float)$_POST['price_per_night'] : 0;

    if ($logementId <= 0 || empty($startDateStr) || empty($endDateStr)) {
        echo "<script>alert('Invalid booking details.'); window.history.back();</script>";
        exit;
    }

    $start = new DateTime($startDateStr);
    $end = new DateTime($endDateStr);

    if ($end <= $start) {
        echo "<script>alert('End date must be after start date.'); window.history.back();</script>";
        exit;
    }

    $days = $end->diff($start)->days;
    $basePrice = $days * $pricePerNight;
    $cleaningFee = 15;
    $serviceFee = 25;
    $totalPrice = $basePrice + $cleaningFee + $serviceFee;

    $reservationRepo = new ReservationRepository($pdo);
    
    
    $success = $reservationRepo->save($userId, $logementId, $startDateStr, $endDateStr, (int)$totalPrice);

    if ($success) {
        
        echo "<script>alert('Reservation successfully created!'); window.location.href='../views/index.php';</script>";
    } else {
        echo "<script>alert('Failed to create reservation. Please try again.'); window.history.back();</script>";
    }
} else {
    header('Location: ../views/index.php');
    exit;
}
