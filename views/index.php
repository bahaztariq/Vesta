<?php
session_start();

require_once dirname(__DIR__) . '/config/DataBase.php';
 require_once dirname(__DIR__) . '/Entities/favoire.php';
 require_once dirname(__DIR__) . '/repositories/FavouriteRepository.php';

 use App\Repositories\LogementRepository;
 use App\Repositories\FavouriteRepository;
 

 $logments = new LogementRepository($pdo);
 $topTens = $logments->getTopRated(10);
 
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
    <title>Vesta - Vacation Rentals & Short-Term Stays</title>
    <?php include 'partials/head_resources.php'; ?>
</head>
<body class="font-poppins bg-gray-50">
    <!-- Navigation -->
    <?php include 'partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="bg-[url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?q=80&w=2049&auto=format&fit=crop')] bg-cover bg-center min-h-[600px] relative flex flex-col justify-center items-center text-white pb-20">
        
        <div class="absolute inset-0 bg-black/40"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10 mb-12 mt-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg">Find Your Perfect Short-Term Stay</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto drop-shadow-md text-gray-100">Discover unique homes, apartments, and villas for your next vacation.</p>
        </div>

        <div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center">

            <div class="flex justify-center md:justify-start space-x-2 md:space-x-1 relative z-20 w-full md:w-auto md:ml-8">
                
                <label class="cursor-pointer bg-white/90 backdrop-blur-sm rounded-t-lg px-6 py-3 transition hover:bg-white has-[:checked]:bg-white has-[:checked]:text-orange-600">
                    <input type="radio" name="search_type" class="peer hidden" checked>
                    <div class="flex flex-col md:flex-row items-center space-x-2 text-gray-600 peer-checked:text-orange-600">
                        <i class="fa-solid fa-hotel"></i>
                        <span class="font-medium">Hotels</span>
                    </div>
                </label>
            
                <label class="cursor-pointer bg-white/90 backdrop-blur-sm rounded-t-lg px-6 py-3 transition hover:bg-white has-[:checked]:bg-white has-[:checked]:text-orange-600">
                    <input type="radio" name="search_type" class="peer hidden">
                    <div class="flex flex-col md:flex-row items-center space-x-2 text-gray-600 peer-checked:text-orange-600">
                        <i class="fa-solid fa-house"></i>
                        <span class="font-medium">Homes</span>
                    </div>
                </label>
            
                <label class="cursor-pointer bg-white/90 backdrop-blur-sm rounded-t-lg px-6 py-3 transition hover:bg-white has-[:checked]:bg-white has-[:checked]:text-orange-600">
                    <input type="radio" name="search_type" class="peer hidden">
                    <div class="flex flex-col md:flex-row items-center space-x-2 text-gray-600 peer-checked:text-orange-600">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span class="font-medium whitespace-nowrap">Long stays</span>
                    </div>
                </label>
                
                <label class="cursor-pointer bg-white/90 backdrop-blur-sm rounded-t-lg px-6 py-3 transition hover:bg-white has-[:checked]:bg-white has-[:checked]:text-orange-600">
                    <input type="radio" name="search_type" class="peer hidden">
                    <div class="flex flex-col md:flex-row items-center space-x-2 text-gray-600 peer-checked:text-orange-600">
                        <i class="fa-solid fa-car"></i>
                        <span class="font-medium whitespace-nowrap">Transfers</span>
                    </div>
                </label>
            </div>

            <div class="bg-white w-full rounded-2xl shadow-2xl p-4 md:p-12 pb-10 relative z-10">
                
                <div class="group border border-gray-300 rounded-lg flex items-center p-3 mb-4 hover:border-gray-400 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition bg-white">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-lg ml-2 group-focus-within:text-blue-500"></i>
                    <input type="text" placeholder="Enter a destination or property" class="w-full ml-4 text-gray-700 outline-none text-lg placeholder-gray-400 font-medium truncate">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="border border-gray-300 rounded-lg flex items-center p-3 hover:border-gray-400 focus-within:border-blue-500 transition relative bg-white">
                        
                        <div class="flex-1 flex flex-col justify-center border-r border-gray-200 pr-2">
                            <label class="text-xs text-gray-500 font-bold uppercase tracking-wider pl-1">Check-in</label>
                            <input type="date" class="w-full text-gray-700 font-bold text-sm md:text-base outline-none bg-transparent cursor-pointer uppercase">
                        </div>

                        <div class="flex-1 flex flex-col justify-center pl-4">
                            <label class="text-xs text-gray-500 font-bold uppercase tracking-wider pl-1">Check-out</label>
                            <input type="date" class="w-full text-gray-700 font-bold text-sm md:text-base outline-none bg-transparent cursor-pointer uppercase">
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-lg flex items-center px-4 py-2 hover:border-gray-400 focus-within:border-blue-500 transition relative bg-white">
                        <i class="fa-solid fa-user-group text-gray-500 text-xl absolute left-4 pointer-events-none"></i>
                        
                        <select class="w-full appearance-none bg-transparent outline-none text-gray-700 font-bold text-lg pl-10 cursor-pointer py-2">
                            <option value="1">1 adult</option>
                            <option value="2">2 adults</option>
                            <option value="3">2 adults, 1 child</option>
                            <option value="4">+4 adults · 2 rooms</option>
                        </select>

                        <i class="fa-solid fa-chevron-down text-gray-400 text-sm absolute right-4 pointer-events-none"></i>
                    </div>
                </div>

                <div class="absolute w-1/2  -bottom-6 left-1/2 transform -translate-x-1/2">
                    <button class="w-full bg-orange-600 hover:bg-orange-700 text-center text-white text-xl font-bold py-3 px-16 rounded-full shadow-lg shadow-orange-600/40 transition duration-200 uppercase tracking-wide flex items-center justify-center gap-2">
                        Search
                    </button>
                </div>

            </div>
        </div>
        
    </section>
    


    <!-- Featured Rentals Section -->
    <section id="rentals" class="p-2 bg-white md:p-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Our Top destinations</h2>
                    <p class="text-gray-600">Handpicked accommodations for an exceptional stay</p>
                </div>
                <div class="flex gap-2">
                    <button class="PrevSlide top-4 left-4 text-black  hover:bg-opacity-70 rounded-full w-10 h-10 flex items-center justify-center bg-orange-500">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button class="NextSlide top-4 left-4 text-black  hover:bg-opacity-70 rounded-full w-10 h-10 flex items-center justify-center bg-orange-500">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    
                </div>
            </div>
            
            <div class="swiper mySwiper w-full h-full">
                <div class="swiper-wrapper">
                    <?php foreach ($topTens as $logement): ?>
                    <div onclick="window.location.href='Rooms.php?id=<?= $logement->getId() ?>'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="<?= htmlspecialchars($logement->getImgPath()) ?>" alt="<?= htmlspecialchars($logement->getName()) ?>">
                                <div onclick="event.stopPropagation()" class="absolute top-3 right-3 text-white/70 hover:text-white transition">
                                    <label class="cursor-pointer">
                                        <input type="checkbox" class="hidden favorite-checkbox"
                                               data-id="<?= $logement->getId() ?>"
                                               <?= in_array($logement->getId(), $userFavorites) ? 'checked' : '' ?>
                                               onchange="toggleFavorite(this, <?= $logement->getId() ?>)">
                                        <i class="fa-regular fa-heart"></i>
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

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Tuscany, Italy</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.75</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Vineyard estate</div>
                            <div class="font-light text-neutral-500 text-sm">Oct 1 - 5</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">$420</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1613977257363-707ba9348227?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Paris, France</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.8</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Modern Apartment</div>
                            <div class="font-light text-neutral-500 text-sm">2 guests · 1 bedroom</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">€89</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1065&q=80" alt="Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Santorini, Greece</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.9</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Beachfront Villa</div>
                            <div class="font-light text-neutral-500 text-sm">6 guests · 3 bedrooms</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">€245</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Swiss Alps</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.7</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Cozy Cabin</div>
                            <div class="font-light text-neutral-500 text-sm">4 guests · 2 bedrooms</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">€120</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>
                    
                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Kyoto Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Kyoto, Japan</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.92</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Traditional Ryokan</div>
                            <div class="font-light text-neutral-500 text-sm">Nov 12 - 17</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">$180</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bali Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">Bali, Indonesia</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.85</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Jungle Treehouse</div>
                            <div class="font-light text-neutral-500 text-sm">Dec 5 - 10</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">$95</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='Rooms.php'" class="swiper-slide cursor-pointer group">
                        <div class="flex flex-col gap-2 w-full">
                            <div class="aspect-square w-full relative overflow-hidden rounded-xl">
                                <img class="object-cover h-full w-full group-hover:scale-110 transition duration-300" src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="NYC Listing">
                                <div onclick="event.stopPropagation()" class="absolute top-3 text-xl right-3 text-orange-600/70 hover:text-orange-600 transition">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between items-start pt-1">
                                <div class="font-semibold text-sm">New York, USA</div>
                                <div class="flex flex-row items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-black">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-light">4.6</span>
                                </div>
                            </div>
                            <div class="font-light text-neutral-500 text-sm">Downtown Loft</div>
                            <div class="font-light text-neutral-500 text-sm">Jan 10 - 15</div>
                            <div class="flex flex-row items-center gap-1 mt-1">
                                <div class="font-semibold text-sm">$350</div>
                                <div class="font-light text-sm">night</div>
                            </div>
                        </div>
                    </div>
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

            fetch('actions/toggle_favorite.php', {
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

    
        document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.mySwiper', {
                slidesPerView: 2,
                spaceBetween: 32,
                loop: true,
                navigation: {
                    nextEl: '.NextSlide',
                    prevEl: '.PrevSlide',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 4,
                        spaceBetween: 32,
                    },
                    1024: {
                        slidesPerView: 6,
                        spaceBetween: 32,
                    },
                },
            });

            var testimonialSwiper = new Swiper('.testimonialSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: '.testi-next',
                    prevEl: '.testi-prev',
                },
                breakpoints: {
                     768: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                }
            });
        });
    </script>
        </div>
    </section>

    <!-- Value Proposition / How It Works -->
    <section id="how-it-works" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="text-orange-600 font-semibold tracking-wider uppercase text-sm">Why Choose Vesta</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">Experience the Vesta Difference</h2>
                <p class="text-gray-500">We don't just find you a place to stay; we curate experiences that turn into lifelong memories.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Feature 1 -->
                <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-magnifying-glass-location"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Seamless Discovery</h3>
                    <p class="text-gray-500 leading-relaxed">Filter by what matters—views, wifi speed, or verified quiet zones. Find your perfect match in seconds.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-regular fa-credit-card"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Flexible</h3>
                    <p class="text-gray-500 leading-relaxed">Book with confidence using our encrypted payment system. Enjoy free cancellation on most stays.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                    <p class="text-gray-500 leading-relaxed">From check-in to check-out, our global support team is just a message away, anytime, anywhere.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section (Swiper) -->
    <section id="testimonials" class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                     <span class="text-orange-500 font-semibold tracking-wider uppercase text-sm">Testimonials</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2">Loved by Travelers</h2>
                </div>
                
                <!-- Swiper Navigation Buttons -->
                <div class="flex gap-4 mt-6 md:mt-0">
                    <button class="testi-prev w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center hover:bg-orange-600 hover:border-orange-600 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <button class="testi-next w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center hover:bg-orange-600 hover:border-orange-600 transition-colors">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="swiper testimonialSwiper pb-12">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="bg-gray-800 p-8 rounded-2xl h-full border border-gray-700 hover:border-gray-600 transition-colors">
                            <div class="flex text-orange-500 mb-6 text-sm">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-gray-300 text-lg italic mb-6">"The Paris apartment was even better than the photos! The host was incredibly responsive and gave us great local recommendations. We'll definitely use Vesta again!"</p>
                            <div class="flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah L." class="w-12 h-12 rounded-full object-cover border-2 border-gray-700">
                                <div>
                                    <h4 class="font-bold text-white">Sarah Jenkins</h4>
                                    <p class="text-xs text-gray-400">Stayed in Paris, France</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="bg-gray-800 p-8 rounded-2xl h-full border border-gray-700 hover:border-gray-600 transition-colors">
                            <div class="flex text-orange-500 mb-6 text-sm">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-gray-300 text-lg italic mb-6">"As a host, Vesta has made managing my rental property so much easier. The booking system is seamless, and I love the automated notifications."</p>
                            <div class="flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Michael T." class="w-12 h-12 rounded-full object-cover border-2 border-gray-700">
                                <div>
                                    <h4 class="font-bold text-white">Michael Thompson</h4>
                                    <p class="text-xs text-gray-400">Host in Austin, USA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="bg-gray-800 p-8 rounded-2xl h-full border border-gray-700 hover:border-gray-600 transition-colors">
                            <div class="flex text-orange-500 mb-6 text-sm">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-gray-300 text-lg italic mb-6">"The cabin in the Alps was a dream! The booking process was straightforward, and the customer support team helped quickly when I had a question."</p>
                            <div class="flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Jessica R." class="w-12 h-12 rounded-full object-cover border-2 border-gray-700">
                                <div>
                                    <h4 class="font-bold text-white">Jessica Reynolds</h4>
                                    <p class="text-xs text-gray-400">Stayed in Swiss Alps</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 4 -->
                     <div class="swiper-slide">
                        <div class="bg-gray-800 p-8 rounded-2xl h-full border border-gray-700 hover:border-gray-600 transition-colors">
                            <div class="flex text-orange-500 mb-6 text-sm">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-alt"></i>
                            </div>
                            <p class="text-gray-300 text-lg italic mb-6">"Found a hidden gem in Kyoto that wasn't on other platforms. The unique filter options really helped me find exactly what I needed for my remote work trip."</p>
                            <div class="flex items-center gap-4">
                                <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="David C." class="w-12 h-12 rounded-full object-cover border-2 border-gray-700">
                                <div>
                                    <h4 class="font-bold text-white">David Chen</h4>
                                    <p class="text-xs text-gray-400">Stayed in Kyoto, Japan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination mt-8"></div>
            </div>
        </div>
    </section>

    <!-- Visual Stats / Trust Section -->
    <section class="relative py-24 bg-fixed bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=2021&q=80');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center text-white">
            <h2 class="text-3xl md:text-5xl font-bold mb-16">Creating Every Stay Count</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="p-4 backdrop-blur-sm bg-white/10 rounded-xl border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold mb-2 text-orange-500">50k+</div>
                    <div class="text-sm md:text-base font-medium text-gray-200">Properties</div>
                </div>
                <div class="p-4 backdrop-blur-sm bg-white/10 rounded-xl border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold mb-2 text-orange-500">120+</div>
                    <div class="text-sm md:text-base font-medium text-gray-200">Countries</div>
                </div>
                <div class="p-4 backdrop-blur-sm bg-white/10 rounded-xl border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold mb-2 text-orange-500">500k+</div>
                    <div class="text-sm md:text-base font-medium text-gray-200">Travelers</div>
                </div>
                <div class="p-4 backdrop-blur-sm bg-white/10 rounded-xl border border-white/20">
                    <div class="text-4xl md:text-5xl font-bold mb-2 text-orange-500">4.8</div>
                    <div class="text-sm md:text-base font-medium text-gray-200">Avg Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA / Newsletter Section -->
    <section class="py-20 bg-orange-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pattern-dots"></div> <!-- Minimal pattern placeholder -->
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready for your next adventure?</h2>
            <p class="text-orange-100 text-lg mb-10 max-w-2xl mx-auto">Join our community and get exclusive access to hidden gems and last-minute deals.</p>
            
            <form class="max-w-md mx-auto flex flex-col md:flex-row gap-4">
                <input type="email" placeholder="Your email address" class="flex-1 px-6 py-4 rounded-full outline-none text-gray-800 shadow-lg focus:ring-4 focus:ring-orange-700/50 transition">
                <button type="button" class="bg-gray-900 text-white font-bold px-8 py-4 rounded-full text-lg shadow-lg hover:bg-black hover:scale-105 transition duration-300">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
