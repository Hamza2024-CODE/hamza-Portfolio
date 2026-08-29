<?php
// ── Page-specific SEO metadata ──────────────────────────────────────────────
$pageTitle = "Production Projects & Software Architecture Portfolio | Hem B. Khatri";
$pageMeta  = [
    'description'  => 'Explore production-grade enterprise systems, scalable REST APIs, and custom software solutions designed and shipped by Senior Engineer Hem B. Khatri using Python, Django, and modern backend tech.',
    'keywords'     => 'Enterprise Web Applications, Python Django Portfolio, REST API Development, Scalable Backend Architecture, Custom Software Solutions Nepal, Senior Developer Case Studies',
    'og_type'      => 'website',
    'og_image_alt' => 'Production Web Applications & Software Architecture Portfolio — Hem B. Khatri',
    'canonical'    => 'https://hemkhatri.com.np/projects/',
    'robots'       => 'index, follow',
];

include_once __DIR__ . '/../../src/includes/header.php';
// DB Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'portfolio_db';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("<p style='color:red;'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Fetch all portfolio entries ordered by start date
$stmt = $pdo->query("SELECT * FROM projects ORDER BY date_started DESC");
$projects = $stmt->fetchAll();

// Helper function to extract YouTube IDs cleanly from various URL formats
function getYouTubeEmbedUrl($url)
{
    $video_id = '';
    // Handle short format youtu.be
    if (strpos($url, 'youtu.be/') !== false) {
        $parts = explode('youtu.be/', $url);
        $end_parts = explode('?', $parts[1]);
        $video_id = $end_parts[0];
    }
    // Handle standard watch?v= format
    elseif (strpos($url, 'v=') !== false) {
        $parts = explode('v=', $url);
        $end_parts = explode('&', $parts[1]);
        $video_id = $end_parts[0];
    }
    // Handle native embed formats if already written
    elseif (strpos($url, 'embed/') !== false) {
        $parts = explode('embed/', $url);
        $end_parts = explode('?', $parts[1]);
        $video_id = $end_parts[0];
    }

    // Clean up trailing spaces or characters safely
    $video_id = trim(substr($video_id, 0, 11));

    return !empty($video_id) ? "https://www.youtube.com/embed/" . $video_id : $url;
}

// Helper function to check if an item string is a YouTube link
function isYouTubeUrl($url)
{
    return (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false);
}
?>



