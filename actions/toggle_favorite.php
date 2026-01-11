<?php
session_start();
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/Entities/favoire.php';
require_once dirname(__DIR__) . '/repositories/impl/FavouriteRepositoryInterface.php';
require_once dirname(__DIR__) . '/repositories/FavouriteRepository.php';

use App\Repositories\impl\FavouriteRepository;

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$logementId = $_POST['d_logement'] ?? null;

if (!$logementId) {
    echo json_encode(['success' => false, 'message' => 'Invalid logement ID']);
    exit;
}

try {
    $repo = new FavouriteRepository($pdo);

    // Check if already favorite
    $favorites = $repo->getAllByUserId($userId);
    $exists = false;
    $favId = null;

    foreach ($favorites as $fav) {
        if ($fav->getLogementId() == $logementId) {
            $exists = true;
            $favId = $fav->getId();
            break;
        }
    }

    if ($exists) {
        // Remove
        $success = $repo->delete($favId);
        $action = 'removed';
    } else {
        // Add
        $success = $repo->save($userId, $logementId);
        $action = 'added';
    }

    echo json_encode(['success' => $success, 'action' => $action]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
