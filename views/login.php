<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/DataBase.php'; 
 

use App\Repositories\UserRepository;    
use App\Services\UserService;

if (isset($_SESSION['user_id'])) {   
      header("Location: index.php");    
      exit;
}

$message = "";

if (isset($_POST['submit'])) {
    $identifier = $_POST['email']; 
    $password = $_POST['password'];

    $userRepository = new UserRepository($connect);
    $usersService = new UserService($userRepository);

    $user = $usersService->loginUser($identifier, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['Role'] = $user->getRole();
        if ($user->getRole() == 'Admin') {
            header('location:AdminDashboard.php');
            exit();
        }else if($user->getRole() == 'Hote') {
            header('location:HoteDashboard.php');
            exit();
        }else {
            header('location:logements.php');
            exit();
        }
    } else {
        $message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kari - Sign In</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/css/all/all.min.css">
    <link rel="icon" href="imgs/icon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="flex h-full w-full">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 h-full flex flex-col p-8 lg:p-6 overflow-y-auto justify-center">
            <div class="mb-8 absolute top-8 left-8">
                <a href="index.php" class="inline-flex items-center gap-2 text-gray-500 hover:text-orange-500 transition-colors"> 
                    <i class="fi fi-rr-arrow-small-left text-xl pt-1"></i>
                    <span class="font-medium">Back to Home</span>
                </a>
            </div>

            <div class="max-w-md mx-auto w-full">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome back</h1>
                    <p class="text-gray-500">Please enter your details to sign in.</p>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST" class="space-y-5">
                    
                    <!-- Username/Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Username or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fi fi-rr-user"></i>
                            </div>
                            <input type="text" name="email" id="email" placeholder="Enter your username or email" required
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
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                class="pl-10 w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        </div>
                        <div class="flex justify-end">
                            <a href="#" class="text-sm text-orange-500 hover:text-orange-600 font-medium">Forgot password?</a>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" name="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 duration-200">
                        Sign In
                    </button>

                    <p class="text-center text-gray-600 mt-6">
                        Don't have an account? 
                        <a href="Register.php" class="text-orange-500 font-semibold hover:text-orange-600 hover:underline">Sign up for free</a>
                    </p>
                </form>
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-400 absolute bottom-4 w-full left-0 lg:static lg:w-auto">
                &copy; <?php echo date('Y'); ?> Vesta. All rights reserved.
            </div>
        </div>
        
        <!-- Right Side - Image -->
        <div class="hidden lg:block w-1/2 h-full relative">
            <div class="absolute inset-0 bg-black/20 z-10"></div>
            <img src="../background.jpg" alt="Luxury Interior" class="w-full h-full object-cover">
            <div class="absolute bottom-10 left-10 z-20 text-white max-w-lg">
                <h2 class="text-4xl font-bold mb-4 drop-shadow-md">Welcome to your premium experience.</h2>
                <p class="text-lg drop-shadow-md text-gray-100">Log in to manage your bookings, wishlists, and more.</p>
            </div>
        </div>
    </div>
</body>
</html>