<main class="w-full max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-body">
    <div class="mb-12 border-b border-gray-200 dark:border-slate-800 pb-6">
        <h1 class="text-4xl font-extrabold text-brandNeutral dark:text-white tracking-tight font-headline">Portfolio
            Repository</h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">Production software systems, web applications, and
            scripts.</p>
    </div>

    <?php if (empty($projects)): ?>
        <p class="text-gray-500 dark:text-gray-400 text-center py-10">No records found in database.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($projects as $proj_index => $project): ?>
                <article
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-all duration-200 hover:shadow-lg">

                    <?php
                    $media_input = !empty($project['project_media']) ? explode(',', $project['project_media']) : [];
                    $media_items = array_filter(array_map('trim', $media_input));

                    if (!empty($media_items)):
                        $total_items = count($media_items);
                        ?>
                        <div class="relative w-full aspect-video bg-black overflow-hidden group/carousel"
                            id="carousel-container-<?php echo $proj_index; ?>">

                            <div class="flex h-full w-full transition-transform duration-300 ease-out"
                                id="track-<?php echo $proj_index; ?>" data-current="0" data-total="<?php echo $total_items; ?>">

                                <?php foreach ($media_items as $index => $item): ?>
                                    <div class="w-full h-full flex-shrink-0 relative select-none cursor-zoom-in">
                                        <?php if (isYouTubeUrl($item)): ?>
                                            <iframe class="w-full h-full" src="<?php echo htmlspecialchars(getYouTubeEmbedUrl($item)); ?>"
                                                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                            </iframe>
                                            <div class="absolute inset-0 z-10 transparent-trigger"
                                                onclick="openLightbox('<?php echo $item; ?>', true)"></div>

                                        <?php else: ?>
                                            <img src="<?php echo htmlspecialchars($item); ?>" class="w-full h-full object-cover"
                                                alt="Project media asset" loading="lazy"
                                                onclick="openLightbox('<?php echo $item; ?>', false)">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button onclick="moveCarousel(<?php echo $proj_index; ?>, -1)"
                                class="absolute left-3 top-1/2 -translate-y-1/2 z-20 bg-brandNeutral/60 hover:bg-brandNeutral text-white rounded-full p-2 opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-200 shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button onclick="moveCarousel(<?php echo $proj_index; ?>, 1)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 z-20 bg-brandNeutral/60 hover:bg-brandNeutral text-white rounded-full p-2 opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-200 shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-brandPrimary bg-teal-50 dark:bg-[#009688]/10 rounded-full">
                                    <?php echo htmlspecialchars($project['category']); ?>
                                </span>
                                <?php if ($project['is_featured'] == 1): ?>
                                    <span
                                        class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-950/40 rounded border border-emerald-500/20">Featured</span>
                                <?php endif; ?>
                            </div>

                            <h2 class="text-2xl font-bold text-brandNeutral dark:text-white mb-3 font-headline">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>

                            <?php if (!empty($project['key_contribution'])): ?>
                                <div class="mb-4 bg-gray-50 dark:bg-slate-800/40 p-3 rounded-lg border-l-4 border-brandPrimary">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        <?php echo htmlspecialchars($project['key_contribution']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 items-center min-h-[32px]">
                            <?php
                            // --- ENTER YOUR LANGUAGE ICON MAPPINGS HERE ---
                            // Keep the keys completely lowercase so the check remains case-insensitive
                            $icon_mapping = [
                                'python' => 'https://i.ibb.co/jkcGpf4H/Python-logo-notext-svg.png',
                                'javascript' => 'https://example.com/icons/javascript.png',
                                'html' => 'https://example.com/icons/html.png',
                                'css' => 'https://example.com/icons/css.png',
                                'django' => 'https://example.com/icons/django.png',
                                'spring boot' => 'https://example.com/icons/springboot.png',
                                'java' => 'https://i.ibb.co/MkthJJZV/1720088.webp',
                                // You can add more icons following the same format below
                            ];

                            $tags = explode(',', $project['languages_used']);
                            foreach ($tags as $tag):
                                $trimmed_tag = trim($tag);
                                if ($trimmed_tag === '')
                                    continue;

                                // Convert value to lowercase to perfectly match the array keys
                                $lookup_key = strtolower($trimmed_tag);

                                if (array_key_exists($lookup_key, $icon_mapping)): ?>
                                    <div class="relative group/tooltip flex items-center justify-center cursor-help">
                                        <img src="<?php echo htmlspecialchars($icon_mapping[$lookup_key]); ?>"
                                            alt="<?php echo htmlspecialchars($trimmed_tag); ?> logo"
                                            class="w-7 h-7 object-contain transition-transform duration-200 hover:scale-110">

                                        <span
                                            class="absolute bottom-full mb-2 hidden group-hover/tooltip:block bg-slate-950 dark:bg-slate-800 text-white text-[11px] font-medium px-2 py-1 rounded shadow-xl whitespace-nowrap z-30 border border-slate-800 dark:border-slate-700/50">
                                            <?php echo htmlspecialchars($trimmed_tag); ?>
                                        </span>
                                        <span
                                            class="absolute bottom-full mb-1 hidden group-hover/tooltip:block w-2 h-2 bg-slate-950 dark:bg-slate-800 rotate-45 z-20"></span>
                                    </div>
                                <?php else: ?>
                                    <span
                                        class="bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-gray-200 text-xs px-2.5 py-0.5 rounded font-mono border border-gray-200/40 dark:border-slate-700/40">
                                        <?php echo htmlspecialchars($trimmed_tag); ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-slate-800/20 border-t border-gray-100 dark:border-slate-800 pb-4 flex justify-between items-center gap-4">

                        <!-- GitHub Code Base Link -->
                        <?php if (!empty($project['github_link'])): ?>
                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-brandPrimary dark:hover:text-brandPrimary transition-colors group">
                                <!-- Inline Vector GitHub Icon Badge -->
                                <svg class="w-5 h-5 mr-2 fill-current transition-colors text-gray-600 dark:text-gray-400 group-hover:text-brandPrimary"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.48 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.008.069-.008 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.577.688.479C19.138 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z" />
                                </svg>
                                Code Base
                            </a>
                        <?php endif; ?>

                        <!-- Live Website Demo Link -->
                        <?php if (!empty($project['live_demo_link'])): ?>
                            <a href="<?php echo htmlspecialchars($project['live_demo_link']); ?>" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-sm font-semibold text-brandPrimary hover:underline group">
                                Live Website
                                <!-- Inline Vector Go-To External Link Icon from your Image Reference -->
                                <svg class="w-4 h-4 ml-1.5 stroke-current transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                                    viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </a>
                        <?php endif; ?>

                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<div id="global-lightbox"
    class="hidden fixed inset-0 bg-brandNeutral/95 z-50 flex items-center justify-center p-4 backdrop-blur-sm select-none">
    <button onclick="closeLightbox()"
        class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2.5 transition-all focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="w-full max-w-5xl max-h-[85vh] flex items-center justify-center" id="lightbox-content">
    </div>
</div>
<?php include_once __DIR__ . '/../../src/includes/footer.php'; ?>

<script>
    function moveCarousel(id, direction) {
        const track = document.getElementById(`track-${id}`);
        if (!track) return;

        let current = parseInt(track.getAttribute('data-current')) || 0;
        const total = parseInt(track.getAttribute('data-total')) || 1;

        current = current + direction;
        if (current >= total) {
            current = 0;
        } else if (current < 0) {
            current = total - 1;
        }

        track.setAttribute('data-current', current);
        track.style.transform = `translateX(-${current * 100}%)`;
    }

    function openLightbox(source, isVideo) {
        const lightbox = document.getElementById('global-lightbox');
        const content = document.getElementById('lightbox-content');

        if (isVideo) {
            let videoId = '';
            let url = source.trim();

            if (url.includes('v=')) {
                videoId = url.split('v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            } else if (url.includes('embed/')) {
                videoId = url.split('embed/')[1].split('?')[0];
            } else {
                videoId = url;
            }

            videoId = videoId.trim().substring(0, 11);
            const finalEmbedUrl = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";

            content.innerHTML = '<iframe class="w-full aspect-video max-w-4xl shadow-2xl rounded" src="' + finalEmbedUrl + '" frameborder="0" allow="autoplay; key-system-wide-features; encrypted-media" allowfullscreen></iframe>';
        } else {
            content.innerHTML = `<img src="${source}" class="max-w-full max-h-[85vh] object-contain shadow-2xl rounded-lg" alt="Preview">`;
        }

        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('global-lightbox');
        const content = document.getElementById('lightbox-content');

        lightbox.classList.add('hidden');
        content.innerHTML = '';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>