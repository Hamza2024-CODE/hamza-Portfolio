<?php
// ── Page-specific SEO metadata ──────────────────────────────────────────────
$pageTitle = "حمزة بوبكر الصديق — مهندس برمجيات ومطور متعدد المنصات";
$pageMeta  = [
    'description'  => 'الملف الشخصي والمهني للمهندس حمزة بوبكر الصديق — مهندس برمجيات ومطور متعدد المنصات بوزارة التكوين والتعليم المهنيين، متخصص في بناء الأنظمة المؤسسية وتطبيقات Laravel و C# و ASP.NET.',
    'keywords'     => 'حمزة بوبكر الصديق, مهندس برمجيات الجزائر, مطور متعدد المنصات, وزارة التكوين المهني, Laravel, C# ASP.NET, WSAP, Tassyir ERP',
    'og_type'      => 'website',
    'og_image_alt' => 'حمزة بوبكر الصديق — مهندس برمجيات الجزائر',
    'canonical'    => 'https://github.com/Hamza2024-CODE',
    'jsonld_type'  => 'Person',
    'jsonld_article' => null,
];
require_once __DIR__ . '/../src/includes/header.php';
require_once __DIR__ . '/../config/dbconfig.php';
require_once __DIR__ . '/../src/backend/blogger_post_handler.php';

$endpoint = "posts?maxResults=10&";
$cache_filename = "posts_cache.json";
$raw_payload = fetch_blogger_data($endpoint, $cache_filename);

$latest_posts = [];
if ($raw_payload && isset($raw_payload['items']) && is_array($raw_payload['items'])) {
    $latest_posts = format_blogger_posts($raw_payload['items']);
}
?>

<!-- FontAwesome Icons for Icons rendering -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.heatmap-container {
  display: flex;
  justify-content: flex-end;
  width: 100%;
  overflow: hidden;
}
.heatmap-grid {
  display: grid;
  grid-auto-flow: column;
  grid-template-rows: repeat(7, 1fr);
  gap: 4px;
  width: 100%;
  min-width: 850px;
}
.heatmap-cell {
  width: 100%;
  aspect-ratio: 1 / 1;
  border-radius: 3px;
  transition: background-color 0.3s ease;
}
.heatmap-legend-cell {
  width: 11px;
  height: 11px;
  border-radius: 2px;
}

@keyframes custom-shine {
  0% { transform: translateX(-100%); }
  35%, 100% { transform: translateX(100%); }
}
.animate-auto-shine {
  animation: custom-shine 3s infinite ease-in-out;
}

