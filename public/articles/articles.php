<?php
// ── Page-specific SEO metadata ──────────────────────────────────────────────
$pageTitle = "المقالات والأبحاث البرمجية — حمزة بوبكر الصديق";
$pageMeta  = [
    'description'  => 'استعرض الأبحاث والمقالات البرمجية والدراسات المعمارية التي يشاركها المهندس حمزة بوبكر الصديق في مجالات الـ ERP و Laravel و Microservices والأمن السيبراني.',
    'keywords'     => 'مقالات حمزة بوبكر الصديق, هندسة البرمجيات, Laravel ERP, Microservices, الأمن السيبراني, Redis',
    'og_type'      => 'website',
    'og_image_alt' => 'المقالات والأبحاث البرمجية — حمزة بوبكر الصديق',
    'canonical'    => 'https://github.com/Hamza2024-CODE',
    'robots'       => 'index, follow',
];

include_once __DIR__ . '/../../src/includes/header.php';
?>

<main class="w-full max-w-6xl mx-auto py-32 px-4 sm:px-6 lg:px-8 font-body text-left rtl:text-right min-h-screen">
    <div class="mb-12 border-b border-gray-200 dark:border-slate-800 pb-6">
        <h1 class="text-4xl font-extrabold text-brandNeutral dark:text-white tracking-tight font-headline" data-ar="المقالات والدراسات البرمجية" data-en="Engineering Publications & Articles">
            المقالات والدراسات البرمجية
        </h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400" data-ar="أبحاث وملاحظات حول الهندسة المعمارية للأنظمة المؤسسية، واجهات الـ APIs، والتحول الرقمي للمهندس حمزة بوبكر الصديق" data-en="Engineering thoughts, system architecture notes, and digital transformation research.">
            أبحاث وملاحظات حول الهندسة المعمارية للأنظمة المؤسسية، واجهات الـ APIs، والتحول الرقمي للمهندس حمزة بوبكر الصديق
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <article class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl p-6 border border-gray-100 dark:border-slate-800 shadow-md hover:shadow-lg transition-all">
            <div class="w-full aspect-video rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800">
                <img src="public/assets/images/badimalika.jpg" alt="Building Scalable ERPs" class="w-full h-full object-cover">
            </div>
            <span class="text-xs text-teal-600 dark:text-teal-400 font-semibold mb-2 block">15 يونيو 2026</span>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 leading-snug">بناء أنظمة الـ ERP المؤسسية بـ Laravel و Redis</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow">دراسة شاملة حول الهيكلية البرمجية النمطية، مصفوفات الصلاحيات RBAC، والتخزين المؤقت عالي السرعة لخدمة ملايين المعاملات.</p>
            <a href="mailto:boubakarseddikh@gmail.com" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">طلب نسخة البحث الرقمي &larr;</a>
        </article>

        <article class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl p-6 border border-gray-100 dark:border-slate-800 shadow-md hover:shadow-lg transition-all">
            <div class="w-full aspect-video rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800">
                <img src="public/assets/images/Mountains-Nepal-II.jpg" alt="REST APIs & PWA" class="w-full h-full object-cover">
            </div>
            <span class="text-xs text-teal-600 dark:text-teal-400 font-semibold mb-2 block">12 يونيو 2026</span>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 leading-snug">تصميم واجهات الـ REST APIs وتطبيقات الـ PWA</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow">أفضل الممارسات في بناء الميكروسيرفيس، المصادقة الرقمية بـ JWT، وتطبيقات الويب الشغالة بدون إنترنت في البيئات الحكومية.</p>
            <a href="mailto:boubakarseddikh@gmail.com" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">طلب نسخة البحث الرقمي &larr;</a>
        </article>

        <article class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl p-6 border border-gray-100 dark:border-slate-800 shadow-md hover:shadow-lg transition-all">
            <div class="w-full aspect-video rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800">
                <img src="public/assets/images/marigold.jpg" alt="DevOps & Security" class="w-full h-full object-cover">
            </div>
            <span class="text-xs text-teal-600 dark:text-teal-400 font-semibold mb-2 block">7 يونيو 2026</span>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 leading-snug">الأمن السيبراني والـ DevOps للمنصات الحكومية</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow">تكوين سيرفرات Nginx العكسية، حماية سيرفرات Linux، والنسخ الاحتياطي التلقائي لقواعد البيانات بوزارة MFEP.</p>
            <a href="mailto:boubakarseddikh@gmail.com" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">طلب نسخة البحث الرقمي &larr;</a>
        </article>
    </div>
</main>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>