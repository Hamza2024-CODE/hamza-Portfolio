<?php
/**
 * blogger-api.php - Part 1
 * Headless CMS Controller Engine for HemLex Magazine Portal
 * Powered by Google Blogger REST API v3 with automatic file-caching.
 */

// =========================================================================
// 1. SYSTEM CONFIGURATION MATRIX
// =========================================================================

function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments inside env configuration files
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split data cleanly by the initial assignment equals sign
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Strip away string boundaries quotes if defined
            $value = trim($value, '"\'');

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// FIXED: Moves up two directory levels to find your .env file at the root securely
loadEnv(dirname(__DIR__, 2) . '/.env');

// Fetching from .env (with fallbacks to prevent errors if keys are missing)
define('BLOG_ID', $_ENV['BLOG_ID'] ?? '');
define('API_KEY', $_ENV['API_KEY'] ?? '');
define('CACHE_EXPIRY', isset($_ENV['CACHE_EXPIRY']) ? (int) $_ENV['CACHE_EXPIRY'] : 900);
// Resolves to: (Project Root) / src / post_cache /
define('CACHE_DIR', dirname(__DIR__, 2) . '/' . ($_ENV['CACHE_DIR'] ?? 'src/post_cache/'));


// =========================================================================
// 2. CORE DATA PIPELINE FETCH ENGINE
// =========================================================================
/**
 * Fetches JSON payloads from the Blogger API endpoint with local file caching fallback.
 * Saves all generated cache files inside the specified CACHE_DIR directory.
 *
 * @param string $endpoint The specific resource path (e.g., "posts?maxResults=10&")
 * @param string $cache_filename Local storage filename for caching the payload
 * @return array|null Parsed JSON data array or null on failure
 */
function fetch_blogger_data($endpoint, $cache_filename)
{
    // Append the target directory path to the filename
    $full_cache_path = CACHE_DIR . $cache_filename;
    $alt_cache_path  = dirname(__DIR__, 2) . '/src/post_cache/' . $cache_filename;

    // Check if a valid local cache file exists inside either folder
    if (file_exists($full_cache_path)) {
        return json_decode(file_get_contents($full_cache_path), true);
    }
    if (file_exists($alt_cache_path)) {
        return json_decode(file_get_contents($alt_cache_path), true);
    }

    // Direct fallback extraction just in case constants are blank
    $blogId = defined('BLOG_ID') && BLOG_ID !== '' ? BLOG_ID : ($_ENV['BLOG_ID'] ?? $_SERVER['BLOG_ID'] ?? '');
    $apiKey = defined('API_KEY') && API_KEY !== '' ? API_KEY : ($_ENV['API_KEY'] ?? $_SERVER['API_KEY'] ?? '');

    // Clean up any stray quotes left over from the .env parser
    $blogId = trim($blogId, '"\' ');
    $apiKey = trim($apiKey, '"\' ');

    if (empty($blogId) || empty($apiKey)) {
        return null;
    }

    // Failsafe URL compilation matrix
    $baseUrl = "https://www.googleapis.com/blogger/v3/blogs/";
    $url = $baseUrl . $blogId . "/" . $endpoint . "key=" . $apiKey;

    // Set a reasonable stream context timeout AND bypass local SSL certificate faults
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 5
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ]);

    // Fetch fresh payload
    $response = @file_get_contents($url, false, $ctx);

    if ($response) {
        if (!is_dir(CACHE_DIR)) {
            mkdir(CACHE_DIR, 0755, true);
        }
        file_put_contents($full_cache_path, $response);
        return json_decode($response, true);
    }

    return null;
}

// =========================================================================
// 3. ADAPTIVE HEADLESS CMS FORMATTING ROUTER & TOKENS
// =========================================================================

/**
 * Helper function to convert any title string into a clean, URL-safe slug.
 */
function generate_slug_from_title($string)
{
    // Lowercase, remove special characters, replace spaces with dashes
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return $slug;
}
/**
 * Parses raw Blogger payload matrices into uniform arrays structured perfectly
 * for the clean, premium UI grid template rules. Now injects clean custom slugs.
 *
 * @param array $raw_posts The structural "items" block from the API payload
 * @return array Cleanly formatted post objects
 */
function format_blogger_posts($raw_posts)
{
    if (empty($raw_posts) || !is_array($raw_posts)) {
        return [];
    }

    $formatted = [];
    foreach ($raw_posts as $post) {
        // --- A. IMAGE RESOLUTION SYSTEM ---
        $image = 'https://unsplash.com';
        if (preg_match('/<img.+src=["\']([^"\']+)["\']/', $post['content'] ?? '', $matches)) {
            $image = $matches[1];
        }

        // --- B. CLEAN TEXT EXCERPT EXTRACTION SYSTEM ---
        $clean_text = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $post['content'] ?? '');
        $clean_text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $clean_text);
        $clean_text = trim(strip_tags($clean_text));
        $clean_text = preg_replace('/\s+/', ' ', $clean_text);

        if (empty($clean_text)) {
            $excerpt = "Interactive dynamic module asset stream dashboard system.";
        } else {
            $excerpt = (mb_strlen($clean_text) > 130) ? mb_substr($clean_text, 0, 127) . '...' : $clean_text;
        }

        // --- C. DYNAMIC CATEGORY & TAG SEPARATION MATRIX ---
        $raw_labels = isset($post['labels']) ? $post['labels'] : [];

        // First label is ALWAYS the primary category
        $category = (!empty($raw_labels[0])) ? $raw_labels[0] : 'System Log';

        // Everything else becomes a sub-tag (e.g., Bollywood, Hollywood)
        $post_tags = [];
        if (count($raw_labels) > 1) {
            $post_tags = array_slice($raw_labels, 1);
        }

        // Generate clean title slug
        $custom_slug = generate_slug_from_title($post['title'] ?? $post['id']);

        // --- D. COMPACT ARRAY COMPILATION ---
        $formatted[] = [
            'id' => $post['id'],
            'title' => isset($post['title']) ? htmlspecialchars($post['title']) : 'Untitled Entry',
            'slug' => $custom_slug,
            'image' => $image,
            'category_name' => htmlspecialchars($category),
            'category_slug' => urlencode(strtolower($category)),
            'tags' => $post_tags, // Now neatly separated!
            'excerpt' => htmlspecialchars($excerpt),
            'content' => $post['content'] ?? '',
            'created_at' => isset($post['published']) ? date("M j, Y", strtotime($post['published'])) : 'Recent Logs'
        ];
    }

    return $formatted;
}

