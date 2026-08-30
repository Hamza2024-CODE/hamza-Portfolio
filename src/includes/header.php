<?php
// Get the current file name to handle active links automatically
$current_page = basename($_SERVER['PHP_SELF']);

// Calculate dynamic base path to support both subdirectories (XAMPP) and production domain root

// Calculate dynamic base path to support both subdirectories (XAMPP) and production domain root
$base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Strip out the '/public' directory and anything after it to get the true application root
if (($pos = strpos($base_path, '/public')) !== false) {
    $base_path = substr($base_path, 0, $pos);
}

// Ensure the path always ends with a single trailing slash
$base_path = rtrim($base_path, '/') . '/';

// Ensure database connectivity for dynamic settings
if (!isset($pdo)) {
    $db_config_file = __DIR__ . '/../../config/dbconfig.php';
    if (file_exists($db_config_file)) {
        require_once $db_config_file;
    }
}

$site_settings = [];
try {
    if (isset($pdo)) {
        $settings_stmt = $pdo->query("SELECT * FROM settings");
        if ($settings_stmt) {
            foreach ($settings_stmt->fetchAll() as $s) {
                $site_settings[$s['key_name']] = $s['key_value'];
            }
        }
    }
} catch (Exception $e) {
    // Fallback if table not initialized
}

$site_name_ar = $site_settings['site_name_ar'] ?? 'حمزة بوبكر الصديق';
$site_name_en = $site_settings['site_name_en'] ?? 'Hamza Boubakar Seddik';
$site_title_ar = $site_settings['site_title_ar'] ?? 'مهندس برمجيات ومطور متعدد المنصات';
$site_title_en = $site_settings['site_title_en'] ?? 'Software Engineer & Multi-Platform Developer';
$site_institution_ar = $site_settings['site_institution_ar'] ?? 'وزارة التكوين والتعليم المهنيين (MFEP) | الجزائر العاصمة';
$site_institution_en = $site_settings['site_institution_en'] ?? 'Ministry of Vocational Training & Education (MFEP) | Algiers, Algeria';
$site_email = $site_settings['site_email'] ?? 'boubakarseddikh@gmail.com';
$site_phone = $site_settings['site_phone'] ?? '+213 779771993';
$site_github = $site_settings['site_github'] ?? 'https://github.com/Hamza2024-CODE';
$site_linkedin = $site_settings['site_linkedin'] ?? 'https://www.linkedin.com/in/hamza-boubakare-seddike';


