<?php
$pageTitle = "منهج وخارطة طريق هندسة البرمجيات 2026 — حمزة بوبكر الصديق";
$path_prefix = '../';
include '../../src/includes/header.php';
?>

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300 min-h-screen pt-32 pb-24 px-6 sm:px-12 md:px-16 lg:px-20">
    <div class="max-w-[1000px] mx-auto text-left rtl:text-right">
        <a href="tools/" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-4 inline-block">&larr; العودة لمركز الأدوات</a>
        <h1 class="font-sans text-3xl font-bold text-slate-900 dark:text-white mb-2">منهج وخارطة طريق هندسة البرمجيات (2026)</h1>
        <p class="font-body text-sm text-slate-600 dark:text-slate-400 mb-8">المنهاج الشامل لبناء أنظمة الـ ERP المؤسسية، واجهات الـ REST APIs عالية السرعة، والأنظمة الموزعة بـ PHP و Laravel.</p>

        <div class="space-y-6">
            <div class="p-6 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl">
                <h3 class="text-lg font-bold text-teal-600 dark:text-teal-400 mb-2">الوحدة 1: الأساسيات المتقدمة لـ PHP 8 ومبادئ البرمجة الكائنية OOP</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">الـ Namespaces، التحميل التلقائي PSR-4، تحديد الأنواع Type Hinting، Attributes، Enums، المعالجة المتزامنة Fiber، ومبادئ التصميم SOLID.</p>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl">
                <h3 class="text-lg font-bold text-teal-600 dark:text-teal-400 mb-2">الوحدة 2: إطار عمل Laravel للمؤسسات والأنظمة الموزعة Microservices</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">الـ Service Providers، حاقن التبعيات Dependency Injection، طبقات الـ Middleware، علاقات Eloquent ORM، التهجير Migrations، طوابير التجميع Queues & Redis، والمصادقة الرقمية Sanctum / Passport OAuth2.</p>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl">
                <h3 class="text-lg font-bold text-teal-600 dark:text-teal-400 mb-2">الوحدة 3: هندسة قواعد البيانات وتحسين استعلامات SQL</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">تصميم جداول MySQL / MariaDB، استراتيجيات الفهرسة Indexing (B-Tree, Full-Text)، درجات العزل Transactions، القيود المرجعية، واستعلامات CTEs لتحسين الأداء.</p>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl">
                <h3 class="text-lg font-bold text-teal-600 dark:text-teal-400 mb-2">الوحدة 4: التطبيقات التقدمية PWA والأمن السيبراني للمنصات الحكومية</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">الـ Service Workers، التخزين المؤقت دون إنترنت Offline Caching، إعدادات سيرفر Nginx العكسي، حماية سيرفرات Linux، مصفوفات الصلاحيات RBAC، وسجلات التفتيش Audit Logs.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../src/includes/footer.php'; ?>
