<?php
$pageTitle = "مركز الموارد وأدوات المطورين — حمزة بوبكر الصديق";
$pageMeta = [
    'description' => 'أدوات برمجية عالية الأداء بـ PHP، تحويل الكود بـ UTF-8، تنسيق وتوثيق واجهات الـ REST API، ومنهجية الهندسة المعمارية بحساب حمزة بوبكر الصديق.',
    'keywords' => 'أدوات PHP, منسق لاراڤيل, واجهات API, OMR, خريطة طريق الهندسة المعمارية',
    'og_type' => 'website',
    'og_image_alt' => 'أدوات المطورين حمزة بوبكر الصديق',
    'canonical' => 'https://github.com/Hamza2024-CODE',
    'jsonld_type' => 'WebPage',
    'jsonld_article' => null,
];
$path_prefix = '../';
include '../../src/includes/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300 min-h-screen pt-32 pb-24 px-6 sm:px-12 md:px-16 lg:px-20">
    <div class="max-w-[1320px] mx-auto text-left rtl:text-right">
        
        <!-- Header Banner -->
        <div class="mb-12">
            <span class="px-4 py-1.5 bg-teal-50 dark:bg-teal-900/30 border border-teal-200 dark:border-teal-700/50 text-teal-800 dark:text-teal-300 rounded-full text-xs font-semibold tracking-wide" data-ar="🛠️ مركز الموارد وأدوات المطورين" data-en="🛠️ Resource Center & Creator Tools">
                🛠️ مركز الموارد وأدوات المطورين
            </span>
            <h1 class="font-sans text-3xl sm:text-4xl md:text-5xl font-bold mt-4 mb-3 text-slate-900 dark:text-white" data-ar="الأدوات البرمجية وخارطة طريق الهندسة المعمارية" data-en="Developer Utilities & Learning Roadmaps">
                الأدوات البرمجية وخارطة طريق الهندسة المعمارية
            </h1>
            <p class="font-body text-base text-slate-600 dark:text-slate-400 max-w-2xl" data-ar="أدوات هندسية عالية الأداء مخصصة لتسهيل عمليات التطوير البرمجي، اختبار واجهات الـ REST API، تنسيق أكواد PHP، والتحضير لبناء الأنظمة المؤسسية." data-en="Performant, handy software engineering utilities tailored to streamline backend development, REST API testing, PHP code formatting, and system architecture preparation.">
                أدوات هندسية عالية الأداء مخصصة لتسهيل عمليات التطوير البرمجي، اختبار واجهات الـ REST API، تنسيق أكواد PHP، والتحضير لبناء الأنظمة المؤسسية.
            </p>
        </div>

        <!-- Tools Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full box-border">

            <!-- Tool 1 -->
            <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
                    <i class="fas fa-code"></i>
                </div>
                <h2 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="محول الأكواد وترميز UTF-8 بـ PHP" data-en="PHP & UTF-8 Code Converter">محول الأكواد وترميز UTF-8 بـ PHP</h2>
                <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow" data-ar="تحويل النصوص، مصفوفات UTF-8، الرموز البرمجية، وتهيئة البيانات بترميز JSON و Base64 بكل سهولة." data-en="Effortlessly convert PHP strings, UTF-8 character maps, HTML entities, and JSON data matrices.">تحويل النصوص، مصفوفات UTF-8، الرموز البرمجية، وتهيئة البيانات بترميز JSON و Base64 بكل سهولة.</p>
                <a href="tools/php_converter.php" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition-all shadow-md" data-ar="فتح الأداة ⟵" data-en="Open Tool &rarr;">
                    فتح الأداة ⟵
                </a>
            </div>

            <!-- Tool 2 -->
            <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
                    <i class="fas fa-feather-alt"></i>
                </div>
                <h2 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="منسق أكواد PHP و Laravel" data-en="PHP & Laravel Code Formatter">منسق أكواد PHP و Laravel</h2>
                <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow" data-ar="أداة تنظيف وتنسيق الأكواد البرمجية وفق المعايير القياسية العالمية PSR-12." data-en="Professional PHP code formatter, syntax highlighter, and PSR-12 standard beautifier.">أداة تنظيف وتنسيق الأكواد البرمجية وفق المعايير القياسية العالمية PSR-12.</p>
                <a href="tools/php_writer.php" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition-all shadow-md" data-ar="فتح الأداة ⟵" data-en="Open Tool &rarr;">
                    فتح الأداة ⟵
                </a>
            </div>

            <!-- Tool 3 -->
            <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="خارطة طريق هندسة البرمجيات (2026)" data-en="PHP & Architecture Syllabus">خارطة طريق هندسة البرمجيات (2026)</h2>
                <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow" data-ar="المنهج الكامل لبناء الأنظمة المؤسسية الكبرى، أنظمة الـ ERP، والـ Microservices بـ PHP و Laravel." data-en="Complete Zero-to-Hero course outline & architecture roadmap for enterprise PHP software engineers.">المنهج الكامل لبناء الأنظمة المؤسسية الكبرى، أنظمة الـ ERP، والـ Microservices بـ PHP و Laravel.</p>
                <a href="tools/php_syllabus.php" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition-all shadow-md" data-ar="فتح المنهاج ⟵" data-en="Open Syllabus &rarr;">
                    فتح المنهاج ⟵
                </a>
            </div>

            <!-- Tool 4 -->
            <div class="flex flex-col bg-slate-50/80 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl p-6 backdrop-blur-xl shadow-xl dark:shadow-2xl text-left rtl:text-right transition-all duration-200 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 border border-teal-200/60 dark:border-teal-700/50 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl mb-4">
                    <i class="fas fa-check-double"></i>
                </div>
                <h2 class="font-sans text-lg font-bold text-slate-900 dark:text-white m-0 mb-2" data-ar="المحاكي الرقمي لنظام OMR والتقييم" data-en="Virtual OMR & Assessment Evaluator">المحاكي الرقمي لنظام OMR والتقييم</h2>
                <p class="font-body text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 flex-grow" data-ar="نظام التقييم وتصحيح أوراق الإجابة الرقمية المصمم لمنصة المسابقات والتوجيه." data-en="Interactive candidate examination evaluator & digital OMR answer sheet practice engine.">نظام التقييم وتصحيح أوراق الإجابة الرقمية المصمم لمنصة المسابقات والتوجيه.</p>
                <a href="tools/omr_evaluator.php" class="inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition-all shadow-md" data-ar="فتح المحاكي ⟵" data-en="Open Tool &rarr;">
                    فتح المحاكي ⟵
                </a>
            </div>

        </div>

    </div>
</div>

<?php include '../../src/includes/footer.php'; ?>
