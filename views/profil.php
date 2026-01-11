<?php
session_start();
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/UserRepository.php';
require_once dirname(__DIR__) . '/repositories/FavouriteRepository.php';
require_once dirname(__DIR__) . '/repositories/ReservationRepository.php';
require_once dirname(__DIR__) . '/repositories/LogementRepository.php';

use App\Repositories\UserRepository;
use App\Repositories\FavouriteRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\LogementRepository;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userRepo = new UserRepository($pdo);
$favRepo = new FavouriteRepository($pdo);
$resRepo = new ReservationRepository($pdo);
$logementRepo = new LogementRepository($pdo);

$user = $userRepo->getUserById($_SESSION['user_id']);
$favorites = $favRepo->getAllByUserId($_SESSION['user_id']);
$reservations = $resRepo->getByUserId($_SESSION['user_id']); 

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Vesta</title>
    <?php include 'partials/head_resources.php'; ?>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <?php include 'partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit sticky top-4">
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->getFullName()) ?>&background=random&size=128" alt="Profile" class="w-full h-full object-cover">
                </div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($user->getFullName()) ?></h2>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($user->getEmail()) ?></p>
            </div>

            <nav class="space-y-2">
                <a href="?tab=profile" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $activeTab === 'profile' ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-user w-5 text-center"></i> Personal Info
                </a>
                <a href="?tab=favorites" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $activeTab === 'favorites' ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-heart w-5 text-center"></i> My Favorites
                </a>
                <a href="?tab=reservations" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $activeTab === 'reservations' ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i class="fas fa-suitcase w-5 text-center"></i> My Reservations
                </a>
                <div class="border-t border-gray-100 my-2 pt-2">
                    <a href="../views/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Log Out
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="w-full md:w-3/4 bg-white rounded-xl shadow-sm border border-gray-100 p-8 min-h-[500px]">
            
            <?php if ($activeTab === 'profile'): ?>
                <!-- Profile Tab -->
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Personal Information</h1>
                <form action="../actions/update_profile.php" method="POST" class="max-w-xl space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="firstname" value="<?= htmlspecialchars(explode(' ', $user->getFullName())[0]) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="lastname" value="<?= htmlspecialchars(explode(' ', $user->getFullName())[1] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition shadow-md">Save Changes</button>
                    </div>
                </form>

            <?php elseif ($activeTab === 'favorites'): ?>
                <!-- Favorites Tab -->
                <h1 class="text-2xl font-bold text-gray-800 mb-6">My Favorites</h1>
                <?php if (empty($favorites)): ?>
                     <div class="text-center py-12 text-gray-500">
                        <i class="far fa-heart text-4xl mb-3"></i>
                        <p>You haven't saved any listings yet.</p>
                        <a href="logements.php" class="text-orange-600 underline mt-2 block">Start exploring</a>
                     </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach($favorites as $fav): 
                            $logement = $logementRepo->getById($fav->getLogementId());
                            if($logement):
                        ?>
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                            <div class="h-48 overflow-hidden relative">
                                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="w-full h-full object-cover">
                                <button class="absolute top-3 right-3 text-red-500 bg-white rounded-full p-2 w-8 h-8 flex items-center justify-center hover:bg-gray-100 shadow-sm" title="Remove">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800 truncate pr-2"><?= htmlspecialchars($logement->getName()) ?></h3>
                                    <span class="flex items-center gap-1 text-sm"><i class="fas fa-star text-orange-500"></i> New</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-3 truncate"><?= htmlspecialchars($logement->getCity()) ?>, <?= htmlspecialchars($logement->getCountry()) ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-900">$<?= $logement->getPrice() ?> <span class="font-normal text-gray-500 text-sm">/ night</span></span>
                                    <a href="Rooms.php?id=<?= $logement->getId() ?>" class="text-orange-600 text-sm font-medium hover:underline">View</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php elseif ($activeTab === 'reservations'): ?>
                <!-- Reservations Tab -->
                <h1 class="text-2xl font-bold text-gray-800 mb-6">My Reservations</h1>
                <?php if (empty($reservations)): ?>
                     <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-suitcase text-4xl mb-3"></i>
                        <p>No reservations found.</p>
                        <a href="logements.php" class="text-orange-600 underline mt-2 block">Book your first trip</a>
                     </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach($reservations as $res): 
                            $logement = $logementRepo->getById($res->getLogementId());
                            if($logement):
                        ?>
                        <div class="border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row gap-4 items-start md:items-center hover:border-gray-300 transition">
                            <div class="w-full md:w-32 h-24 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($logement->getName()) ?></h3>
                                <p class="text-gray-500 text-sm mb-1"><?= htmlspecialchars($logement->getCity()) ?></p>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Dates:</span> <?= date('M j, Y', strtotime($res->getStartDate())) ?> - <?= date('M j, Y', strtotime($res->getEndDate())) ?>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2 w-full md:w-auto">
                                <span class="font-bold text-xl">$<?= $res->getPrice() ?></span>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">Confirmed</span>
                                <a href="Rooms.php?id=<?= $logement->getId() ?>" class="text-orange-600 text-sm underline">View Listing</a>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>

</body>
</html>
