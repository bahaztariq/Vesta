    <nav class="bg-white shadow-md relative z-50">
        <div class="container mx-auto px-4 md:px-16 py-3 flex justify-between  items-center">
            <div class="flex flex-col md:flex-row items-center space-x-2">
                <img class="w-16 h-16" src="/Logo_2.png" alt="Vesta">
                <a href="index.php" class="text-2xl font-bold text-orange-600">Vesta</a>
            </div>
            
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="flex items-center space-x-4">
                <a href="login.php" class="text-gray-700 hover:text-orange-600">Log in</a>
                <a href="Register.php" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition duration-300">Sign up</a>
                <button id="menu-toggle" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="flex items-center space-x-6">
                <!-- Notifications Dropdown -->
                <div class="relative">
                    <button id="notification-btn" class="relative w-10 h-10 text-orange-600 text-2xl rounded-full hover:bg-orange-50 transition flex justify-center items-center focus:outline-none">
                        <i class="fa-regular fa-bell"></i>
                        <!-- Notification Dot (optional/dynamic) -->
                         <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform origin-top-right transition-all duration-200">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-semibold text-gray-800">Notifications</h3>
                            <button class="text-xs text-orange-600 hover:text-orange-700 font-medium">Mark all read</button>
                        </div>
                        <div class="max-h-[20rem] overflow-y-auto custom-scrollbar">
                            <!-- Empty State -->
                            <div class="flex flex-col items-center justify-center p-8 text-center text-gray-500">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-regular fa-bell-slash text-xl text-gray-400"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-600">No new notifications</p>
                                <p class="text-xs text-gray-400 mt-1">We'll let you know when something important happens.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button id="user-menu-btn" class="group flex items-center gap-2 focus:outline-none">
                         <div class="w-10 h-10 text-white text-xl bg-orange-600 rounded-full flex justify-center items-center shadow-md group-hover:shadow-lg transition-all duration-300 ring-2 ring-transparent group-hover:ring-orange-200">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300 group-hover:text-orange-600 group-aria-expanded:rotate-180"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all duration-200">
                        <div class="px-5 py-4 border-b border-gray-100 mb-2">
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Signed in as</p>
                            <p class="text-sm font-bold text-gray-900 truncate"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?></p>
                        </div>
                        
                        <div class="px-2 space-y-1">
                            <a href="./profil.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                My Profile
                            </a>
                            
                            <?php if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'Hote'): ?>
                            <a href="./HoteDashboard.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                Host Dashboard
                            </a>
                            <?php elseif (isset($_SESSION['Role']) && $_SESSION['Role'] === 'Admin'): ?>
                             <a href="./AdminDashboard.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                Admin Dashboard
                            </a>
                            <?php else: ?>
                             <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                My History
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="border-t border-gray-100 my-2 pt-2 px-2">
                            <a href="logout.php" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </div>
                                Log out
                            </a>
                        </div>
                    </div>
                </div>
                
                <button id="menu-toggle" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white px-4 py-3 shadow-md">
            <a href="index.html" class="block py-2 text-orange-600 font-semibold">Home</a>
            <a href="#rentals" class="block py-2 text-gray-700">Rentals</a>
            <a href="#how-it-works" class="block py-2 text-gray-700">How it works</a>
            <a href="#testimonials" class="block py-2 text-gray-700">Testimonials</a>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown Logic
            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-dropdown');
            const notifBtn = document.getElementById('notification-btn');
            const notifDropdown = document.getElementById('notification-dropdown');

            function closeDropdowns() {
                if(userDropdown) userDropdown.classList.add('hidden');
                if(notifDropdown) notifDropdown.classList.add('hidden');
            }

            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = userDropdown.classList.contains('hidden');
                    closeDropdowns(); // Close others
                    if (isHidden) {
                        userDropdown.classList.remove('hidden');
                    }
                });
            }

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = notifDropdown.classList.contains('hidden');
                    closeDropdowns(); // Close others
                    if (isHidden) {
                        notifDropdown.classList.remove('hidden');
                    }
                });
            }

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (userDropdown && !userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
                if (notifDropdown && !notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                    notifDropdown.classList.add('hidden');
                }
            });

            // Prevent closing when clicking inside the dropdowns
            if(userDropdown) {
                userDropdown.addEventListener('click', (e) => e.stopPropagation());
            }
            if(notifDropdown) {
                notifDropdown.addEventListener('click', (e) => e.stopPropagation());
            }
        });
    </script>
