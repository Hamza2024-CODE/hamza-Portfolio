<?php
// Set dynamic metadata context before loading layouts
$pageTitle = "You are Offline - Hem B. Khatri";
include "includes/header.php";
?>

<div class="w-full max-w-2xl mx-auto py-16 flex flex-col items-center justify-center text-center px-4">
    <!-- Premium Offline SVG Illustration -->
    <div class="mb-8 text-teal-600 dark:text-teal-400 animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 mx-auto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
        </svg>
    </div>

    <!-- Title and message -->
    <h1 class="font-headline text-3xl md:text-4xl font-bold tracking-tight text-gray-900 dark:text-white mb-4">
        You're Offline
    </h1>
    <p class="font-body text-gray-600 dark:text-gray-400 text-base md:text-lg leading-relaxed max-w-md mb-10">
        It seems your network connection went for a hike in the Himalayas. You can still navigate cached parts of the portfolio.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a href="index.php" class="px-6 py-3 rounded-xl text-sm font-semibold bg-teal-600 hover:bg-teal-500 text-white shadow-md hover:shadow-lg transition-all duration-200">
            Go Back Home
        </a>
        <button onclick="window.location.reload()" class="px-6 py-3 rounded-xl text-sm font-semibold bg-gray-200 hover:bg-gray-300 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-gray-800 dark:text-zinc-200 border border-gray-300/50 dark:border-gray-700/50 transition-all duration-200">
            Retry Connection
        </button>
    </div>
</div>

<?php 
include "includes/footer.php"; 
?>
