<?php
session_start();

require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/LogementRepository.php';
require_once dirname(__DIR__) . '/Entities/favoire.php';
require_once dirname(__DIR__) . '/repositories/FavouriteRepository.php';

use App\Repositories\LogementRepository;
use App\Repositories\FavouriteRepository;

$logementRepository = new LogementRepository($pdo);

// Initialize filters
$query = $_GET['search'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$guests = $_GET['guests'] ?? '';

$filters = [
    'min_price' => $minPrice,
    'max_price' => $maxPrice,
    'guests' => $guests
];

// Fetch results
$logements = $logementRepository->search($query, $filters);

// Fetch favorites
$favRepo = new FavouriteRepository($pdo);
$userFavorites = [];
if (isset($_SESSION['user_id'])) {
    $favs = $favRepo->getAllByUserId($_SESSION['user_id']);
    foreach ($favs as $f) {
        $userFavorites[] = $f->getLogementId();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vesta - Browse Homes</title>
    <?php include 'components/head_resources.php'; ?>
    <!-- Reuse existing CSS if available -->
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full md:w-1/4">
                <div class="bg-white p-6 rounded-xl shadow-sm sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Filters</h2>
                    <form action="" method="GET" class="space-y-6">
                        <!-- Maintain search query hidden if present -->
                        <input type="hidden" name="search" value="<?= htmlspecialchars($query) ?>">

                        <div>
                            <h3 class="font-semibold mb-2">Price Range</h3>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min" value="<?= htmlspecialchars($minPrice) ?>" class="w-full p-2 border rounded-md">
                                <input type="number" name="max_price" placeholder="Max" value="<?= htmlspecialchars($maxPrice) ?>" class="w-full p-2 border rounded-md">
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold mb-2">Guests</h3>
                            <select name="guests" class="w-full p-2 border rounded-md">
                                <option value="">Any</option>
                                <option value="1" <?= $guests == '1' ? 'selected' : '' ?>>1+</option>
                                <option value="2" <?= $guests == '2' ? 'selected' : '' ?>>2+</option>
                                <option value="4" <?= $guests == '4' ? 'selected' : '' ?>>4+</option>
                                <option value="6" <?= $guests == '6' ? 'selected' : '' ?>>6+</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition">Apply Filters</button>
                        <?php if($query || $minPrice || $maxPrice || $guests): ?>
                            <a href="logements.php" class="block text-center text-gray-500 text-sm mt-2 hover:underline">Clear Filters</a>
                        <?php endif; ?>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="w-full md:w-3/4">
                <!-- Search Bar -->
                <div class="bg-white p-4 rounded-xl shadow-sm mb-6">
                    <form action="" method="GET" class="flex gap-2">
                        <!-- Maintain filters hidden -->
                        <input type="hidden" name="min_price" value="<?= htmlspecialchars($minPrice) ?>">
                        <input type="hidden" name="max_price" value="<?= htmlspecialchars($maxPrice) ?>">
                        <input type="hidden" name="guests" value="<?= htmlspecialchars($guests) ?>">
                        
                        <div class="relative flex-grow">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" name="search" placeholder="Search by destination (City, Country) or Name..." value="<?= htmlspecialchars($query) ?>" class="w-full pl-10 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-md hover:bg-orange-700 transition">Search</button>
                    </form>
                </div>

                <!-- Results Grid -->
                <?php if (empty($logements)): ?>
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4"><i class="fas fa-search"></i></div>
                        <h3 class="text-xl font-bold text-gray-800">No properties found</h3>
                        <p class="text-gray-600">Try adjusting your search or filters.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php foreach ($logements as $logement): ?>
                        <div onclick="window.location.href='Rooms.php?id=<?= $logement->getId() ?>'" class="cursor-pointer group">
                             <div class="flex flex-col gap-2 w-full">
                                <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                    <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="<?= htmlspecialchars($logement->getImgPath()) ?>" alt="<?= htmlspecialchars($logement->getName()) ?>">
                                    <div onclick="event.stopPropagation()" class="absolute text-xl top-3 right-3 text-orange-600/70 hover:text-orange-600 transition">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" class="hidden favorite-checkbox"
                                                data-id="<?= $logement->getId() ?>"
                                                <?= in_array($logement->getId(), $userFavorites) ? 'checked' : '' ?>
                                                onchange="toggleFavorite(this, <?= $logement->getId() ?>)">
                                            <i class="<?= in_array($logement->getId(), $userFavorites) ? 'fa-solid text-orange-600' : 'fa-regular' ?> fa-heart"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-start pt-1">
                                    <div class="font-semibold text-sm"><?= htmlspecialchars($logement->getCity() . ', ' . $logement->getCountry()) ?></div>
                                    <div class="flex flex-row items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-light">4.8</span>
                                    </div>
                                </div>
                                <div class="font-light text-neutral-500 text-sm"><?= htmlspecialchars($logement->getName()) ?></div>
                                <div class="font-light text-neutral-500 text-sm"><?= $logement->getGuestNum() ?> guests</div>
                                <div class="flex flex-row items-center gap-1 mt-1">
                                    <div class="font-semibold text-sm">$<?= $logement->getPrice() ?></div>
                                    <div class="font-light text-sm">night</div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function toggleFavorite(checkbox, id) {
            const icon = checkbox.nextElementSibling;
            const isChecked = checkbox.checked;

            // Optimistic UI update
            if (isChecked) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid', 'text-orange-600');
            } else {
                icon.classList.remove('fa-solid', 'text-orange-600');
                icon.classList.add('fa-regular');
            }

            const formData = new FormData();
            formData.append('d_logement', id);

            fetch('../../actions/toggle_favorite.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Revert if failed
                    checkbox.checked = !isChecked;
                    if (!isChecked) { // Was checked, now unchecked (revert)
                         icon.classList.remove('fa-regular');
                         icon.classList.add('fa-solid', 'text-orange-600');
                    } else { // Was unchecked, now checked (revert)
                         icon.classList.remove('fa-solid', 'text-orange-600');
                         icon.classList.add('fa-regular');
                    }
                    alert(data.message || 'Error updating favorite. Please make sure you are logged in.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert
                checkbox.checked = !isChecked;
                if (!isChecked) {
                     icon.classList.remove('fa-regular');
                     icon.classList.add('fa-solid', 'text-orange-600');
                } else {
                     icon.classList.remove('fa-solid', 'text-orange-600');
                     icon.classList.add('fa-regular');
                }
            });
        }
    </script>
</body>
</html>
