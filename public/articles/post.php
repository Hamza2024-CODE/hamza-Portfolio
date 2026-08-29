<?php
// =========================================================================
// 1. ENVIRONMENT CONFIGURATION & ERROR HANDLING
// =========================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =========================================================================
// 2. DEPENDENCY INCLUSIONS (Using absolute paths to prevent rewrite breaks)
// =========================================================================
$base_dir = dirname(__DIR__, 2);
require_once $base_dir . '/src/backend/blogger_post_handler.php';

// =========================================================================
// 3. INBOUND ROUTING & VALIDATION
// =========================================================================
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header("Location: ../articles");
    exit;
}

// =========================================================================
// 4. DATA MATRIX FETCHING & PARSING
// =========================================================================
$raw_payload = fetch_blogger_data('posts?maxResults=50&', 'posts_cache.json');

$formatted_posts = [];
if ($raw_payload && isset($raw_payload['items']) && is_array($raw_payload['items'])) {
    $formatted_posts = format_blogger_posts($raw_payload['items']);
}

// Search matrix for matching slug
$article = null;
if (!empty($formatted_posts)) {
    foreach ($formatted_posts as $post) {
        if (isset($post['slug']) && $post['slug'] === $slug) {
            $article = $post;
            break;
        }
    }
}

// Fallback if resource does not exist
if (!$article) {
    header("Location: ../articles");
    exit;
}

// =========================================================================
// 5. DYNAMIC METADATA & SEO EXECUTION PREPARATION
// =========================================================================
// Calculate dynamic read time
$word_count = str_word_count(strip_tags($article['content'] ?? ''));
$read_time_minutes = max(1, ceil($word_count / 200)); 
$read_time = $read_time_minutes . " min read";

// Normalize layout parameters
$category  = !empty($article['category_name']) ? $article['category_name'] : 'Technology';
$thumbnail = !empty($article['image']) ? $article['image'] : 'https://hemkhatri.com.np/assets/favicon/profile.png'; 
$pageTitle = $article['title'] . " — Hem B. Khatri";
$post_url  = 'https://hemkhatri.com.np/articles/' . $article['slug'];

$post_excerpt = !empty($article['excerpt'])
    ? strip_tags(html_entity_decode($article['excerpt']))
    : 'Read this article by Hem B. Khatri on backend engineering and system architecture.';
$post_date_iso = isset($article['created_at']) ? date('c', strtotime($article['created_at'])) : date('c');

// Generate Schema and Meta Matrix for header injection
$pageMeta = [
    'description'  => mb_strimwidth($post_excerpt, 0, 160, '...'),
    'keywords'     => htmlspecialchars($category) . ' Tutorial, Python Django Development, System Architecture Nepal, ' . htmlspecialchars($article['title'] ?? ''),
    'og_type'      => 'article',
    'og_image'     => $thumbnail,
    'og_image_alt' => htmlspecialchars($article['title'] ?? 'Software Architecture Article by Hem B. Khatri'),
    'canonical'    => $post_url,
    'robots'       => 'index, follow',
    'published'    => $post_date_iso,
    'modified'     => $post_date_iso,
    'jsonld_type'  => 'Article',
    'jsonld_article' => [
        '@context'         => 'https://schema.org',
        '@type'            => 'TechArticle',
        'headline'         => $article['title'] ?? '',
        'description'      => mb_strimwidth($post_excerpt, 0, 160, '...'),
        'image'            => $thumbnail,
        'datePublished'    => $post_date_iso,
        'dateModified'     => $post_date_iso,
        'author'           => [
            '@type' => 'Person',
            'name'  => 'Hem B. Khatri',
            'url'   => 'https://hemkhatri.com.np',
        ],
        'publisher'        => [
            '@type' => 'Person',
            'name'  => 'Hem B. Khatri',
            'url'   => 'https://hemkhatri.com.np',
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => $post_url,
        ],
        'articleSection'   => $category,
        'url'              => $post_url,
    ],
];

// Mount Layout Header
require_once $base_dir . '/src/includes/header.php';
?>

<div class="w-full max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8 font-body bg-white dark:bg-brandNeutral text-gray-800 dark:text-gray-200 transition-colors duration-200">

    <div class="mb-8">
        <a href="articles" class="inline-flex items-center text-sm font-sans font-semibold text-brandPrimary hover:opacity-80 transition-opacity group text-teal-500 hover:text-teal-400">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Articles
        </a>
    </div>

    <div class="mb-8 border-b border-gray-100 dark:border-gray-800 pb-8">
        <div class="mb-4 flex flex-wrap items-center gap-2 text-xs font-sans text-gray-500 dark:text-gray-400 font-medium">
            <time class="flex items-center pl-3.5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center" aria-hidden="true">
                    <span class="h-3 w-0.5 rounded-full bg-brandPrimary bg-teal-500"></span>
                </span>
                <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
            </time>
            <span class="text-gray-300 dark:text-gray-700">•</span>
            <span><?php echo $read_time; ?></span>
            <span class="text-gray-300 dark:text-gray-700">•</span>
            
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider text-brandPrimary bg-brandPrimary/10 border border-brandPrimary/20 text-[#009688] bg-[#009688]/10 border-[#009688]/20 backdrop-blur-sm shadow-sm shadow-brandPrimary/5">
                <?php echo htmlspecialchars($category); ?>
            </span>
        </div>

        <h1 class="font-headline text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 dark:text-white leading-tight">
            <?php echo htmlspecialchars($article['title']); ?>
        </h1>
    </div>

    <div class="relative w-full aspect-video rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-slate-900 mb-10 shadow-xl">
        <img src="<?php echo $thumbnail; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="w-full h-full object-cover">
    </div>

    <article class="font-body text-gray-700 dark:text-gray-300 text-base leading-relaxed space-y-6 
                    prose dark:prose-invert max-w-none
                    prose-headings:font-headline prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white prose-headings:tracking-tight
                    prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
                    prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                    prose-p:text-gray-600 dark:prose-p:text-gray-400 prose-p:leading-relaxed
                    prose-ul:list-disc prose-ul:pl-6 prose-ul:space-y-2 prose-ul:text-gray-600 dark:prose-ul:text-gray-400
                    prose-strong:text-gray-900 dark:prose-strong:text-white prose-strong:font-semibold">
        <?php echo $article['content']; ?>
    </article>

</div>

<?php 
// Mount Layout Footer
require_once $base_dir . '/src/includes/footer.php'; 
?>