/**
 * Looks up the master cache file to find the post ID that matches a text slug.
 */
function get_post_id_by_slug($slug)
{
    $cache_file = CACHE_DIR . 'posts_cache.json';
    if (!file_exists($cache_file))
        return null;

    $raw_data = json_decode(file_get_contents($cache_file), true);
    $posts = format_blogger_posts($raw_data['items'] ?? []);

    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            return $post['id'];
        }
    }
    return null;
}

/**
 * Processes post HTML body markup to generate a dynamic Table of Contents
 * and inject matching anchors into heading strings seamlessly.
 *
 * @param string $html_content Raw content string from Blogger API
 * @return array Contains ['toc_html' => '...', 'modified_html' => '...']
 */
function generate_post_toc_and_modify_html($html_content)
{
    if (empty($html_content)) {
        return ['toc_html' => '', 'modified_html' => ''];
    }

    // Suppress warning alerts triggered by HTML5 elements inside legacy DOMDocument parsers
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    // Load text encoding explicitly as UTF-8 to prevent Nepali/Special font breakage
    $dom->loadHTML(mb_convert_encoding($html_content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new DOMXPath($dom);
    // Scan exclusively for H2, H3, and H4 elements within the entry body text
    $headings = $xpath->query('//h2 | //h3 | //h4');

    $toc_links = [];
    $heading_counter = 1;

    foreach ($headings as $heading) {
        $text_val = trim($heading->textContent);
        if (empty($text_val))
            continue;

        // Generate a clean URL anchor hash handle (e.g., "toc-section-2")
        $anchor_id = "toc-section-" . $heading_counter;

        // 1. Log properties into our Table of Contents array map
        $toc_links[] = [
            'id' => $anchor_id,
            'text' => htmlspecialchars($text_val),
            'level' => $heading->tagName // Tracks head level tag names for indent styling
        ];

        // 2. Modify the actual post HTML: Inject the ID attribute into the heading node element
        $heading->setAttribute('id', $anchor_id);

        $heading_counter++;
    }

    // Save our structural changes back down into a clean HTML string format
    $modified_html = $dom->saveHTML();
    libxml_clear_errors();

    // 3. Compile the Table of Contents into a styled HTML layout block
    $toc_html = '';
    if (!empty($toc_links)) {
        $toc_html .= '<div class="ev-article-toc-box" style="background:#ffffff; border:1px solid #eaecef; border-radius:6px; padding:20px; margin-bottom:30px; box-shadow:0 2px 8px rgba(0,0,0,0.01);">';
        $toc_html .= '<h3 style="font-family:\'Oswald\', sans-serif; font-size:1.4rem; font-weight:700; text-transform:uppercase; color:#111115; margin-bottom:12px; border-bottom:2px solid #0088ff; padding-bottom:4px; display:inline-block; letter-spacing:0.3px;">Table of Contents</h3>';
        $toc_html .= '<ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">';

        foreach ($toc_links as $link) {
            // Adjust nested margin layout styles cleanly based on heading depth hierarchy
            if ($link['level'] === 'h3') {
                $indent_style = 'margin-left: 15px; font-size: 1.25rem; color:#55555a;';
            } elseif ($link['level'] === 'h4') {
                $indent_style = 'margin-left: 25px; font-size: 1.2rem; color:#788292; font-style: italic;';
            } else {
                $indent_style = 'font-weight: 600; font-size: 1.3rem; color:#111115;';
            }

            $toc_html .= "<li style='{$indent_style}'>";
            $toc_html .= "<a href='#{$link['id']}' style='transition:color 0.15s ease;' onmouseover='this.style.color=\"#0088ff\"' onmouseout='this.style.color=\"\"'>• {$link['text']}</a>";
            $toc_html .= "</li>";
        }

        $toc_html .= '</ul>';
        $toc_html .= '</div>';
    }

    return [
        'toc_html' => $toc_html,
        'modified_html' => $modified_html
    ];
}

// =========================================================================
// 4. API EXECUTION ROUTER (Only executed when accessed directly via HTTP)
// =========================================================================
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");

    $raw_data = fetch_blogger_data('posts?maxResults=10&', 'posts_cache.json');

    if (!$raw_data) {
        http_response_code(200);
        echo json_encode([]);
        exit;
    }

    $clean_posts = format_blogger_posts($raw_data['items'] ?? []);
    echo json_encode($clean_posts);
    exit;
}