.portfolio-bg-layer {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 0;
  background-image: url('public/assets/images/hamza_hero.jpg');
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
  transition: opacity 0.25s ease;
}
.portfolio-bg-layer::before {
  content: '';
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
  background: linear-gradient(115deg, rgba(255, 255, 255, 1) 25%, rgba(255, 255, 255, 0.7) 45%, rgba(255, 255, 255, 0.2) 65%, rgba(255, 255, 255, 0) 90%);
  transition: opacity 300ms ease, background 300ms ease;
  opacity: 1;
}
html.dark .portfolio-bg-layer::before {
  background: linear-gradient(115deg, #0d1527 25%, rgba(13, 21, 39, 0.7) 45%, rgba(13, 21, 39, 0.2) 65%, rgba(13, 21, 39, 0) 90%);
}
@media (min-width: 768px) {
  .portfolio-bg-layer {
    opacity: 1.00;
    background-size: cover;
    background-position: right center;
  }
  .portfolio-bg-layer::before {
    background: linear-gradient(90deg, #ffffff 0%, #ffffff 35%, rgba(255, 255, 255, 0.6) 55%, rgba(255, 255, 255, 0) 85%);
  }
  html.dark .portfolio-bg-layer::before {
    background: linear-gradient(90deg, #0d1527 0%, #0d1527 35%, rgba(13, 21, 39, 0.6) 55%, rgba(13, 21, 39, 0) 85%);
  }
}
</style>

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300">
<div class="w-full box-border grid grid-cols-[minmax(1.5rem,1fr)_minmax(0,1320px)_minmax(1.5rem,1fr)] sm:grid-cols-[minmax(3rem,1fr)_minmax(0,1320px)_minmax(3rem,1fr)] md:grid-cols-[minmax(4rem,1fr)_minmax(0,1320px)_minmax(4rem,1fr)] lg:grid-cols-[minmax(5rem,1fr)_minmax(0,1320px)_minmax(5rem,1fr)]">

<!-- Hero Section -->
<section
class="col-span-full relative w-full h-auto md:min-h-screen flex items-center bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border pt-24 sm:pt-32 pb-12 sm:pb-16 md:py-0 transition-colors duration-300">
<div class="portfolio-bg-layer absolute inset-0 pointer-events-none z-0"></div>
<div
class="relative z-20 w-full px-4 sm:px-12 md:px-16 lg:px-20 max-w-[1320px] mx-auto flex items-center box-border">
<div class="w-full max-w-[680px] text-left rtl:text-right flex flex-col items-start box-border m-0">
<div
class="font-sans bg-teal-50 dark:bg-teal-900/30 border border-teal-200 dark:border-teal-700/50 text-teal-800 dark:text-teal-300 px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-[30px] text-xs sm:text-[0.95rem] font-semibold mb-4 sm:mb-6 tracking-wide transition-all duration-300"
data-ar="👋 مرحباً، أنا" data-en="👋 Hello, I'm">
👋 مرحباً، أنا
</div>
<h1
class="font-sans text-3xl sm:text-5xl md:text-[3.8rem] font-bold leading-[1.2] sm:leading-[1.15] m-0 mb-4 sm:mb-6 tracking-tight w-full box-border">
<span data-ar="<?php echo htmlspecialchars($site_name_ar); ?>" data-en="<?php echo htmlspecialchars($site_name_en); ?>"><?php echo htmlspecialchars($site_name_ar); ?></span><br>
<span class="text-teal-600 dark:text-teal-400 text-2xl sm:text-5xl md:text-[3.8rem] block mt-1" data-ar="<?php echo htmlspecialchars($site_title_ar); ?>" data-en="<?php echo htmlspecialchars($site_title_en); ?>"><?php echo htmlspecialchars($site_title_ar); ?></span><br>
<span
class="text-base sm:text-2xl md:text-[2.2rem] font-medium text-slate-500 dark:text-slate-300 transition-colors duration-300 block mt-1" data-ar="<?php echo htmlspecialchars($site_institution_ar); ?>" data-en="<?php echo htmlspecialchars($site_institution_en); ?>"><?php echo htmlspecialchars($site_institution_ar); ?></span>
</h1>
<p
class="font-body text-sm sm:text-[1.15rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0 mb-6 sm:mb-8 max-w-[620px] transition-colors duration-300" data-ar="متخصص في بناء الأنظمة الرقمية المؤسسية، تطبيقات الـ ERP، والحلول البرمجية عالية الأداء بخبرة 7+ سنوات في التطوير البرمجي." data-en="Specialized in building end-to-end digital systems, enterprise applications, ERPs, and scalable multi-platform platforms with 7+ years of professional engineering experience.">
متخصص في بناء الأنظمة الرقمية المؤسسية، تطبيقات الـ ERP، والحلول البرمجية عالية الأداء بخبرة 7+ سنوات في التطوير البرمجي.
</p>
<div class="flex gap-4 mb-8 sm:mb-12 font-sans w-full max-w-[480px] sm:max-w-none">
<a href="#projects"
class="flex-1 md:flex-initial text-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-base md:text-[1.05rem] rounded-xl no-underline transition-all duration-200 shadow-lg shadow-teal-600/20" data-ar="مشاهدة أعمالي ⟵" data-en="See my Work &rarr;">
مشاهدة أعمالي ⟵
</a>
<a href="mailto:boubakarseddikh@gmail.com"
class="flex-1 md:flex-initial text-center px-6 py-3 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/40 font-bold text-base md:text-[1.05rem] rounded-xl no-underline transition-all duration-200 dark:hover:bg-slate-800/70" data-ar="تواصل معي" data-en="Contact Me">
تواصل معي
</a>
</div>
<div class="flex items-center gap-4 font-sans">
<span
class="hidden sm:inline text-slate-400 dark:text-slate-500 text-[0.95rem] font-medium transition-colors duration-300" data-ar="تابعني على:" data-en="Let's connect">تابعني على:</span>
<div class="flex gap-2.5">
<a href="https://github.com/Hamza2024-CODE" target="_blank" rel="noopener noreferrer"
class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200" title="GitHub">
<i class="fab fa-github"></i>
</a>
<a href="https://www.linkedin.com/in/hamza-boubakare-seddike" target="_blank" rel="noopener noreferrer"
class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200" title="LinkedIn">
<i class="fab fa-linkedin-in"></i>
</a>
<a href="mailto:boubakarseddikh@gmail.com"
class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200" title="Email">
<i class="fas fa-envelope"></i>
</a>
<a href="tel:+213779771993"
class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200" title="Phone">
<i class="fas fa-phone"></i>
</a>
</div>
</div>
</div>
</div>

<!-- Interactive AI Agent Floating Widget Button -->
<a id="ask-hem-trigger" href="javascript:void(0);" onclick="toggleChatWindow()"
class="absolute -bottom-12 right-4 sm:bottom-8 sm:right-12 md:right-24 lg:right-32 z-30 block no-underline group scale-85 sm:scale-100 origin-bottom-right">
<div
class="relative w-[280px] sm:w-[330px] rounded-2xl p-[3px] overflow-hidden transition-transform duration-300 group-hover:scale-[1.02]">
<div
class="absolute inset-0 -m-[50%] bg-[conic-gradient(from_0deg,transparent_40%,rgba(0,186,155,0.8)_50%,transparent_60%)] animate-spin [animation-duration:4s]">
</div>
<div
class="relative z-10 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-[13px] p-3.5 backdrop-blur-xl font-sans shadow-2xl overflow-hidden transition-colors duration-300">
<div
class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-slate-900/5 dark:via-white/15 to-transparent animate-auto-shine">
</div>
<div class="relative z-10 flex justify-between items-center gap-3">
<div class="text-left rtl:text-right">
<span
class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400 mb-0.5 tracking-wide transition-colors duration-300" data-ar="المساعد الذكي لـ حمزة" data-en="Ask Hamza AI Assistant">
<i class="fas fa-comment-dots text-teal-600 text-xs"></i> المساعد الذكي لـ حمزة
</span>
<p
class="font-body text-[0.75rem] text-slate-600 dark:text-slate-300 m-0 leading-normal transition-colors duration-300" data-ar="اسأل المساعد الذكي عن مشاريعي وأنظمتي" data-en="Ask anything about my systems or architecture.">
اسأل المساعد الذكي عن مشاريعي وأنظمتي
</p>
</div>
<div
class="w-9 h-9 bg-teal-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-md shadow-teal-600/20">
<i class="fas fa-robot text-white text-xs sm:text-sm"></i>
</div>
</div>
</div>
</div>
</a>
</section>

<!-- Key Metrics Section -->
<div class="col-span-full w-full mt-20 lg:mt-0 px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24 relative z-20">
<div
class="w-full grid grid-cols-2 lg:grid-cols-4 bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200/80 dark:border-white/5 rounded-2xl p-5 backdrop-blur-xl shadow-xl dark:shadow-2xl box-border transition-all duration-300">
<div
class="flex flex-col items-center text-center p-4 justify-center box-border border-r border-b lg:border-b-0 border-slate-200 dark:border-white/5 transition-colors duration-300">
<div class="text-3xl sm:text-4xl md:text-5xl font-sans font-bold text-teal-600 dark:text-teal-400 mb-1 tracking-tight">7+</div>
<p
class="font-body text-[0.85rem] sm:text-[0.95rem] text-slate-600 dark:text-slate-400 leading-snug sm:leading-relaxed m-0 max-w-[160px] sm:max-w-[200px]" data-ar="سنوات خبرة في التطوير البرمجي" data-en="Years Software Development">
سنوات خبرة في التطوير البرمجي
</p>
</div>
<div
class="flex flex-col items-center text-center p-4 justify-center box-border border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/5 transition-colors duration-300">
<div class="text-3xl sm:text-4xl md:text-5xl font-sans font-bold text-teal-600 dark:text-teal-400 mb-1 tracking-tight">13+</div>
<p
class="font-body text-[0.85rem] sm:text-[0.95rem] text-slate-600 dark:text-slate-400 leading-snug sm:leading-relaxed m-0 max-w-[160px] sm:max-w-[200px]" data-ar="منصات برمجية مؤسسية كبرى" data-en="Major Enterprise Platforms">
منصات برمجية مؤسسية كبرى
</p>
</div>
<div
class="flex flex-col items-center text-center p-4 justify-center box-border border-r border-slate-200 dark:border-white/5 transition-colors duration-300">
<div class="text-3xl sm:text-4xl md:text-5xl font-sans font-bold text-teal-600 dark:text-teal-400 mb-1 tracking-tight">1st</div>
<p
class="font-body text-[0.85rem] sm:text-[0.95rem] text-slate-600 dark:text-slate-400 leading-snug sm:leading-relaxed m-0 max-w-[160px] sm:max-w-[200px]" data-ar="الفائز الأول بالهاكاثون (الجزائر 2026)" data-en="Hackathon Winner (Algeria 2026)">
الفائز الأول بالهاكاثون (الجزائر 2026)
</p>
</div>
<div class="flex flex-col items-center text-center p-4 justify-center box-border">
<div class="text-3xl sm:text-4xl md:text-5xl font-sans font-bold text-teal-600 dark:text-teal-400 mb-1 tracking-tight">100%</div>
<p
class="font-body text-[0.85rem] sm:text-[0.95rem] text-slate-600 dark:text-slate-400 leading-snug sm:leading-relaxed m-0 max-w-[160px] sm:max-w-[200px]" data-ar="هندسة جاهزة للإنتاج المؤسسي" data-en="Production Ready Engineering">
هندسة جاهزة للإنتاج المؤسسي
</p>
</div>
</div>
</div>

<!-- Image Viewer Section (Life Beyond Code) -->
<div class="col-span-full w-full overflow-hidden mb-16 md:mb-24">
<div class="w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-4">
<div class="flex flex-row items-center gap-4">
<div class="flex flex-col text-left rtl:text-right">
<h2
class="font-sans text-xl md:text-2xl font-bold text-slate-900 dark:text-white m-0 flex items-center gap-2 tracking-tight" data-ar="الحياة خارج الكود 🏔️" data-en="Life Beyond Code 🏔️">
الحياة خارج الكود <span class="text-base md:text-lg">🏔️</span>
</h2>
<p
class="font-body text-sm text-slate-600 dark:text-slate-400 m-0 mt-0.5 leading-none" data-ar="الأماكن والبيئات الطبيعية التي تلهم أعمالي الهندسيّة." data-en="The places and environments that inspire my engineering.">
الأماكن والبيئات الطبيعية التي تلهم أعمالي الهندسيّة.
</p>
</div>
</div>
</div>
<!-- Scrollable Gallery Cards -->
<div id="gallery-container"
class="flex flex-row items-center justify-start md:justify-center gap-6 overflow-x-auto overflow-y-visible py-6 w-full px-6 sm:px-12 md:px-16 lg:px-20 no-scrollbar">
    <div class="group relative w-64 h-80 flex-shrink-0 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-lg border border-slate-200/60 dark:border-slate-800">
            <img src="public/assets/images/badimalika.jpg" alt="Algiers" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <span class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1 rounded-full border border-white/20">الجزائر العاصمة</span>
        </div>
    </div>

    <div class="group relative w-64 h-80 flex-shrink-0 transform rotate-3 hover:rotate-0 transition-transform duration-300">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-lg border border-slate-200/60 dark:border-slate-800">
            <img src="public/assets/images/Mountains-Nepal-II.jpg" alt="Saïda" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <span class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1 rounded-full border border-white/20">سعيدة، الجزائر</span>
        </div>
    </div>

    <div class="group relative w-64 h-80 flex-shrink-0 transform -rotate-1 hover:rotate-0 transition-transform duration-300">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-lg border border-slate-200/60 dark:border-slate-800">
            <img src="public/assets/images/marigold.jpg" alt="Djurdjura" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <span class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1 rounded-full border border-white/20">جبال جرجرة</span>
        </div>
    </div>

    <div class="group relative w-64 h-80 flex-shrink-0 transform rotate-2 hover:rotate-0 transition-transform duration-300">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-lg border border-slate-200/60 dark:border-slate-800">
            <img src="public/assets/images/mani_baudha.jpg" alt="Casbah" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <span class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1 rounded-full border border-white/20">القصبة العتيقة</span>
        </div>
    </div>

    <div class="group relative w-64 h-80 flex-shrink-0 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-lg border border-slate-200/60 dark:border-slate-800">
            <img src="public/assets/images/nepal boudhanath stupa.jpg" alt="Sahara" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <span class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[11px] font-medium px-3 py-1 rounded-full border border-white/20">طاسيلي ناجر بالصحراء</span>
        </div>
    </div>
</div>
</div>

<!-- Featured Projects Section -->
<div id="projects" class="col-span-full w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24">
<div class="w-full flex flex-row items-end justify-between mb-8">
<div class="flex flex-col text-left rtl:text-right">
<h2
class="font-sans text-2xl md:text-3xl font-bold text-slate-900 dark:text-white m-0 flex items-center gap-2 tracking-tight" data-ar="المشاريع المؤسسية الرائدة" data-en="Featured Enterprise Projects">
المشاريع المؤسسية الرائدة
<span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
</h2>
<p
class="font-body text-sm md:text-base text-slate-600 dark:text-slate-400 m-0 mt-1.5 leading-none" data-ar="منصات برمجية عالية الأثر مبنية للإنتاج المؤسسي والحكومي" data-en="High-impact digital platforms engineered for institutional production">
منصات برمجية عالية الأثر مبنية للإنتاج المؤسسي والحكومي
</p>
</div>
<a href="https://github.com/Hamza2024-CODE" target="_blank"
class="font-sans text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 transition-colors duration-200 group z-20 relative" data-ar="عرض الكل على GitHub ⟵" data-en="View all on GitHub &rarr;">
عرض الكل على GitHub ⟵
</a>
</div>

<?php
try {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY is_featured DESC, id ASC");
    $all_projects = $stmt->fetchAll();
} catch (PDOException $e) {
    $all_projects = [];
}
?>

<div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 box-border">
<?php if (!empty($all_projects)): ?>
    <?php foreach ($all_projects as $idx => $project): 
        $languages = array_map('trim', explode(',', $project['languages_used'] ?? 'Laravel, PHP, MySQL'));
    ?>
    <div data-index="<?php echo $idx; ?>"
    class="project-card group/card w-full flex flex-col bg-slate-50/80 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] border border-slate-200 dark:border-white/5 hover:border-slate-300 dark:hover:border-white/10 rounded-2xl p-5 backdrop-blur-xl shadow-xl dark:shadow-2xl box-border text-left rtl:text-right relative transition-all duration-300 hover:-translate-y-1">
    
    <div class="w-full aspect-[16/10] rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-900 border border-slate-200/60 dark:border-white/5 mb-5 relative transition-colors duration-300">
    <img src="<?php echo !empty($project['project_media']) ? htmlspecialchars($project['project_media']) : 'public/assets/images/badimalika.jpg'; ?>" alt="<?php echo htmlspecialchars($project['title']); ?>"
    class="w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-[1.02]">
    <div
    class="absolute top-3 right-3 bg-teal-600/90 text-white px-3 py-1 rounded-full text-xs font-semibold tracking-wide backdrop-blur-md">
    <?php echo htmlspecialchars($project['category']); ?>
    </div>
    </div>
    <h3 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-2 tracking-tight transition-colors duration-200 group-hover/card:text-teal-600">
    <?php echo htmlspecialchars($project['title']); ?></h3>
    <p class="font-body text-[0.9rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0 mb-5 flex-grow transition-colors duration-300 line-clamp-3">
    <?php echo htmlspecialchars($project['description']); ?></p>
    <div class="flex flex-wrap gap-1.5 mb-5 font-sans z-20 relative">
        <?php foreach ($languages as $lang): ?>
            <span class="px-2.5 py-0.5 bg-slate-200/60 dark:bg-white/5 border border-slate-300/50 dark:border-white/10 rounded-md text-[0.75rem] text-slate-700 dark:text-slate-300 font-medium"><?php echo htmlspecialchars($lang); ?></span>
        <?php endforeach; ?>
    </div>
    <div class="w-full flex items-center justify-between font-sans pt-3 border-t border-slate-200 dark:border-white/5 z-20 relative">
        <span class="text-xs font-semibold text-teal-600 dark:text-teal-400" data-ar="جاهز للإنتاج" data-en="Production Ready">جاهز للإنتاج</span>
        <?php if (!empty($project['github_link'])): ?>
            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400 hover:underline" data-ar="مستودع الكود ⟵" data-en="Repository &nearr;">مستودع الكود ⟵</a>
        <?php endif; ?>
    </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
</div>

<!-- Services & Technical Domains Section -->
<div class="col-span-full w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24">
<div class="flex flex-col text-left rtl:text-right mb-8">
<h2 class="font-sans text-2xl md:text-3xl font-bold text-slate-900 dark:text-white m-0 tracking-tight" data-ar="الخدمات والحلول البرمجية" data-en="What I Can Help You With">
الخدمات والحلول البرمجية
</h2>
<p class="font-body text-sm md:text-base text-slate-600 dark:text-slate-400 m-0 mt-1.5 leading-none" data-ar="حلول برمجية شاملة وتخصصات هندسية متقدمة" data-en="Full-stack solutions & technical specialization">
حلول برمجية شاملة وتخصصات هندسية متقدمة
</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full box-border">
<div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
<div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-slate-200/50 dark:bg-white/[0.03] border border-slate-300/60 dark:border-white/5 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl sm:text-3xl mb-6">
<i class="fas fa-sitemap"></i>
</div>
<h3 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-3 tracking-tight leading-snug" data-ar="الهندسة المعمارية<br>وأنظمة الـ ERP" data-en="Software Architecture<br>& ERP Systems">الهندسة المعمارية<br>وأنظمة الـ ERP</h3>
<p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0" data-ar="تصميم الأنظمة المؤسسية النمطية، تطبيقات إدارة الموارد، وبناء قواعد البيانات الحكومية." data-en="Modular enterprise system design, ERP workflows, and institutional data platforms.">تصميم الأنظمة المؤسسية النمطية، تطبيقات إدارة الموارد، وبناء قواعد البيانات الحكومية.</p>
</div>

<div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
<div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-slate-200/50 dark:bg-white/[0.03] border border-slate-300/60 dark:border-white/5 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl sm:text-3xl mb-6">
<i class="fas fa-code-branch"></i>
</div>
<h3 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-3 tracking-tight leading-snug" data-ar="تطوير الـ Full-Stack<br>والـ REST APIs" data-en="Full-Stack & API<br>Engineering">تطوير الـ Full-Stack<br>والـ REST APIs</h3>
<p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0" data-ar="أنظمة خلفية عالية الأداء بـ Laravel, PHP, C#, ASP.NET, Java وواجهات برمجة التطبيقات." data-en="High-performance Laravel, PHP, C#, ASP.NET, Java backend systems and REST APIs.">أنظمة خلفية عالية الأداء بـ Laravel, PHP, C#, ASP.NET, Java وواجهات برمجة التطبيقات.</p>
</div>

<div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
<div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-slate-200/50 dark:bg-white/[0.03] border border-slate-300/60 dark:border-white/5 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl sm:text-3xl mb-6">
<i class="fas fa-mobile-alt"></i>
</div>
<h3 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-3 tracking-tight leading-snug" data-ar="تطبيقات الموبايل<br>والـ PWA التقدمية" data-en="Multi-Platform &<br>PWA Development">تطبيقات الموبايل<br>والـ PWA التقدمية</h3>
<p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0" data-ar="تطبيقات الموبايل متوافقة مع جميع المنصات، وتطبيقات الويب التقدمية الشغالة بدون إنترنت." data-en="Cross-platform mobile applications, Progressive Web Apps (PWA), and responsive interfaces.">تطبيقات الموبايل متوافقة مع جميع المنصات، وتطبيقات الويب التقدمية الشغالة بدون إنترنت.</p>
</div>

<div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
<div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-slate-200/50 dark:bg-white/[0.03] border border-slate-300/60 dark:border-white/5 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl sm:text-3xl mb-6">
<i class="fas fa-shield-alt"></i>
</div>
<h3 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-3 tracking-tight leading-snug" data-ar="الأمن السيبراني<br>والـ DevOps السحابي" data-en="DevOps & Cloud<br>Cybersecurity">الأمن السيبراني<br>والـ DevOps السحابي</h3>
<p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0" data-ar="إدارة سيرفرات Linux و Nginx، صلاحيات الوصول RBAC، وخدمات Google Cloud و Huawei." data-en="Linux server administration, Nginx, RBAC security, Google Cloud, Huawei, and Redis caching.">إدارة سيرفرات Linux و Nginx، صلاحيات الوصول RBAC، وخدمات Google Cloud و Huawei.</p>
</div>
</div>
</div>

<!-- Creator Tools / Resource Center Section -->
<div id="tools" class="col-span-full w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24">
<div class="w-full flex flex-col text-left rtl:text-right mb-8">
<h2 class="font-sans text-2xl md:text-3xl font-bold text-slate-900 dark:text-white m-0 tracking-tight flex items-center gap-2" data-ar="مركز الموارد وأدوات المطورين 🛠️" data-en="Resource Center & Creator Tools 🛠️">
مركز الموارد وأدوات المطورين <span class="text-base md:text-lg">🛠️</span>
</h2>
<p class="font-body text-sm md:text-base text-slate-600 dark:text-slate-400 m-0 mt-1.5 leading-none" data-ar="أدوات هندسية عالية الأداء مدعومة بـ Gemini API و PHP للمطورين" data-en="Handy, performant utilities powered by Gemini API & PHP for software engineers">
أدوات هندسية عالية الأداء مدعومة بـ Gemini API و PHP للمطورين
</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full box-border">

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
            <i class="fas fa-code"></i>
        </div>
        <h3 class="font-sans text-base font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="محول الأكواد وترميز UTF-8 بـ PHP" data-en="PHP & UTF-8 Code Converter">محول الأكواد وترميز UTF-8 بـ PHP</h3>
        <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4 flex-grow" data-ar="تحويل النصوص، مصفوفات UTF-8، الرموز البرمجية، وتهيئة البيانات بترميز JSON و Base64." data-en="Convert raw text, UTF-8 character maps, HTML entities, and JSON data matrices.">تحويل النصوص، مصفوفات UTF-8، الرموز البرمجية، وتهيئة البيانات بترميز JSON و Base64.</p>
        <a href="tools/php_converter.php" class="font-sans text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 mt-auto" data-ar="فتح المحول ⟵" data-en="Open Converter &rarr;">
            فتح المحول ⟵
        </a>
    </div>

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
            <i class="fas fa-feather-alt"></i>
        </div>
        <h3 class="font-sans text-base font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="منسق أكواد PHP و Laravel" data-en="PHP & Laravel Code Formatter">منسق أكواد PHP و Laravel</h3>
        <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4 flex-grow" data-ar="أداة تنظيف وتنسيق الأكواد البرمجية وفق المعايير القياسية العالمية PSR-12." data-en="Professional PHP code formatter, syntax highlighter, and PSR-12 standard beautifier.">أداة تنظيف وتنسيق الأكواد البرمجية وفق المعايير القياسية العالمية PSR-12.</p>
        <a href="tools/php_writer.php" class="font-sans text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 mt-auto" data-ar="فتح المنسق ⟵" data-en="Open Formatter &rarr;">
            فتح المنسق ⟵
        </a>
    </div>

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h3 class="font-sans text-base font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="خارطة طريق هندسة البرمجيات (2026)" data-en="PHP & Architecture Syllabus (2026)">خارطة طريق هندسة البرمجيات (2026)</h3>
        <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4 flex-grow" data-ar="المنهج الكامل لبناء الأنظمة المؤسسية الكبرى، أنظمة الـ ERP، والـ Microservices." data-en="Complete architectural syllabus and roadmap for building production enterprise systems in PHP.">المنهج الكامل لبناء الأنظمة المؤسسية الكبرى، أنظمة الـ ERP، والـ Microservices.</p>
        <a href="tools/php_syllabus.php" class="font-sans text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 mt-auto" data-ar="فتح المنهاج ⟵" data-en="Open Roadmap &rarr;">
            فتح المنهاج ⟵
        </a>
    </div>

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
            <i class="fas fa-check-double"></i>
        </div>
        <h3 class="font-sans text-base font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="المحاكي الرقمي لنظام OMR والتقييم" data-en="Virtual OMR & Assessment Evaluator">المحاكي الرقمي لنظام OMR والتقييم</h3>
        <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4 flex-grow" data-ar="نظام التقييم وتصحيح أوراق الإجابة الرقمية المصمم لمنصة المسابقات والتوجيه." data-en="Interactive candidate examination evaluator & digital OMR answer sheet practice engine.">نظام التقييم وتصحيح أوراق الإجابة الرقمية المصمم لمنصة المسابقات والتوجيه.</p>
        <a href="tools/omr_evaluator.php" class="font-sans text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 mt-auto" data-ar="فتح المحاكي ⟵" data-en="Open Evaluator &rarr;">
            فتح المحاكي ⟵
        </a>
    </div>

</div>
</div>

<!-- Latest Writing Section -->
<div id="articles" class="col-span-full w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24">
<div class="w-full flex flex-row items-end justify-between mb-8">
<div class="flex flex-col text-left rtl:text-right">
<h2 class="font-sans text-2xl md:text-3xl font-bold text-slate-900 dark:text-white m-0 tracking-tight" data-ar="أحدث المقالات والأبحاث" data-en="Latest Writing">أحدث المقالات والأبحاث</h2>
<p class="font-body text-sm md:text-base text-slate-600 dark:text-slate-400 m-0 mt-1.5 leading-none" data-ar="مقالات هندسية، دراسات المعمارية البرمجية، ومذكرات تطوير الأنظمة" data-en="Engineering thoughts, architecture tutorials, and system design notes">مقالات هندسية، دراسات المعمارية البرمجية، ومذكرات تطوير الأنظمة</p>
</div>
<a href="articles" class="font-sans text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 transition-colors duration-200 group" data-ar="عرض جميع المقالات ⟵" data-en="View all publications &rarr;">
عرض جميع المقالات ⟵
</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full box-border">
    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-5 backdrop-blur-xl shadow-xl dark:shadow-2xl box-border text-left rtl:text-right transition-all duration-300 hover:-translate-y-1">
    <div class="w-full aspect-[16/9] rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 mb-4">
    <img src="public/assets/images/badimalika.jpg" alt="Building Scalable ERPs" class="w-full h-full object-cover">
    </div>
    <span class="font-body text-xs text-slate-500 dark:text-slate-500 mb-2 block">15 يونيو 2026</span>
    <h3 class="font-sans text-base md:text-lg font-bold text-slate-900 dark:text-white m-0 mb-2 tracking-tight line-clamp-2 leading-snug" data-ar="بناء أنظمة الـ ERP المؤسسية بـ Laravel و Redis" data-en="Building Scalable Enterprise ERPs with Laravel & Redis">
    بناء أنظمة الـ ERP المؤسسية بـ Laravel و Redis
    </h3>
    <p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0 mb-5 flex-grow line-clamp-2" data-ar="دراسة شاملة حول الهيكلية البرمجية النمطية، مصفوفات الصلاحيات RBAC، والتخزين المؤقت عالي السرعة..." data-en="A comprehensive study on modular enterprise architecture, RBAC authorization matrices, and caching...">
    دراسة شاملة حول الهيكلية البرمجية النمطية، مصفوفات الصلاحيات RBAC، والتخزين المؤقت عالي السرعة...
    </p>
    <a href="articles" class="font-sans text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 transition-colors duration-200 mt-auto group" data-ar="قراءة المقال ⟵" data-en="Read paper &rarr;">
    قراءة المقال ⟵
    </a>
    </div>

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-5 backdrop-blur-xl shadow-xl dark:shadow-2xl box-border text-left rtl:text-right transition-all duration-300 hover:-translate-y-1">
    <div class="w-full aspect-[16/9] rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 mb-4">
    <img src="public/assets/images/Mountains-Nepal-II.jpg" alt="REST APIs & PWA" class="w-full h-full object-cover">
    </div>
    <span class="font-body text-xs text-slate-500 dark:text-slate-500 mb-2 block">12 يونيو 2026</span>
    <h3 class="font-sans text-base md:text-lg font-bold text-slate-900 dark:text-white m-0 mb-2 tracking-tight line-clamp-2 leading-snug" data-ar="تصميم واجهات الـ REST APIs وتطبيقات الـ PWA" data-en="Designing High-Throughput REST APIs & PWA Systems">
    تصميم واجهات الـ REST APIs وتطبيقات الـ PWA
    </h3>
    <p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0 mb-5 flex-grow line-clamp-2" data-ar="أفضل الممارسات في بناء الميكروسيرفيس، المصادقة الرقمية بـ JWT، وتطبيقات الويب الشغالة بدون إنترنت..." data-en="Best practices for microservices, JWT authentication, asynchronous job queues, and offline PWAs...">
    أفضل الممارسات في بناء الميكروسيرفيس، المصادقة الرقمية بـ JWT، وتطبيقات الويب الشغالة بدون إنترنت...
    </p>
    <a href="articles" class="font-sans text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 transition-colors duration-200 mt-auto group" data-ar="قراءة المقال ⟵" data-en="Read paper &rarr;">
    قراءة المقال ⟵
    </a>
    </div>

    <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-5 backdrop-blur-xl shadow-xl dark:shadow-2xl box-border text-left rtl:text-right transition-all duration-300 hover:-translate-y-1">
    <div class="w-full aspect-[16/9] rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 mb-4">
    <img src="public/assets/images/marigold.jpg" alt="DevOps & Security" class="w-full h-full object-cover">
    </div>
    <span class="font-body text-xs text-slate-500 dark:text-slate-500 mb-2 block">7 يونيو 2026</span>
    <h3 class="font-sans text-base md:text-lg font-bold text-slate-900 dark:text-white m-0 mb-2 tracking-tight line-clamp-2 leading-snug" data-ar="الأمن السيبراني والـ DevOps للمنصات الحكومية" data-en="DevOps & Security Best Practices for Institutional Platforms">
    الأمن السيبراني والـ DevOps للمنصات الحكومية
    </h3>
    <p class="font-body text-[0.88rem] text-slate-600 dark:text-slate-400 leading-relaxed m-0 mb-5 flex-grow line-clamp-2" data-ar="تكوين سيرفرات Nginx العكسية، حماية سيرفرات Linux، والنسخ الاحتياطي التلقائي لقواعد البيانات..." data-en="Nginx reverse proxies, Linux server hardening, automated MySQL backups, and security monitoring...">
    تكوين سيرفرات Nginx العكسية، حماية سيرفرات Linux، والنسخ الاحتياطي التلقائي لقواعد البيانات...
    </p>
    <a href="articles" class="font-sans text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5 transition-colors duration-200 mt-auto group" data-ar="قراءة المقال ⟵" data-en="Read paper &rarr;">
    قراءة المقال ⟵
    </a>
    </div>
</div>
</div>

<!-- Call to Action (CTA) Banner Section -->
<div class="col-span-full w-full px-6 sm:px-12 md:px-16 lg:px-20 box-border mb-16 md:mb-24">
<div class="group relative w-full rounded-2xl md:rounded-3xl overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200/80 dark:from-[#124e43] dark:to-[#0a2f28] border border-slate-300/60 dark:border-white/10 shadow-xl box-border transition-all duration-300">
<div class="relative z-10 w-full p-8 md:p-10 lg:p-12 flex flex-col items-center text-center md:flex-row md:items-center md:justify-between md:text-left rtl:md:text-right gap-6 md:gap-8 box-border">
<div class="flex flex-col items-center md:items-start max-w-xl">
<div class="hidden md:flex text-slate-800 dark:text-white text-3xl mb-4 opacity-90">
<i class="fas fa-paper-plane"></i>
</div>
<h2 class="font-sans text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white m-0 tracking-tight" data-ar="هل لديك مشروع برمي مؤسسي في الذهن؟" data-en="Have an enterprise project in mind?">هل لديك مشروع برمي مؤسسي في الذهن؟</h2>
<p class="font-body text-sm md:text-base text-slate-700 dark:text-white/80 m-0 mt-3 leading-relaxed" data-ar="أرحب بمناقشة الهيكلية البرمجية، الأنظمة الرقمية، والحلول متعددة المنصات." data-en="I'd love to discuss architecture, digital systems, or multi-platform software solutions.">أرحب بمناقشة الهيكلية البرمجية، الأنظمة الرقمية، والحلول متعددة المنصات.</p>
</div>
<div class="w-full md:w-auto flex flex-col items-center gap-6 flex-shrink-0">
<a href="mailto:boubakarseddikh@gmail.com" class="group/btn inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-[#124e43] font-sans font-bold text-sm md:text-base rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-200 shadow-md hover:shadow-lg w-full md:w-auto no-underline relative z-30" data-ar="دعنا نعمل معاً ⟵" data-en="Let's Work Together &rarr;">
دعنا نعمل معاً ⟵
</a>
</div>
</div>
</div>
</div>

</div>
</div>

<?php require_once __DIR__ . '/../src/includes/footer.php'; ?>