<?php
require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/LogementRepository.php';
require_once dirname(__DIR__) . '/repositories/UserRepository.php';
require_once dirname(__DIR__) . '/repositories/ReviewRepository.php';
require_once dirname(__DIR__) . '/repositories/ReservationRepository.php';
require_once dirname(__DIR__) . '/services/LogementService.php';
require_once dirname(__DIR__) . '/services/UserService.php';
require_once dirname(__DIR__) . '/services/ReviewService.php';

use App\Repositories\LogementRepository;
use App\Repositories\UserRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\ReservationRepository;
use App\Services\LogementService;
use App\Services\UserService;
use App\Services\ReviewService;

$logementRepo = new LogementRepository($pdo);
$userRepo = new UserRepository($pdo);
$reviewRepo = new ReviewRepository($pdo);
$reservationRepo = new ReservationRepository($pdo);

$logementService = new LogementService($logementRepo);
$userService = new UserService($userRepo);
$reviewService = new ReviewService($reviewRepo);

$logementId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$logement = $logementService->getLogementById($logementId);
$logementDates = $logementService->getLogementsDates($logementId);
session_start();


if (!$logement) {
    header('Location: index.php');
    exit;
}

// Use Service to get Host
$host = $userService->getUserById($logement->getHoteId());
$hostName = $host ? $host->getFullName() : 'Unknown Host';
$hostImage = "https://ui-avatars.com/api/?name=" . urlencode($hostName) . "&background=random";


// Use Service to get Reviews
$reviews = $reviewService->getReviewsByLogementId($logement->getId());
$ratingSum = 0;
$reviewCount = count($reviews);
foreach ($reviews as $r) {
    $ratingSum += $r->getRating();
}
$avgRating = $reviewCount > 0 ? round($ratingSum / $reviewCount, 2) : 0;

