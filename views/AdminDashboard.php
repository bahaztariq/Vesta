<?php

namespace App\Entities;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/DataBase.php';


use App\Repositories\UserRepository;
use App\Repositories\LogementRepository;
use App\Repositories\ReclamationRepository;
use App\Repositories\SystemLogRepository;

session_start();
// if(!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'Admin'){
//      header("location:index.php");
// }

$userRepo = new UserRepository($pdo);
$logementRepo = new LogementRepository($pdo);
$reclamationRepo = new ReclamationRepository($pdo);

$totalUsers = $userRepo->countTotalUsers();
$totalHosts = $userRepo->countHosts();
$totalLogements = $logementRepo->countTotal();

$reclamations = $reclamationRepo->findAll();


?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Admin Dashboard</title>
     <?php include 'components/head_resources.php'; ?>
     <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex flex-col h-screen overflow-hidden">
    <!-- Global Navbar -->
    <?php include 'components/navbar.php'; ?>
    
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md hidden md:flex flex-col z-20 border-r border-gray-100">
            <nav class="flex-1 p-4 space-y-2 mt-4">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 bg-orange-50 rounded-lg">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Reclamations</span>
                </a>
                 
            </nav>
            <div class="p-4 border-t border-gray-100">
               <a href="logout.php" class="flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt"></i>
                <span class="font-medium">Logout</span>
               </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10">
                <h2 class="text-xl font-semibold text-gray-800">Overview</h2>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalUsers ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                     <!-- Total Hosts -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Hosts</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalHosts ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                        </div>
                    </div>
                     <!-- Total Logements -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Logements</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalLogements ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                                <i class="fas fa-home text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reclamations Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Recent Reclamations</h3>
                        <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full"><?= count($reclamations) ?> Pending</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 font-medium">User ID</th>
                                    <th class="px-6 py-3 font-medium">Logement ID</th>
                                    <th class="px-6 py-3 font-medium">Message</th>
                                    <th class="px-6 py-3 font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (empty($reclamations)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-400">No reclamations found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($reclamations as $rec): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3"><?= htmlspecialchars($rec->getUserId()) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($rec->getLogementId()) ?></td>
                                        <td class="px-6 py-3 text-gray-800"><?= htmlspecialchars(substr($rec->getMessage(), 0, 50)) . (strlen($rec->getMessage()) > 50 ? '...' : '') ?></td>
                                        <td class="px-6 py-3">
                                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">View Details</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
          </main>
        </div>
    </div>
</body>
</html>