$is_spa = isset($_SERVER['HTTP_X_SPA_REQUEST']) && $_SERVER['HTTP_X_SPA_REQUEST'] === 'true';
if ($is_spa) {
    $spa_title = isset($pageTitle) ? $pageTitle : 'Hamza Boubakar Seddik — Software Engineer & Multi-Platform Developer';
    header("X-SPA-Title: " . $spa_title);
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?php echo $base_path; ?>">

        <?php
        // ============================================================
        // DYNAMIC SEO METADATA SYSTEM
        // Pages set $pageMeta[] before including this file.
        // All fields below fall back to safe site-level defaults.
        // ============================================================
    
        // --- Resolve canonical URL ---
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'github.com/Hamza2024-CODE';
        $req_uri = $_SERVER['REQUEST_URI'] ?? '/';
        $canonical = $pageMeta['canonical'] ?? ($protocol . '://' . $host . $req_uri);

        // --- Default meta values (site-level optimized for client acquisition) ---
        $meta_title = isset($pageTitle) ? $pageTitle : 'Hamza Boubakar Seddik | Software Engineer & Multi-Platform Developer';
        $meta_description = $pageMeta['description']
            ?? 'Hamza Boubakar Seddik — Software Engineer & Multi-Platform Developer at Ministry of Vocational Training and Education (MFEP), specializing in Laravel, C#, ASP.NET, Java, and enterprise software systems.';
        $meta_keywords = $pageMeta['keywords']
            ?? 'Hamza Boubakar Seddik, Software Engineer Algeria, Multi-Platform Developer, MFEP Algeria, Laravel, C#, ASP.NET, Java, REST APIs, WSAP, Tassyir ERP';
        $meta_og_type = $pageMeta['og_type'] ?? 'website';

        // --- High-Quality Social Share Image ---
        $meta_og_image = $pageMeta['og_image'] ?? ($protocol . '://' . $host . '/public/assets/images/og-banner.png');
        $meta_og_image_alt = $pageMeta['og_image_alt'] ?? 'Hamza Boubakar Seddik — Software Engineer & Multi-Platform Developer';

        $meta_robots = $pageMeta['robots'] ?? 'index, follow';
        $meta_author = $pageMeta['author'] ?? 'Hamza Boubakar Seddik';
        $meta_published = $pageMeta['published'] ?? null;
        $meta_modified = $pageMeta['modified'] ?? null;

        // --- JSON-LD Structured Data ---
        $jsonld_type = $pageMeta['jsonld_type'] ?? 'Person';
        if ($jsonld_type === 'Article' && isset($pageMeta['jsonld_article'])) {
            $ld = $pageMeta['jsonld_article'];
        } else {
            $ld = [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => 'Hamza Boubakar Seddik',
                'description' => 'Software Engineer & Multi-Platform Developer specialized in enterprise applications, Laravel, C#, ASP.NET, and digital systems.',
                'url' => 'https://github.com/Hamza2024-CODE',
                'jobTitle' => 'Software Engineer & Multi-Platform Developer',
                'worksFor' => [
                    '@type' => 'Organization',
                    'name' => 'merojob'
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Kathmandu',
                    'addressCountry' => 'NP'
                ],
                'knowsAbout' => [
                    'Python',
                    'Django',
                    'PostgreSQL',
                    'RESTful APIs',
                    'Software Architecture',
                    'Full-Stack Development',
                    'Backend Engineering'
                ],
                'sameAs' => [
                    'https://github.com/hemkhatri',
                    'https://www.linkedin.com/in/hemlex',
                ],
            ];
        }
        ?>


        <!-- Primary SEO Meta Tags -->
        <title><?php echo htmlspecialchars($meta_title); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
        <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
        <meta name="author" content="<?php echo htmlspecialchars($meta_author); ?>">
        <meta name="robots" content="<?php echo htmlspecialchars($meta_robots); ?>">
        <link rel="canonical" href="<?php echo htmlspecialchars($canonical); ?>">

        <?php if ($meta_published): ?>
            <meta name="article:published_time" content="<?php echo htmlspecialchars($meta_published); ?>">
        <?php endif; ?>
        <?php if ($meta_modified): ?>
            <meta name="article:modified_time" content="<?php echo htmlspecialchars($meta_modified); ?>">
        <?php endif; ?>

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="<?php echo htmlspecialchars($meta_og_type); ?>">
        <meta property="og:url" content="<?php echo htmlspecialchars($canonical); ?>">
        <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
        <meta property="og:image" content="<?php echo htmlspecialchars($meta_og_image); ?>">
        <meta property="og:image:alt" content="<?php echo htmlspecialchars($meta_og_image_alt); ?>">
        <meta property="og:site_name" content="Hem B. Khatri Portfolio">
        <meta property="og:locale" content="en_US">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="<?php echo htmlspecialchars($canonical); ?>">
        <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title); ?>">
        <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description); ?>">
        <meta name="twitter:image" content="<?php echo htmlspecialchars($meta_og_image); ?>">
        <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($meta_og_image_alt); ?>">
        <meta name="twitter:creator" content="@hemkhatri">
        <meta name="twitter:site" content="@hemkhatri">

        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
            <?php echo json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
            </script>

        <!-- Favicon Import -->
        <link rel="icon" type="image/png" href="public/assets/favicon/favicon.png">

        <!-- Import Google Fonts -->
        <link rel="preconnect" href="https://googleapis.com">
        <link rel="preconnect" href="https://gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Alexandria:wght@300;400;600;700;800;900&family=Karla:ital,wght@0,200..800;1,200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Tailwind CSS CDN -->
        <script>
            function toggleTheme() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
                updateThemeIcons();
            }

            function updateThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                const sunIcon = document.getElementById('theme-toggle-sun-icon');
                const moonIcon = document.getElementById('theme-toggle-moon-icon');
                if (sunIcon && moonIcon) {
                    if (isDark) {
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    } else {
                        moonIcon.classList.remove('hidden');
                        sunIcon.classList.add('hidden');
                    }
                }
            }

            if (localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            document.addEventListener('DOMContentLoaded', updateThemeIcons);
        </script>

        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Tailwind Configuration -->
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            brandPrimary: '#009688',
                            brandNeutral: '#0F172A',
                        },
                        fontFamily: {
                            sans: ['"Montserrat"', '"Cairo"', 'sans-serif'],
                            headline: ['"Karla"', '"Alexandria"', 'sans-serif'],
                            body: ['"Open Sans"', '"Cairo"', 'sans-serif'],
                            arabic: ['"Cairo"', '"Alexandria"', 'sans-serif']
                        }
                    }
                }
            }
        </script>
        <!-- Language Switcher Script -->
        <script>
            function toggleLanguage() {
                const currentLang = localStorage.getItem('site-lang') === 'en' ? 'ar' : 'en';
                applyLanguage(currentLang);
            }

            function applyLanguage(lang) {
                const isAr = lang === 'ar';
                document.documentElement.lang = isAr ? 'ar' : 'en';
                document.documentElement.dir = isAr ? 'rtl' : 'ltr';
                localStorage.setItem('site-lang', lang);
                
                const langBtnText = document.getElementById('lang-btn-text');
                if (langBtnText) langBtnText.textContent = isAr ? 'English' : 'العربية';
                
                document.querySelectorAll('[data-ar]').forEach(el => {
                    const arText = el.getAttribute('data-ar');
                    const enText = el.getAttribute('data-en') || el.dataset.enFallback;
                    if (!el.dataset.enFallback) el.dataset.enFallback = el.innerHTML;
                    if (isAr && arText) {
                        el.innerHTML = arText;
                    } else if (!isAr && enText) {
                        el.innerHTML = enText;
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedLang = localStorage.getItem('site-lang') || 'ar';
                applyLanguage(savedLang);
            });
        </script>
        <!-- Custom SPA Router -->
        <script src="assets/js/spa-router.js" defer></script>

        <!-- PWA Manifest & Meta Tags -->
        <link rel="manifest" href="<?php echo $base_path; ?>manifest.json">
        <meta name="theme-color" content="#009688">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" href="assets/favicon/profile.png">

        <!-- PWA Installation Script -->
        <script>
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                const pwaBtn = document.getElementById('pwa-install-btn');
                if (pwaBtn) pwaBtn.classList.remove('hidden');
            });

            function installPWA() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('[PWA] User accepted install prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            }

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('<?php echo $base_path; ?>sw.js?v=2.5')
                        .then(reg => console.log('[Service Worker] Registered:', reg.scope))
                        .catch(err => console.error('[Service Worker] Failed:', err));
                });
            }
        </script>

        <style>
            html[dir="rtl"] body {
                font-family: 'Cairo', 'Alexandria', sans-serif !important;
            }
            /* Base setup for the logo pill container */
            #logo-pill {
                background-color: transparent;
                border: 1px solid transparent;
                box-shadow: none;
                backdrop-filter: blur(0px);
                -webkit-backdrop-filter: blur(0px);
                transition: all 0.25s ease-in-out !important;
            }

            #logo-pill.scrolled {
                background-color: rgba(255, 255, 255, 0.4) !important;
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
                border: 1px solid rgba(229, 231, 235, 0.4) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            }

            .dark #logo-pill.scrolled {
                background-color: rgba(23, 23, 23, 0.4) !important;
                border: 1px solid rgba(63, 63, 70, 0.4) !important;
                box-shadow: none !important;
            }
        </style>

    </head>

    <body class="bg-white dark:bg-black min-h-screen font-body flex flex-col transition-colors duration-300">

        <!-- Header Component -->
        <header id="main-header"
            class="fixed top-6 left-0 right-0 w-full lg:left-12 lg:right-12 lg:w-auto px-4 sm:px-8 lg:px-16 transition-all duration-300 ease-in-out z-50">

            <div class="max-w-6xl mx-auto flex items-center justify-between h-16">

                <!-- Logo pill -->
                <div id="logo-pill" class="flex-shrink-0 flex items-center rounded-full px-5 py-2">
                    <a href="" data-ar="<?php echo htmlspecialchars($site_name_ar); ?>" data-en="<?php echo htmlspecialchars($site_name_en); ?>"
                        class="font-headline font-bold text-gray-900 dark:text-white text-base sm:text-lg tracking-wide transition-colors">
                        <?php echo htmlspecialchars($site_name_ar); ?>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <nav
                    class="hidden md:flex items-center space-x-1 lg:space-x-2 rtl:space-x-reverse bg-white/40 dark:bg-neutral-900/40 backdrop-blur-md border border-gray-200/40 dark:border-neutral-800/40 shadow-md rounded-full px-6 py-2 transition-colors duration-300">
                    <a href="about" data-ar="عن حمزة" data-en="About"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 <?php echo ($current_page == 'about.php') ? 'text-brandPrimary font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-brandPrimary'; ?>">About</a>

                    <a href="projects" data-ar="المشاريع" data-en="Projects"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 <?php echo ($current_page == 'projects.php') ? 'text-brandPrimary font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-brandPrimary'; ?>">Projects</a>

                    <a href="articles" data-ar="المقالات" data-en="Articles"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 <?php echo ($current_page == 'articles.php') ? 'text-brandPrimary font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-brandPrimary'; ?>">Articles</a>

                    <a href="tools/" data-ar="الأدوات" data-en="Tools"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 <?php echo (strpos($current_page, 'tools') !== false) ? 'text-brandPrimary font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-brandPrimary'; ?>">Tools</a>
                </nav>

                <!-- Right Element: Floating Rounded Pill for Language & Theme Toggler -->
                <div
                    class="flex items-center space-x-2 rtl:space-x-reverse bg-white/40 dark:bg-neutral-900/40 backdrop-blur-md border border-gray-200/40 dark:border-neutral-800/40 shadow-md rounded-full p-1.5 transition-colors duration-300">
                    
                    <!-- PWA Install Button -->
                    <button id="pwa-install-btn" onclick="installPWA()" type="button"
                        class="flex items-center gap-1 text-xs font-bold text-teal-800 dark:text-teal-300 hover:bg-teal-600 hover:text-white px-3 py-1.5 rounded-full transition-all bg-teal-50 dark:bg-teal-900/40 border border-teal-200 dark:border-teal-700/50 shadow-sm" title="تثبيت التطبيق على الجهاز">
                        <i class="fas fa-mobile-alt text-teal-600"></i>
                        <span data-ar="تثبيت" data-en="Install">تثبيت</span>
                    </button>

                    <!-- Language Toggle Button -->
                    <button id="lang-toggle-btn" onclick="toggleLanguage()" type="button"
                        class="flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400 px-3 py-1.5 rounded-full transition-all bg-white/50 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50">
                        <i class="fas fa-globe text-teal-600"></i>
                        <span id="lang-btn-text">العربية</span>
                    </button>

                    <button id="theme-toggle" onclick="toggleTheme()" type="button"
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-200/50 dark:hover:bg-neutral-800/60 focus:outline-none rounded-full text-sm p-2 transition-all"
                        aria-label="Toggle theme">
                        <svg id="theme-toggle-sun-icon" class="hidden h-5 w-5 fill-current" viewBox="0 0 20 20" xmlns="http://w3.org">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 2.293a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zm4 4.707a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM16.121 14.707a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 14a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.707-1.293a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.293-4.707a1 1 0 011.414-1.414l.707.707a1 1 0 01-1.414 1.414l-.707-.707zM10 5a5 5 0 100 10 5 5 0 000-10z"></path>
                        </svg>
                        <svg id="theme-toggle-moon-icon" class="hidden h-5 w-5 fill-current" viewBox="0 0 20 20" xmlns="http://w3.org">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>
                    <!-- Mobile Hamburger Button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-btn"
                            class="text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none rounded-full p-2 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Drawer Layer -->
            <div id="mobile-menu"
                class="hidden md:hidden mt-2 bg-white/95 dark:bg-neutral-900/95 backdrop-blur-md border border-gray-200 dark:border-neutral-800 rounded-2xl w-full shadow-xl overflow-hidden transition-all">
                <div class="px-4 py-4 space-y-1">
                    <a href="about" data-ar="عن حمزة" data-en="About"
                        class="block <?php echo ($current_page == 'about.php') ? 'text-brandPrimary bg-gray-100 dark:bg-white/5 font-semibold' : 'text-gray-600 dark:text-gray-400'; ?> text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">About</a>
                    <a href="projects" data-ar="المشاريع" data-en="Projects"
                        class="block <?php echo ($current_page == 'projects.php') ? 'text-brandPrimary bg-gray-100 dark:bg-white/5 font-semibold' : 'text-gray-600 dark:text-gray-400'; ?> text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">Projects</a>
                    <a href="articles" data-ar="المقالات" data-en="Articles"
                        class="block <?php echo ($current_page == 'articles.php') ? 'text-brandPrimary bg-gray-100 dark:bg-white/5 font-semibold' : 'text-gray-600 dark:text-gray-400'; ?> text-sm font-medium py-2.5 px-4 rounded-xl transition-colors">Articles</a>
                    <a href="tools/" data-ar="الأدوات" data-en="Tools"
                        class="block <?php echo (strpos($current_page, 'tools') !== false) ? 'text-brandPrimary bg-gray-100 dark:bg-white/5 font-semibold' : 'text-gray-600 dark:text-gray-400'; ?> text-sm font-medium py-2.5 px-4 rounded-xl transition-colors">Tools</a>
                </div>
            </div>
        </header>

        <!-- Enforce direct hx-swap attributes right at the container element to maximize speed -->
        <main id="main-content">

            <!-- JavaScript logic optimized for AJAX single-page loads -->
            <script>
                function initializeHeaderLogic() {
                    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                    const mobileMenu = document.getElementById('mobile-menu');
                    const header = document.getElementById('main-header');

                    const themeToggleBtn = document.getElementById('theme-toggle');
                    const themeToggleDarkIcon = document.getElementById('theme-toggle-moon-icon');
                    const themeToggleLightIcon = document.getElementById('theme-toggle-sun-icon');

                    const logoPill = document.getElementById('logo-pill');

                    // Theme Icon Setup Logic
                    updateThemeIcons();

                    // Mobile Menu Toggle
                    if (mobileMenuBtn && mobileMenu) {
                        mobileMenuBtn.onclick = function() {
                            mobileMenu.classList.toggle('hidden');
                        };
                    }
                }

                // Bind layout initializations to both structural lifecycle conditions
                document.addEventListener('DOMContentLoaded', initializeHeaderLogic);
                document.addEventListener('spa:pageLoaded', initializeHeaderLogic);

                // Global Window Scroll Tracking Handlers (Attached only once cleanly)
                if (!window.scrollTrackingInitialized) {
                    let lastScrollY = window.scrollY;
                    window.addEventListener('scroll', () => {
                        const currentScrollY = window.scrollY;
                        const header = document.getElementById('main-header');
                        const mobileMenu = document.getElementById('mobile-menu');
                        const logoPill = document.getElementById('logo-pill');

                        if (header) {
                            if (currentScrollY > lastScrollY && currentScrollY > 60) {
                                header.classList.add('-translate-y-32', 'opacity-0');
                                if (mobileMenu) mobileMenu.classList.add('hidden');
                            } else {
                                header.classList.remove('-translate-y-32', 'opacity-0');
                            }
                        }

                        if (logoPill) {
                            if (currentScrollY > 10) {
                                logoPill.classList.add('scrolled');
                            } else {
                                logoPill.classList.remove('scrolled');
                            }
                        }
                        lastScrollY = currentScrollY;
                    });
                    window.scrollTrackingInitialized = true;
                }
            </script>

            <!-- Start of main wrapper block -->
            <section
                class="w-full bg-white dark:bg-[#0d1527] min-h-screen flex flex-col items-center overflow-x-hidden flex-grow transition-colors duration-300">

                <!-- Inner Container: Pass-through wrapper without nested bounds -->
                <div id="spa-container"
                    class="w-full min-h-screen">
                <?php }
?>