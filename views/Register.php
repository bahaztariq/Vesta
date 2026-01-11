<?php
// require '../db/db_connect.php';
// require '../sendemail.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/DataBase.php';

use App\Repositories\UserRepository;

if(isset($_POST['submit'])){
    $fullname = $_POST['Fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role_selection'] ?? 'voyageur';

    $nameParts = explode(' ', $fullname, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    $userRepository = new UserRepository($connect);
    
    if ($userRepository->register($firstName, $lastName, $username, $email, $password, $role)) {
         echo "<script>alert('Registration successful!');</script>"; 
         
         $user = $userRepository->findByEmail($email);
         if ($user) {
             $_SESSION['user_id'] = $user->getId();
             $_SESSION['username'] = $user->getUsername();
             $_SESSION['Role'] = $user->getRole();
             header('location:logements.php');
             exit();
         }
    } else {
        echo "<script>alert('Registration failed. Email or Username might already exist.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kari - Sign Up</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/css/all/all.min.css">
    <link rel="icon" href="imgs/icon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom radio button styling */
        .role-radio:checked + div {
            border-color: #f97316; /* orange-500 */
            background-color: #fff7ed; /* orange-50 */
            color: #f97316;
        }
        .role-radio:checked + div i {
            color: #f97316;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="flex h-full w-full">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 h-full flex flex-col p-8  lg:p-6 lg:pb-0  overflow-y-auto">
            <div class="mb-4">
                <a href="index.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-500 transition-colors"> 
                    <i class="fi fi-rr-arrow-small-left text-xl pt-1"></i>
                    <span class="font-medium">Back to Home</span>
                </a>
            </div>

            <div class=" flex flex-col justify-center max-w-lg mx-auto w-full">
                <div class="mb-4">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Create an account</h1>
                    <p class="text-gray-500">Join Vesta today and start your journey.</p>
                </div>

                <form action="" method="POST" class="space-y-2">
                    
                    <!-- Role Selection -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">I want to join as a...</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Hote -->
                            <label class="cursor-pointer relative">
                                <input type="radio" name="role_selection" value="host" class="role-radio hidden">
                                <div class="border-2 border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all hover:border-orange-300 h-32 text-gray-400">
                                    <i class="fi fi-rr-house-chimney text-3xl"></i>
                                    <span class="font-semibold text-gray-700">Hôte</span>
                                </div>
                            </label>
                            <!-- Voyageur -->
                            <label class="cursor-pointer relative">
                                <input type="radio" name="role_selection" value="voyageur" class="role-radio hidden" checked>
                                <div class="border-2 border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all hover:border-orange-300 h-32 text-gray-400">
                                    <i class="fi fi-rr-backpack text-3xl"></i>
                                    <span class="font-semibold text-gray-700">Voyageur</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Names -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="Fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fi fi-rr-user"></i>
                                </div>
                                <input type="text" name="Fullname" id="Fullname" placeholder="John Doe" required
                                    class="pl-10 w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fi fi-rr-at"></i>
                                </div>
                                <input type="text" name="username" id="username" placeholder="johndoe123" required
                                    class="pl-10 w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fi fi-rr-envelope"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="john@example.com" required
                                class="pl-10 w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fi fi-rr-lock"></i>
                            </div>
                            <input type="password" name="password" id="password" placeholder="Min. 8 characters" required
                                class="pl-10 w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" name="submit" class="mt-2 w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 duration-200">
                        Create Account
                    </button>

                    <p class="text-center text-gray-600 mt-2">
                        Already have an account? 
                        <a href="login.php" class="text-orange-500 font-semibold hover:text-orange-600 hover:underline">Sign in</a>
                    </p>
                </form>
            </div>
            
            <div class="mt-2 text-center text-xs text-gray-400">
                &copy; <?php echo date('Y'); ?> Kari. All rights reserved.
            </div>
        </div>
        
        <!-- Right Side - Image -->
        <div class="hidden lg:block w-1/2 h-full relative">
            <div class="absolute inset-0 bg-black/10 z-10"></div>
            <img src="../vv.jpg" alt="Beautiful landscape" class="w-full h-full object-cover">
            <div class="absolute bottom-10 left-10 z-20 text-white max-w-lg">
                <h2 class="text-4xl font-bold mb-4 drop-shadow-md">Find your next adventure.</h2>
                <p class="text-lg drop-shadow-md text-gray-100">Join our community of travelers and hosts to discover unique places around the world.</p>
            </div>
        </div>
    </div>
</body>
</html>