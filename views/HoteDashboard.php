<?php
session_start();


// if (!isset($_SESSION['UserID'])) {
//     header("Location: login.php");
//     exit();
// }

require_once dirname(__DIR__) . '/config/DataBase.php';
require_once dirname(__DIR__) . '/repositories/LogementRepository.php';

use App\Repositories\LogementRepository;

// $userId = $_SESSION['UserID'] ; 
$repo = new LogementRepository($pdo);
$message = '';
$messageType = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_listing') {
    $name = htmlspecialchars($_POST['name']);
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $price = (float) $_POST['price'];
    $description = htmlspecialchars($_POST['description']);
    $guestNum = (int) $_POST['guestNum'];
    
    // File Upload Handling
    $imgPath = '';
    if (isset($_FILES['imagePath']) && $_FILES['imagePath']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__DIR__) . '/uploads/Rooms/'; 
        $fileInfo = pathinfo($_FILES['imagePath']['name']);
        $extension = strtolower($fileInfo['extension']);
        $newFileName = uniqid('room_', true) . '.' . $extension;
        $destination = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['imagePath']['tmp_name'], $destination)) {
                $imgPath = '/uploads/Rooms/' . $newFileName;
            }else{
                $message = "Failed to upload image.";
                $messageType = "error";
            }
    } else {
        $message = "Please upload an image.";
        $messageType = "error";
    }

    if (!$message && $imgPath) {
        if ($repo->save($userId, $name, $country, $city, $price, $imgPath, $description, $guestNum)) {
            $message = "Listing added successfully!";
            $messageType = "success";
        } else {
            $message = "Failed to add listing to database.";
            $messageType = "error";
        }
    }
}

// Fetch Listings
$myListings = $repo->findByHostId(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Dashboard - Vesta</title>
    <?php include 'partials/head_resources.php'; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        orange: {
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-poppins bg-gray-50 flex flex-col h-screen overflow-hidden">

    <!-- Global Navbar -->
    <?php include 'components/navbar.php'; ?>

    <!-- Dashboard Content -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-xl flex flex-col z-10 transition-transform duration-300 absolute md:relative md:translate-x-0 h-full border-r border-gray-100" id="sidebar">
            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                <button onclick="showSection('dashboard')" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-600 transition-colors group active-nav-link" id="nav-dashboard">
                    <i class="fas fa-th-large group-hover:text-orange-600"></i>
                    <span class="font-medium">My Listings</span>
                </button>
                <button onclick="showSection('add-listing')" class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-600 transition-colors group" id="nav-add-listing">
                    <i class="fas fa-plus-circle group-hover:text-orange-600"></i>
                    <span class="font-medium">Add New Listing</span>
                </button>
            </nav>

            <div class="p-4 border-t border-gray-100">
             <!-- Logout button removed as it is in the global header, but kept here for convenience if desired, or we can remove it. Keeping for now as bottom utility. -->
                <a href="logout.php" class="flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Header (Simplified) -->
            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10">
                <button class="md:hidden text-gray-600 focus:outline-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-gray-800" id="page-title">My Listings</h1>
                <!-- User info removed as it is in global navbar -->
            </header>

        <!-- Content Srcollable Area -->
        <div class="flex-1 overflow-y-auto p-6 md:p-8 bg-gray-50">
            
            <?php if ($message): ?>
            <script>
                Toastify({
                    text: "<?php echo $message; ?>",
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    backgroundColor: "<?php echo $messageType === 'success' ? '#22c55e' : '#ef4444'; ?>",
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();
            </script>
            <?php endif; ?>

            <!-- Dashboard / Listings Section -->
            <section id="section-dashboard" class="space-y-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Your Accommodations</h2>
                        <p class="text-gray-500">Manage your current listings</p>
                    </div>
                    <button onclick="showSection('add-listing')" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Add Listing
                    </button>
                </div>

                <?php if (empty($myListings)): ?>
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-orange-300 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-800 mb-2">No listings yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start your hosting journey by adding your first property today.</p>
                    <button onclick="showSection('add-listing')" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors font-medium shadow-md">
                        Add Your First Listing
                    </button>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($myListings as $listing): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($listing->getImgPath()); ?>" alt="<?php echo htmlspecialchars($listing->getName()); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-semibold text-gray-800 shadow-sm">
                                $<?php echo htmlspecialchars($listing->getPrice()); ?>/night
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($listing->getName()); ?></h3>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm mb-4 space-x-3">
                                <span class="flex items-center"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($listing->getCity()); ?>, <?php echo htmlspecialchars($listing->getCountry()); ?></span>
                            </div>
                            <div class="flex items-center text-gray-500 text-xs mb-4 space-x-3 border-t border-gray-50 pt-3">
                                <span class="flex items-center"><i class="fas fa-user-friends mr-1"></i> <?php echo htmlspecialchars($listing->getGuestNum()); ?> Guests</span>
                            </div>
                            
                            <div class="flex space-x-2 mt-4">
                                <button class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-200">
                                    Edit
                                </button>
                                <button class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg text-sm font-medium transition-colors border border-red-100">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <!-- Add Listing Section -->
            <section id="section-add-listing" class="hidden max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-xl font-bold text-gray-800">Add New Listing</h2>
                        <p class="text-gray-500 text-sm mt-1">Fill in the details to publish your new accommodation.</p>
                    </div>
                    
                    <form action="" method="POST" class="p-6 md:p-8 space-y-6" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_listing">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Name</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="e.g. Cozy Sunset Villa">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <input type="text" name="country" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="e.g. Italy">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" name="city" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="e.g. Rome">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night ($)</label>
                                    <input type="number" name="price" step="0.01" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Guests</label>
                                    <input type="number" name="guestNum" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="e.g. 4">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                <input type="file" name="imagePath" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Provide a direct link to an image of the property.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="4" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-400" placeholder="Describe the unique features of your place..."></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-50">
                            <button type="button" onclick="showSection('dashboard')" class="mr-4 px-6 py-2 text-gray-600 font-medium hover:text-gray-800 transition-colors">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 shadow-md transform active:scale-95 transition-all">
                                Publish Listing
                            </button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('section-dashboard').classList.add('hidden');
            document.getElementById('section-add-listing').classList.add('hidden');
            
            // Show requested section
            document.getElementById('section-' + sectionId).classList.remove('hidden');

            // Update Header Title
            const titles = {
                'dashboard': 'My Listings',
                'add-listing': 'Add New Listing'
            };
            document.getElementById('page-title').innerText = titles[sectionId];

            // Update Sidebar Active State
            document.querySelectorAll('nav button').forEach(btn => {
                btn.classList.remove('bg-orange-50', 'text-orange-600');
                btn.classList.add('text-gray-700');
                btn.querySelector('i').classList.remove('text-orange-600');
            });
            
            const activeBtn = document.getElementById('nav-' + sectionId);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-700');
                activeBtn.classList.add('bg-orange-50', 'text-orange-600');
                activeBtn.querySelector('i').classList.add('text-orange-600');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('absolute');
        }

        // Initialize with correct active state
        showSection('dashboard');
    </script>
    </div> <!-- Close dashboard wrapper -->
</body>
</html>
