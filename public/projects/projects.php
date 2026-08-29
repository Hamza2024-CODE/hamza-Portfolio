<?php
// ── Page-specific SEO metadata ──────────────────────────────────────────────
$pageTitle = "معرض المشاريع الهندسية والأنظمة البرمجية — حمزة بوبكر الصديق";
$pageMeta  = [
    'description'  => 'استعرض المشاريع البرمجية المؤسسية، أنظمة الـ ERP، وواجهات الـ REST APIs التي طورها المهندس حمزة بوبكر الصديق بـ Laravel, C#, ASP.NET, Java.',
    'keywords'     => 'مشاريع حمزة بوبكر الصديق, أنظمة ERP, WSAP, Tassyir, SGFEP, Laravel, C#, ASP.NET, Java',
    'og_type'      => 'website',
    'og_image_alt' => 'معرض المشاريع الهندسية والأنظمة البرمجية — حمزة بوبكر الصديق',
    'canonical'    => 'https://github.com/Hamza2024-CODE',
    'robots'       => 'index, follow',
];

include_once __DIR__ . '/../../src/includes/header.php';
require __DIR__ . '/../../config/dbconfig.php';

// Fetch all portfolio entries ordered by ID
$stmt = $pdo->query("SELECT * FROM projects ORDER BY is_featured DESC, id ASC");
$projects = $stmt->fetchAll();

function getYouTubeEmbedUrl($url)
{
    $video_id = '';
    if (strpos($url, 'youtu.be/') !== false) {
        $parts = explode('youtu.be/', $url);
        $end_parts = explode('?', $parts[1]);
        $video_id = $end_parts[0];
    } elseif (strpos($url, 'v=') !== false) {
        $parts = explode('v=', $url);
        $end_parts = explode('&', $parts[1]);
        $video_id = $end_parts[0];
    } elseif (strpos($url, 'embed/') !== false) {
        $parts = explode('embed/', $url);
        $end_parts = explode('?', $parts[1]);
        $video_id = $end_parts[0];
    }
    $video_id = trim(substr($video_id, 0, 11));
    return !empty($video_id) ? "https://www.youtube.com/embed/" . $video_id : $url;
}

function isYouTubeUrl($url)
{
    return (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false);
}

function getTechTagHtml($tag) {
    $tagTrimmed = trim($tag);
    $tagLower = strtolower($tagTrimmed);
    $icon = '<i class="fas fa-code text-teal-600"></i>';
    if (strpos($tagLower, 'php') !== false) {
        $icon = '<i class="fab fa-php text-indigo-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'laravel') !== false) {
        $icon = '<i class="fab fa-laravel text-red-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'c#') !== false || strpos($tagLower, 'asp.net') !== false || strpos($tagLower, '.net') !== false) {
        $icon = '<i class="fas fa-code text-purple-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'java') !== false) {
        $icon = '<i class="fab fa-java text-amber-600 text-sm"></i>';
    } elseif (strpos($tagLower, 'js') !== false || strpos($tagLower, 'javascript') !== false) {
        $icon = '<i class="fab fa-js text-amber-400 text-sm"></i>';
    } elseif (strpos($tagLower, 'sql') !== false || strpos($tagLower, 'mariadb') !== false || strpos($tagLower, 'mysql') !== false) {
        $icon = '<i class="fas fa-database text-sky-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'redis') !== false) {
        $icon = '<i class="fas fa-bolt text-red-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'python') !== false) {
        $icon = '<i class="fab fa-python text-blue-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'linux') !== false || strpos($tagLower, 'nginx') !== false) {
        $icon = '<i class="fab fa-linux text-slate-700 dark:text-slate-300 text-sm"></i>';
    } elseif (strpos($tagLower, 'pwa') !== false || strpos($tagLower, 'mobile') !== false) {
        $icon = '<i class="fas fa-mobile-alt text-teal-500 text-sm"></i>';
    } elseif (strpos($tagLower, 'cloud') !== false) {
        $icon = '<i class="fas fa-cloud text-blue-400 text-sm"></i>';
    }
    return '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm">' . $icon . ' ' . htmlspecialchars($tagTrimmed) . '</span>';
}
?>

<!-- FontAwesome Icons Import -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<main class="w-full max-w-6xl mx-auto py-32 px-4 sm:px-6 lg:px-8 font-body text-left rtl:text-right">
    <div class="mb-12 border-b border-gray-200 dark:border-slate-800 pb-6">
        <h1 class="text-4xl font-extrabold text-brandNeutral dark:text-white tracking-tight font-headline" data-ar="معرض المشاريع والأنظمة البرمجية" data-en="Portfolio Repository">
            معرض المشاريع والأنظمة البرمجية
        </h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400" data-ar="الأنظمة البرمجية المؤسسية، تطبيقات الـ ERP، والحلول الرقمية الجاهزة للإنتاج للمهندس حمزة بوبكر الصديق" data-en="Production software systems, web applications, and enterprise platforms.">
            الأنظمة البرمجية المؤسسية، تطبيقات الـ ERP، والحلول الرقمية الجاهزة للإنتاج للمهندس حمزة بوبكر الصديق
        </p>
    </div>

    <?php if (empty($projects)): ?>
        <p class="text-gray-500 dark:text-gray-400 text-center py-10">لا توجد مشاريع مسجلة حالياً.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($projects as $proj_index => $project): ?>
                <article
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-md overflow-hidden border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-all duration-200 hover:shadow-lg">

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
                                        <?php else: ?>
                                            <img src="<?php echo htmlspecialchars($item); ?>" class="w-full h-full object-cover"
                                                alt="Project media asset" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="p-6 flex-grow flex flex-col justify-between text-left rtl:text-right">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-brandPrimary bg-teal-50 dark:bg-[#009688]/10 rounded-full flex items-center gap-1.5">
                                    <i class="fas fa-folder-open text-teal-600"></i>
                                    <?php echo htmlspecialchars($project['category']); ?>
                                </span>
                                <?php if ($project['is_featured'] == 1): ?>
                                    <span
                                        class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-950/40 rounded border border-emerald-500/20 flex items-center gap-1" data-ar="رئيسي" data-en="Featured">
                                        <i class="fas fa-star text-amber-500 text-[9px]"></i> مشروع رئيسي
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h2 class="text-2xl font-bold text-brandNeutral dark:text-white mb-3 font-headline">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2 items-center">
                                <?php
                                $tags = explode(',', $project['languages_used']);
                                foreach ($tags as $tag):
                                    if (!empty(trim($tag))):
                                        echo getTechTagHtml($tag);
                                    endif;
                                endforeach; 
                                ?>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-2">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1 font-semibold">
                                    <i class="fas fa-check-circle text-teal-600"></i> جاهز للإنتاج المؤسسي
                                </span>
                                <?php if (!empty($project['github_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="text-teal-600 dark:text-teal-400 font-bold hover:underline flex items-center gap-1">
                                        <i class="fab fa-github"></i> مستودع الكود &larr;
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>