$hasReserved = false;
if (isset($_SESSION['user_id'])) {
    $hasReserved = $reservationRepo->hasReservation($_SESSION['user_id'], $logement->getId());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($logement->getName()) ?> - Vesta</title>
    <?php include 'partials/head_resources.php'; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js" defer></script>
</head>

<body class="bg-white text-gray-800 font-sans">

    <!-- Navbar -->
    <?php include 'partials/navbar.php'; ?>

    <main class="max-w-[1120px] mx-auto px-4  pb-10">

        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold mb-2"><?= htmlspecialchars($logement->getName()) ?></h1>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-sm">
                <div class="flex items-center gap-4 text-gray-800 font-medium underline-offset-2">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-star text-sm"></i>
                        New · <span class="underline">0 reviews</span>
                    </span>
                    <span class="text-gray-400">·</span>
                    <span class="underline"><?= htmlspecialchars($logement->getCity()) ?>, <?= htmlspecialchars($logement->getCountry()) ?></span>
                </div>
                <div class="flex items-center gap-4 mt-2 sm:mt-0">
                    <button class="flex items-center gap-2 hover:bg-gray-100 px-2 py-1 rounded-lg underline text-sm font-medium">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                    <button class="flex items-center gap-2 hover:bg-gray-100 px-2 py-1 rounded-lg underline text-sm font-medium">
                        <i class="far fa-heart"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <!-- Image Gallery -->
        <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-2 h-[300px] md:h-[450px] overflow-hidden rounded-xl relative mb-12">
            <!-- Main larger image -->
            <div class="col-span-1 md:col-span-2 row-span-2 cursor-pointer hover:opacity-90 transition bg-gray-200">
                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="object-cover w-full h-full" alt="<?= htmlspecialchars($logement->getName()) ?>" onerror="this.src='https://placehold.co/800x600?text=No+Image'">
            </div>
            <!-- Side images (using same image for demo purposes since we only have one path in DB) -->
            <div class="hidden md:block cursor-pointer hover:opacity-90 transition bg-gray-200">
                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="object-cover w-full h-full opacity-80" alt="View 2">
            </div>
            <div class="hidden md:block cursor-pointer hover:opacity-90 transition bg-gray-200">
                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="object-cover w-full h-full opacity-80" alt="View 3">
            </div>
            <div class="hidden md:block cursor-pointer hover:opacity-90 transition bg-gray-200">
                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="object-cover w-full h-full opacity-80" alt="View 4">
            </div>
            <div class="hidden md:block cursor-pointer hover:opacity-90 transition relative bg-gray-200">
                <img src="<?= htmlspecialchars($logement->getImgPath()) ?>" class="object-cover w-full h-full opacity-80" alt="View 5">
                <button class="absolute bottom-4 right-4 bg-white border border-black px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-100 flex items-center gap-2 shadow-sm">
                    <i class="fas fa-th"></i> Show all photos
                </button>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            <!-- Left Column: Details -->
            <div class="md:col-span-2">

                <div class="flex justify-between items-center border-b border-gray-200 pb-6 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Hosted by <?= htmlspecialchars($hostName) ?></h2>
                        <p class="text-gray-600"><?= $logement->getGuestNum() ?> guests · Free Wifi · Great Location</p>
                    </div>
                    <img src="<?= $hostImage ?>" class="h-14 w-14 rounded-full border border-gray-200" alt="<?= htmlspecialchars($hostName) ?>">
                </div>

                <!-- Features -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex gap-4 mb-4">
                        <i class="fas fa-medal text-2xl text-gray-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">Superhost</h3>
                            <p class="text-gray-500 text-sm">Superhosts are experienced, highly rated hosts.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-gray-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">Great location</h3>
                            <p class="text-gray-500 text-sm">100% of recent guests gave the location a 5-star rating.</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">About this place</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        <?= htmlspecialchars($logement->getDescription()) ?>
                    </p>
                </div>

                <!-- What this place offers -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">What this place offers</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-wifi w-5"></i> Wifi</div>
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-car w-5"></i> Free parking</div>
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-tv w-5"></i> TV</div>
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-snowflake w-5"></i> Air conditioning</div>
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-fire w-5"></i> Heating</div>
                        <div class="flex items-center gap-3 text-gray-600"><i class="fas fa-kitchen-set w-5"></i> Kitchen</div>
                    </div>
                </div>

                <!-- Reviews Section -->

                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                        <i class="fas fa-star text-base"></i>
                        <?= $avgRating > 0 ? $avgRating : 'New' ?> · <?= $reviewCount ?> reviews
                    </h2>

                    <?php if ($reviewCount > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8">
                            <?php foreach ($reviews as $review):
                                // Fetch reviewer info using Service
                                $reviewer = $userService->getUserById($review->getUserId());
                                $reviewerName = $reviewer ? $reviewer->getFullName() : 'Guest';
                                $reviewerImg = "https://ui-avatars.com/api/?name=" . urlencode($reviewerName) . "&background=random";
                                
                            ?>
                                <div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <img src="<?= $reviewerImg ?>" class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($reviewerName) ?></h3>
                                            <div class="text-gray-500 text-sm">Recent Stay</div>
                                        </div>
                                    </div>
                                    <div class="text-gray-700 leading-relaxed">
                                        <?= htmlspecialchars($review->getComment()) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No reviews yet.</p>
                    <?php endif; ?>

                    <?php if ($hasReserved): ?>
                        <div class="mt-8 border-t border-gray-200 pt-8">
                            <h3 class="text-xl font-semibold mb-4">Leave a Review</h3>
                            <form action="../actions/add_review.php" method="POST" class="bg-gray-50 p-6 rounded-xl">
                                <input type="hidden" name="logement_id" value="<?= $logement->getId() ?>">

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Rating</label>
                                    <div class="flex gap-1 text-2xl text-gray-300" id="star-rating-container">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" class="hidden" required>
                                            <label for="star<?= $i ?>" class="cursor-pointer transition-colors star-label" data-value="<?= $i ?>">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const stars = document.querySelectorAll('.star-label');
                                        const inputs = document.querySelectorAll('input[name="rating"]');

                                        function updateStars(rating) {
                                            stars.forEach(star => {
                                                const starVal = parseInt(star.dataset.value);
                                                const icon = star.querySelector('i');
                                                if (starVal <= rating) {
                                                    icon.classList.remove('text-gray-300');
                                                    icon.classList.add('text-orange-500');
                                                } else {
                                                    icon.classList.add('text-gray-300');
                                                    icon.classList.remove('text-orange-500');
                                                }
                                            });
                                        }

                                        inputs.forEach(input => {
                                            input.addEventListener('change', function() {
                                                updateStars(this.value);
                                            });
                                        });

                                        // Optional: Hover effect
                                        const container = document.getElementById('star-rating-container');
                                        if (container) {
                                            container.addEventListener('mouseleave', function() {
                                                const checked = document.querySelector('input[name="rating"]:checked');
                                                const val = checked ? checked.value : 0;
                                                updateStars(val);
                                            });
                                        }

                                        stars.forEach(star => {
                                            star.addEventListener('mouseenter', function() {
                                                updateStars(this.dataset.value);
                                            });
                                        });
                                    });
                                </script>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Comment</label>
                                    <textarea name="comment" rows="4" class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-orange-500 outline-none" required placeholder="Share your experience..."></textarea>
                                </div>

                                <button type="submit" class="bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-orange-700 transition">
                                    Submit Review
                                </button>
                            </form>

                            <!-- Reclamation Section -->
                            <div class="mt-4 text-right">
                                <button onclick="document.getElementById('reclamation-form').classList.toggle('hidden')" class="text-sm text-gray-500 hover:text-red-500 underline">
                                    Report an issue with this stay
                                </button>

                                <form id="reclamation-form" action="../actions/add_reclamation.php" method="POST" class="hidden mt-4 bg-red-50 p-6 rounded-xl text-left border border-red-100">
                                    <h4 class="text-lg font-semibold text-red-700 mb-2">Report an Issue</h4>
                                    <input type="hidden" name="logement_id" value="<?= $logement->getId() ?>">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-medium mb-2">Describe the issue</label>
                                        <textarea name="message" rows="3" class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-500 outline-none" required placeholder="What went wrong?"></textarea>
                                    </div>
                                    <button type="submit" class="bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition">
                                        Submit Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Right Column: Reservation Sidebar -->
            <div class="relative">
                <div class="sticky top-28 bg-white border border-gray-200 rounded-xl shadow-xl p-6">
                    <div class="flex justify-between items-end mb-4">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold">$<?= number_format($logement->getPrice(), 2) ?></span>
                            <span class="text-gray-500">/ night</span>
                        </div>
                        <div class="text-sm text-gray-800 underline font-medium">
                            0 reviews
                        </div>
                    </div>

                    <form action="../actions/book.php" method="POST" id="bookingForm">
                        <input type="hidden" name="logement_id" value="<?= $logement->getId() ?>">
                        <input type="hidden" name="price_per_night" id="price_per_night" value="<?= $logement->getPrice() ?>">

                        <div class="border border-gray-400 rounded-lg overflow-hidden mb-4">
                            <div class="flex border-b border-gray-400">
                                <div class="flex-1 p-2 border-r border-gray-400">
                                    <label class="block text-xs font-bold uppercase text-gray-700">Check-in</label>
                                    <input type="date" name="start_date" id="start_date" required class="w-full text-sm outline-none cursor-pointer" min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="flex-1 p-2">
                                    <label class="block text-xs font-bold uppercase text-gray-700">Checkout</label>
                                    <input type="date" name="end_date" id="end_date" required class="w-full text-sm outline-none cursor-pointer" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                            <div class="p-2">
                                <label class="block text-xs font-bold uppercase text-gray-700">Guests</label>
                                <select name="guests" class="w-full text-sm outline-none bg-white cursor-pointer">
                                    <?php for ($i = 1; $i <= $logement->getGuestNum(); $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-orange-600 text-white font-semibold py-3 rounded-lg hover:bg-[#e00b41] transition duration-200 shadow-md transform active:scale-95">
                            Reserve
                        </button>
                    </form>

                    <p class="text-center text-gray-500 text-sm mt-3 mb-4">You won't be charged yet</p>

                    <div id="price-breakdown" class="hidden space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-gray-600 underline">
                            <span id="night-calc">$0 x 0 nights</span>
                            <span id="base-price">$0</span>
                        </div>
                        <div class="flex justify-between text-gray-600 underline">
                            <span>Cleaning fee</span>
                            <span>$15</span>
                        </div>
                        <div class="flex justify-between text-gray-600 underline">
                            <span>Vesta service fee</span>
                            <span>$25</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-3 border-t border-gray-200 mt-3">
                            <span>Total</span>
                            <span id="total-price">$0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 px-4 md:px-16">
                <div>
                    <div class="flex flex-col md:flex-row items-center space-x-2 mb-6">
                        <img class="w-16 h-16" src="/Logo_2.png" alt="Vesta">
                        <span class="text-2xl font-bold">Vesta</span>
                    </div>
                    <p class="text-gray-400 mb-6">Your trusted platform for short-term rentals and unforgettable travel experiences.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in text-xl"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-6">For Travelers</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Search Rentals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">How it Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Trust & Safety</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Travel Insurance</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Gift Cards</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-6">For Hosts</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">Become a Host</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Host Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Host Protection</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Community Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Hosting FAQs</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-6">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Press</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms & Privacy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <div class=" mt-10 pt-8 text-center text-black-400">
                <p>&copy; 2026 Vesta. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const pricePerNight = parseFloat(document.getElementById('price_per_night').value);
        const priceBreakdown = document.getElementById('price-breakdown');
        const nightCalc = document.getElementById('night-calc');
        const basePriceEl = document.getElementById('base-price');
        const totalPriceEl = document.getElementById('total-price');

        const cleaningFee = 15;
        const serviceFee = 25;

        function calculatePrice() {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);

            if (start && end && end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                const baseTotal = diffDays * pricePerNight;
                const grandTotal = baseTotal + cleaningFee + serviceFee;

                nightCalc.textContent = `$${pricePerNight} x ${diffDays} nights`;
                basePriceEl.textContent = `$${baseTotal}`;
                totalPriceEl.textContent = `$${grandTotal}`;

                priceBreakdown.classList.remove('hidden');
            } else {
                priceBreakdown.classList.add('hidden');
            }
        }

        startDateInput.addEventListener('change', function() {
            endDateInput.min = startDateInput.value;
            calculatePrice();
        });

        endDateInput.addEventListener('change', calculatePrice);

        const logementDates = <?php echo json_encode($logementDates); ?>;
        console.log(logementDates);
    </script>
</body>

</html>