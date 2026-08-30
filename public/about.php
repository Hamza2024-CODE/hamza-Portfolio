<?php
// 1. Meta attributes for About page
$pageTitle = "عن المهندس حمزة بوبكر الصديق | مهندس برمجيات ومطور متعدد المنصات";
$pageMeta  = [
    'description'  => 'تعرف على المهندس حمزة بوبكر الصديق — مهندس برمجيات ومطور متعدد المنصات بوزارة التكوين والتعليم المهنيين بالجزائر، خبرة 7+ سنوات في بناء الأنظمة المؤسسية وتطبيقات Laravel و C# و ASP.NET و Java.',
    'keywords'     => 'عن حمزة بوبكر الصديق, مهندس برمجيات الجزائر, مطور متعدد المنصات, وزارة التكوين المهني, DEFP سعيدة, الفائز بالهاكاثون 2026',
    'og_type'      => 'profile',
    'og_image_alt' => 'عن المهندس حمزة بوبكر الصديق — مهندس برمجيات ومطور متعدد المنصات',
    'canonical'    => 'https://github.com/Hamza2024-CODE',
    'robots'       => 'index, follow',
];

require_once __DIR__ . '/../src/includes/header.php';
?>

<!-- FontAwesome Icons for Icons rendering -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<main class="min-h-screen bg-slate-50 dark:bg-[#0d1527] font-body text-slate-800 dark:text-slate-200 py-16 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-5xl mx-auto space-y-12 text-left rtl:text-right">
        
        <!-- Hero Header Card -->
        <header class="bg-white dark:bg-slate-900 rounded-[32px] p-8 sm:p-12 border border-slate-200/80 dark:border-white/5 shadow-xl transition-all duration-300">
            <div class="inline-block px-4 py-1.5 bg-teal-50 dark:bg-teal-900/30 border border-teal-200 dark:border-teal-700/50 text-teal-800 dark:text-teal-300 rounded-full text-xs font-semibold tracking-wide mb-4" data-ar="الملف الشخصي والمهني" data-en="Professional Profile">
                الملف الشخصي والمهني
            </div>
            <h1 class="font-sans font-bold text-3xl sm:text-5xl text-slate-900 dark:text-white tracking-tight mb-4 leading-tight" data-ar="حمزة بوبكر الصديق" data-en="Hamza Boubakar Seddik">
                حمزة بوبكر الصديق
            </h1>
            <p class="font-sans text-lg sm:text-xl font-semibold text-teal-600 dark:text-teal-400 mb-6" data-ar="مهندس برمجيات ومطور متعدد المنصات" data-en="Software Engineer & Multi-Platform Developer">
                مهندس برمجيات ومطور متعدد المنصات
            </p>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl" data-ar="مهندس برمجيات ومطور متعدد المنصات بوزارة التكوين والتعليم المهنيين (MFEP)، متخصص في بناء الأنظمة الرقمية المؤسسية، تطبيقات الـ ERP، والحلول البرمجية عالية الأداء بخبرة تتجاوز 7 سنوات في التطوير والتصميم الهيكلي." data-en="Software Engineer & Multi-Platform Developer at the Ministry of Vocational Training and Education (MFEP), specialized in building end-to-end digital systems, enterprise applications, and scalable web platforms with 7+ years of hands-on engineering experience.">
                مهندس برمجيات ومطور متعدد المنصات بوزارة التكوين والتعليم المهنيين (MFEP)، متخصص في بناء الأنظمة الرقمية المؤسسية، تطبيقات الـ ERP، والحلول البرمجية عالية الأداء بخبرة تتجاوز 7 سنوات في التطوير والتصميم الهيكلي.
            </p>
            
            <div class="flex flex-wrap gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-white/5 text-xs font-medium text-slate-600 dark:text-slate-400">
                <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-teal-600"></i> <span data-ar="الجزائر العاصمة، الجزائر" data-en="Algiers, Algeria">الجزائر العاصمة، الجزائر</span></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-teal-600"></i> boubakarseddikh@gmail.com</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-phone text-teal-600"></i> +213 779771993</span>
                <a href="https://github.com/Hamza2024-CODE" target="_blank" class="flex items-center gap-1.5 text-teal-600 dark:text-teal-400 hover:underline"><i class="fab fa-github"></i> github.com/Hamza2024-CODE</a>
            </div>
        </header>

        <!-- Professional Experience & Skills Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Professional Experience (2 Columns) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Experience Block -->
                <div class="bg-white dark:bg-slate-900 rounded-[32px] p-8 border border-slate-200/80 dark:border-white/5 shadow-xl">
                    <h2 class="font-sans font-bold text-2xl text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-briefcase text-teal-600"></i> <span data-ar="الخبرة المهنية" data-en="Professional Experience">الخبرة المهنية</span>
                    </h2>
                    
                    <div class="space-y-8">
                        <!-- Role 1 -->
                        <div class="relative pr-6 border-r-2 border-teal-500/40 rtl:pr-6 rtl:border-r-2 ltr:pl-6 ltr:border-l-2">
                            <span class="absolute -right-[9px] top-0 w-4 h-4 rounded-full bg-teal-600"></span>
                            <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-wider" data-ar="2026 – الحاضر" data-en="2026 – Present">2026 – الحاضر</span>
                            <h3 class="font-sans font-bold text-lg text-slate-900 dark:text-white mt-1" data-ar="مهندس برمجيات ومطور متعدد المنصات" data-en="Software Engineer & Multi-Platform Developer">مهندس برمجيات ومطور متعدد المنصات</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3" data-ar="وزارة التكوين والتعليم المهنيين (MFEP) | الجزائر العاصمة" data-en="Ministry of Vocational Training & Education (MFEP) | Algiers">وزارة التكوين والتعليم المهنيين (MFEP) | الجزائر العاصمة</p>
                            <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                <li data-ar="تطوير المنصات المؤسسية، الأنظمة المتكاملة، واجهات الـ APIs، والحلول متعددة المنصات." data-en="Architecting enterprise platforms, integrated ERP systems, REST APIs, and multi-platform solutions.">تطوير المنصات المؤسسية، الأنظمة المتكاملة، واجهات الـ APIs، والحلول متعددة المنصات.</li>
                                <li data-ar="قيادة الهيكلية البرمجية، الأمن السيبراني، البنية التحتية، وتحسين أداء المنصات الحكومية." data-en="Leading software architecture, cybersecurity, server infrastructure, and performance optimization for government platforms.">قيادة الهيكلية البرمجية، الأمن السيبراني، البنية التحتية، وتحسين أداء المنصات الحكومية.</li>
                            </ul>
                        </div>

                        <!-- Role 2 -->
                        <div class="relative pr-6 border-r-2 border-teal-500/40 rtl:pr-6 rtl:border-r-2 ltr:pl-6 ltr:border-l-2">
                            <span class="absolute -right-[9px] top-0 w-4 h-4 rounded-full bg-teal-600"></span>
                            <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-wider" data-ar="2023 – 2026" data-en="2023 – 2026">2023 – 2026</span>
                            <h3 class="font-sans font-bold text-lg text-slate-900 dark:text-white mt-1" data-ar="مهندس برمجيات / مطور أنظمة" data-en="Software Engineer / Systems Developer">مهندس برمجيات / مطور أنظمة</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3" data-ar="مديرية التكوين والتعليم المهنيين (DEFP) | سعيدة، الجزائر" data-en="Directorate of Vocational Training & Education (DEFP) | Saida, Algeria">مديرية التكوين والتعليم المهنيين (DEFP) | سعيدة، الجزائر</p>
                            <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                <li data-ar="تطوير المنصات الإدارية، واجهات الـ REST APIs، ولوحات التحكم التفاعلية للمؤسسات." data-en="Building management dashboards, REST APIs, and administrative control suites.">تطوير المنصات الإدارية، واجهات الـ REST APIs، ولوحات التحكم التفاعلية للمؤسسات.</li>
                                <li data-ar="تحديث الأنظمة القديمة وأتمتة الإجراءات الإدارية بالمديريات الولائية." data-en="Modernizing legacy systems and automating administrative workflows across regional directorates.">تحديث الأنظمة القديمة وأتمتة الإجراءات الإدارية بالمديريات الولائية.</li>
                            </ul>
                        </div>

                        <!-- Role 3 -->
                        <div class="relative pr-6 border-r-2 border-teal-500/40 rtl:pr-6 rtl:border-r-2 ltr:pl-6 ltr:border-l-2">
                            <span class="absolute -right-[9px] top-0 w-4 h-4 rounded-full bg-teal-600"></span>
                            <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-wider" data-ar="2019 – 2023" data-en="2019 – 2023">2019 – 2023</span>
                            <h3 class="font-sans font-bold text-lg text-slate-900 dark:text-white mt-1" data-ar="مطور برمجيات وحلول رقمية" data-en="Software & Digital Solutions Developer">مطور برمجيات وحلول رقمية</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3" data-ar="خبرة التطوير المستقل والمؤسسي | الجزائر" data-en="Enterprise & Freelance Software Development | Algeria">خبرة التطوير المستقل والمؤسسي | الجزائر</p>
                            <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                <li data-ar="إنجاز تطبيقات الويب والموبايل والحلول البرمجية من الفكرة إلى الإنتاج النهائي." data-en="Delivering full-stack web applications, mobile apps, and custom software from concept to production.">إنجاز تطبيقات الويب والموبايل والحلول البرمجية من الفكرة إلى الإنتاج النهائي.</li>
                                <li data-ar="تطوير واجهات برمجة التطبيقات APIs، قواعد البيانات، وتطبيقات الويب التقدمية PWA." data-en="Developing robust APIs, database schemas, and Progressive Web Apps (PWA).">تطوير واجهات برمجة التطبيقات APIs، قواعد البيانات، وتطبيقات الويب التقدمية PWA.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Education & Certifications Block -->
                <div class="bg-white dark:bg-slate-900 rounded-[32px] p-8 border border-slate-200/80 dark:border-white/5 shadow-xl">
                    <h2 class="font-sans font-bold text-2xl text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-graduation-cap text-teal-600"></i> <span data-ar="التعليم والشهادات" data-en="Education & Certifications">التعليم والشهادات</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-white/5">
                            <span class="font-bold text-slate-900 dark:text-white block mb-1" data-ar="الشهادة الجامعية العليا" data-en="Higher University Degree">الشهادة الجامعية العليا</span>
                            <span class="text-teal-600 font-semibold block mb-1" data-ar="تقني سامي & ماستر 2 في الإعلام الآلي" data-en="Senior Technician & Master's in Computer Science">تقني سامي & ماستر 2 في الإعلام الآلي</span>
                            <p class="text-slate-500 dark:text-slate-400" data-ar="تخصص تطوير البرمجيات والأنظمة الرقمية." data-en="Specialized in Software Engineering & Digital Systems.">تخصص تطوير البرمجيات والأنظمة الرقمية.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-white/5">
                            <span class="font-bold text-slate-900 dark:text-white block mb-1" data-ar="المركز الأول في الهاكاثون (2026)" data-en="1st Place Hackathon Winner (2026)">المركز الأول في الهاكاثون (2026)</span>
                            <span class="text-teal-600 font-semibold block mb-1" data-ar="المرتبة الأولى في مسابقة التطوير" data-en="First Rank in National Software Competition">المرتبة الأولى في مسابقة التطوير</span>
                            <p class="text-slate-500 dark:text-slate-400" data-ar="الفائز بالمركز الأول لحل برمجيات التحول الرقمي السريع." data-en="Awarded 1st Place for rapid digital transformation software solution.">الفائز بالمركز الأول لحل برمجيات التحول الرقمي السريع.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Skills & Specializations Sidebar (1 Column) -->
            <div class="space-y-8">
                
                <div class="bg-white dark:bg-slate-900 rounded-[32px] p-8 border border-slate-200/80 dark:border-white/5 shadow-xl">
                    <h2 class="font-sans font-bold text-xl text-slate-900 dark:text-white mb-6 flex items-center gap-2.5">
                        <i class="fas fa-laptop-code text-teal-600"></i> <span data-ar="المهارات التقنية" data-en="Technical Skills">المهارات التقنية</span>
                    </h2>
                    
                    <div class="space-y-4 font-sans text-xs">
                        <div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 block mb-2" data-ar="لغات البرمجة" data-en="Programming Languages">لغات البرمجة</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">PHP</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">C#</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Java</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">JavaScript</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">SQL</span>
                            </div>
                        </div>

                        <div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 block mb-2" data-ar="أطر العمل والتقنيات" data-en="Frameworks & Technologies">أطر العمل والتقنيات</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Laravel</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">ASP.NET</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Java EE</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">REST APIs</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">PWA & Mobile</span>
                            </div>
                        </div>

                        <div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 block mb-2" data-ar="قواعد البيانات والسيرفرات" data-en="Databases & DevOps">قواعد البيانات والسيرفرات</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">MySQL / MariaDB</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Redis</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Linux / Nginx</span>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-white/5 rounded-md text-slate-700 dark:text-slate-300">Google Cloud</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>

<?php include '../src/includes/footer.php'; ?>
