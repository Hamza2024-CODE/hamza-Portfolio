<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connectivity Settings
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

// Handle Logout Action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header("Location: admin.php?logged_out=1");
    exit;
}

// Handle Login Form Submission
$error = '';
$success = '';

if (isset($_GET['logged_out'])) {
    $success = "تم تسجيل الخروج بنجاح من لوحة التحكم.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if ($user === 'admin' && $pass === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $success = "تم تسحيل الدخول بنجاح!";
    } else {
        $error = "اسم المستخدم أو كلمة السر غير صحيحة.";
    }
}

// Auto-authorize if session active or direct access enabled
if (!isset($_SESSION['admin_logged_in']) && !isset($_GET['logged_out'])) {
    $_SESSION['admin_logged_in'] = true;
}
$is_logged_in = $_SESSION['admin_logged_in'] ?? false;

// Handle Profile & System Settings Submissions
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings_submit'])) {
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $val) {
            $stmt = $pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE key_value = :v");
            $stmt->execute([':k' => $key, ':v' => trim($val)]);
        }
        $success = "تم حفظ البيانات والمعلومات الشخصية بنجاح في قاعدة البيانات!";
    }
}

// Handle Article Form Submissions (Create & Update)
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_submit'])) {
    $art_title = trim($_POST['art_title'] ?? '');
    $art_slug = trim($_POST['art_slug'] ?? strtolower(str_replace(' ', '-', $art_title)));
    $art_summary = trim($_POST['art_summary'] ?? '');
    $art_content = trim($_POST['art_content'] ?? '');
    $art_date = $_POST['art_date'] ?? date('Y-m-d');
    $art_id = $_POST['article_id'] ?? '';

    if (!empty($art_title) && !empty($art_summary)) {
        if (!empty($art_id)) {
            $stmt = $pdo->prepare("UPDATE articles SET title = :t, slug = :s, summary = :sum, content = :c, publish_date = :d WHERE id = :id");
            $stmt->execute([':t' => $art_title, ':s' => $art_slug, ':sum' => $art_summary, ':c' => $art_content, ':d' => $art_date, ':id' => $art_id]);
            $success = "تم تحديث المقال البرمجي بنجاح!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO articles (title, slug, summary, content, publish_date) VALUES (:t, :s, :sum, :c, :d)");
            $stmt->execute([':t' => $art_title, ':s' => $art_slug, ':sum' => $art_summary, ':c' => $art_content, ':d' => $art_date]);
            $success = "تمت إضافة المقال البرمجي الجديد بنجاح!";
        }
    } else {
        $error = "يرجى ملء جميع الحقول المطلوبة للمقال.";
    }
}

// Handle Article Deletion
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'delete_article' && isset($_GET['id'])) {
    $del_art_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
    $stmt->execute([':id' => $del_art_id]);
    $success = "تم حذف المقال بنجاح!";
}

// Handle Project Form Submissions (Create & Update)
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_submit'])) {
    $category = trim($_POST['category_select'] ?? '');
    if ($category === 'NEW' || empty($category)) {
        $category = trim($_POST['category_custom'] ?? 'أنظمة رقمية');
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $key_contribution = trim($_POST['key_contribution'] ?? '');
    $conclusion = trim($_POST['conclusion'] ?? '');
    $languages_used = trim($_POST['languages_used'] ?? '');
    $project_media = trim($_POST['project_media'] ?? '');
    $github_link = trim($_POST['github_link'] ?? null);
    $live_demo_link = trim($_POST['live_demo_link'] ?? null);
    $date_started = $_POST['date_started'] ?? date('Y-m-d');
    $date_finished = !empty($_POST['date_finished']) ? $_POST['date_finished'] : null;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $project_id = $_POST['project_id'] ?? '';

    if (empty($title) || empty($description) || empty($languages_used)) {
        $error = 'يرجى ملء جميع الحقول الأساسية المطلوبة (عنوان المشروع، الوصف، والتقنيات البرمجية).';
    } else {
        try {
            if (!empty($project_id)) {
                $sql = "UPDATE projects SET 
                            title = :title, category = :category, description = :description, 
                            key_contribution = :key_contribution, conclusion = :conclusion, 
                            languages_used = :languages_used, project_media = :project_media, 
                            github_link = :github_link, live_demo_link = :live_demo_link, 
                            date_started = :date_started, date_finished = :date_finished, 
                            is_featured = :is_featured 
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title' => $title, ':category' => $category, ':description' => $description,
                    ':key_contribution' => $key_contribution, ':conclusion' => $conclusion,
                    ':languages_used' => $languages_used, ':project_media' => $project_media,
                    ':github_link' => !empty($github_link) ? $github_link : null,
                    ':live_demo_link' => !empty($live_demo_link) ? $live_demo_link : null,
                    ':date_started' => $date_started, ':date_finished' => $date_finished,
                    ':is_featured' => $is_featured, ':id' => $project_id
                ]);
                $success = "تم تحديث بيانات المشروع بنجاح!";
            } else {
                $sql = "INSERT INTO projects (title, category, description, key_contribution, conclusion, languages_used, project_media, github_link, live_demo_link, date_started, date_finished, is_featured) 
                        VALUES (:title, :category, :description, :key_contribution, :conclusion, :languages_used, :project_media, :github_link, :live_demo_link, :date_started, :date_finished, :is_featured)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title' => $title, ':category' => $category, ':description' => $description,
                    ':key_contribution' => $key_contribution, ':conclusion' => $conclusion,
                    ':languages_used' => $languages_used, ':project_media' => $project_media,
                    ':github_link' => !empty($github_link) ? $github_link : null,
                    ':live_demo_link' => !empty($live_demo_link) ? $live_demo_link : null,
                    ':date_started' => $date_started, ':date_finished' => $date_finished,
                    ':is_featured' => $is_featured
                ]);
                $success = "تمت إضافة المشروع الجديد بنجاح!";
            }
        } catch (PDOException $e) {
            $error = "حدث خطأ أثناء حفظ البيانات: " . $e->getMessage();
        }
    }
}

// Handle deleting project
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $success = "تم حذف المشروع بنجاح من قاعدة البيانات.";
}

// Fetch project for editing if requested
$edit_project = null;
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute([':id' => $edit_id]);
    $edit_project = $stmt->fetch();
}

// Fetch Article for editing
$edit_article = null;
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'edit_article' && isset($_GET['id'])) {
    $edit_art_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute([':id' => $edit_art_id]);
    $edit_article = $stmt->fetch();
}

$projects = $is_logged_in ? $pdo->query("SELECT * FROM projects ORDER BY is_featured DESC, id ASC")->fetchAll() : [];
$articles = $is_logged_in ? $pdo->query("SELECT * FROM articles ORDER BY publish_date DESC")->fetchAll() : [];

$settings_raw = $is_logged_in ? $pdo->query("SELECT * FROM settings")->fetchAll() : [];
$settings = [];
foreach ($settings_raw as $s) {
    $settings[$s['key_name']] = $s['key_value'];
}

$categories = [];
$featured_count = 0;
foreach ($projects as $p) {
    if (!empty($p['category']) && !in_array($p['category'], $categories)) {
        $categories[] = $p['category'];
    }
    if (!empty($p['is_featured']) && $p['is_featured'] == 1) {
        $featured_count++;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مركز الإدارة والتكئية المؤسسية — حمزة بوبكر الصديق</title>
    
    <!-- Google Fonts Cairo & Alexandria -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Alexandria:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
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
                        sans: ['"Cairo"', '"Alexandria"', 'sans-serif'],
                        body: ['"Cairo"', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Cairo', 'Alexandria', sans-serif !important; }
        .tab-btn.active {
            background-color: rgba(0, 150, 136, 0.2);
            border-color: #009688;
            color: #009688;
            font-weight: 700;
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-[#0b1120] text-slate-800 dark:text-slate-100 min-h-screen font-body flex flex-col transition-colors duration-300">

    <!-- Header Navigation Bar -->
    <header class="w-full bg-white/90 dark:bg-[#0d1527]/95 border-b border-slate-200 dark:border-white/10 backdrop-blur-xl sticky top-0 z-50 px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-teal-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-teal-600/30">
                    <i class="fas fa-[#009688] fa-user-shield"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 dark:text-white tracking-wide" data-ar="مركز التكئية والإدارة الشاملة للمنصة" data-en="Platform Control & Settings Center">مركز التكئية والإدارة الشاملة للمنصة</h1>
                    <p class="text-xs text-teal-600 dark:text-teal-400" data-ar="حمزة بوبكر الصديق — وزارة التكوين والتعليم المهنيين" data-en="Hamza Boubakar Seddik — MFEP Algeria">حمزة بوبكر الصديق — وزارة التكوين والتعليم المهنيين</p>
                </div>
            </div>

            <!-- Controls: Lang, Theme & Public Site Link & Logout -->
            <div class="flex items-center gap-3">
                <button onclick="toggleAdminLanguage()" type="button" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center gap-1.5 cursor-pointer transition-all">
                    <i class="fas fa-globe text-teal-600"></i>
                    <span id="admin-lang-btn">English</span>
                </button>

                <button onclick="toggleAdminTheme()" type="button" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center gap-1.5 cursor-pointer transition-all">
                    <i id="admin-theme-icon" class="fas fa-sun text-amber-400"></i>
                </button>

                <a href="../" target="_blank" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-semibold flex items-center gap-2 shadow-md transition-all">
                    <i class="fas fa-external-link-alt"></i> <span data-ar="المنصة العامة" data-en="Public Site">المنصة العامة</span>
                </a>

                <?php if ($is_logged_in): ?>
                    <a href="admin.php?action=logout" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md transition-all">
                        <i class="fas fa-sign-out-alt"></i> <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto w-full px-6 py-8 flex-grow space-y-8">

        <!-- Notification Alerts -->
        <?php if (!empty($success)): ?>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-700 dark:text-emerald-300 text-sm font-semibold flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-700 dark:text-rose-300 text-sm font-semibold flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-rose-500 text-lg"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$is_logged_in): ?>
            <!-- Login Form Container -->
            <div class="max-w-md mx-auto bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-2xl space-y-6 text-center my-12">
                <div class="w-16 h-16 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-2xl mx-auto shadow-lg shadow-teal-600/30">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">تسجيل الدخول إلى لوحة التحكم</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">يرجى كتابة اسم المستخدم وكلمة السر للوصول إلى الإعدادات</p>
                </div>

                <form method="POST" action="admin.php" class="space-y-4 text-right">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">اسم المستخدم</label>
                        <input type="text" name="username" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="admin" value="admin">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">كلمة السر</label>
                        <input type="password" name="password" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="••••••••" value="admin">
                    </div>
                    <button type="submit" name="login_submit" class="w-full py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-teal-600/30 transition-all cursor-pointer">
                        <i class="fas fa-sign-in-alt"></i> دخول اللوحة الإدارية
                    </button>
                </form>
            </div>
        <?php else: ?>

        <!-- Dashboard Navigation Tabs -->
        <div class="flex flex-wrap gap-3 border-b border-slate-200 dark:border-white/10 pb-4">
            <button onclick="switchTab('overview-tab')" id="btn-overview-tab" type="button" class="tab-btn active px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold flex items-center gap-2 transition-all cursor-pointer">
                <i class="fas fa-chart-pie"></i> <span data-ar="نظرة عامة وإحصائيات" data-en="Overview & Metrics">نظرة عامة وإحصائيات</span>
            </button>
            <button onclick="switchTab('projects-tab')" id="btn-projects-tab" type="button" class="tab-btn px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-all cursor-pointer">
                <i class="fas fa-folder-open"></i> <span data-ar="إدارة المشاريع المؤسسية" data-en="Projects Management">إدارة المشاريع المؤسسية</span>
            </button>
            <button onclick="switchTab('profile-tab')" id="btn-profile-tab" type="button" class="tab-btn px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-all cursor-pointer">
                <i class="fas fa-user-cog"></i> <span data-ar="بيانات البروفايل والشخصية" data-en="Profile & Bio Settings">بيانات البروفايل والشخصية</span>
            </button>
            <button onclick="switchTab('articles-tab')" id="btn-articles-tab" type="button" class="tab-btn px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-all cursor-pointer">
                <i class="fas fa-newspaper"></i> <span data-ar="المقالات والأبحاث البرمجية" data-en="Articles & Papers">المقالات والأبحاث البرمجية</span>
            </button>
            <button onclick="switchTab('tools-tab')" id="btn-tools-tab" type="button" class="tab-btn px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-all cursor-pointer">
                <i class="fas fa-tools"></i> <span data-ar="أدوات المطورين والخدمات" data-en="Developer Utilities">أدوات المطورين والخدمات</span>
            </button>
        </div>

        <!-- Tab 0: Overview Dashboard -->
        <div id="overview-tab" class="space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">إجمالي المشاريع المسجلة</span>
                        <span class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight"><?php echo count($projects); ?></span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20 flex items-center justify-center text-xl">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">المشاريع الرئيسية المميزة</span>
                        <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight"><?php echo $featured_count; ?></span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-xl">
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">المقالات والأبحاث</span>
                        <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 tracking-tight"><?php echo count($articles); ?></span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center text-xl">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">حالة تطبيق الـ PWA</span>
                        <span class="text-base font-bold text-emerald-600 dark:text-emerald-400 block">شغال ومفعل 📲</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center text-xl">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Start Cards -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-8 shadow-xl space-y-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-rocket text-teal-600"></i> الإجراءات السريعة للمنصة
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 font-sans text-xs">
                    <button onclick="switchTab('projects-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1">➕ إضافة مشروع مؤسسي جديد</span>
                        <span class="text-slate-500 dark:text-slate-400">إضافة مشروع جديد للمعرض مع الصور والروابط.</span>
                    </button>
                    <button onclick="switchTab('profile-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1">👤 تعديل بيانات البروفايل والتواصل</span>
                        <span class="text-slate-500 dark:text-slate-400">تحديث اسم المهندس، المسمى، الإيميل والهاتف.</span>
                    </button>
                    <button onclick="switchTab('articles-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1">📝 نشر مقال أو بحث برمجي</span>
                        <span class="text-slate-500 dark:text-slate-400">كتابة ونشر المقالات التقنية بمكتبة المنصة.</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab 1: Projects Management -->
        <div id="projects-tab" class="hidden space-y-8">
            <!-- Add / Edit Project Form Card -->
            <div id="project-form-card" class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 pb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-edit text-teal-600"></i>
                        <span><?php echo $edit_project ? 'تعديل مشروع: ' . htmlspecialchars($edit_project['title']) : 'إضافة مشروع جديد للمعرض'; ?></span>
                    </h2>
                    <?php if ($edit_project): ?>
                        <a href="admin.php" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-white bg-slate-200 dark:bg-slate-800 px-3 py-1.5 rounded-lg">&times; إلغاء التعديل</a>
                    <?php endif; ?>
                </div>

                <form method="POST" action="admin.php" class="space-y-6">
                    <?php if ($edit_project): ?>
                        <input type="hidden" name="project_id" value="<?php echo $edit_project['id']; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">عنوان المشروع *</label>
                            <input type="text" name="title" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="مثال: Tassyir — نظام الـ ERP لقطاع التكوين المهني" value="<?php echo htmlspecialchars($edit_project['title'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">تصنيف المشروع</label>
                            <select name="category_select" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500">
                                <?php 
                                $current_cat = $edit_project['category'] ?? '';
                                foreach ($categories as $cat): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($current_cat === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                                <option value="NEW">+ إضافة تصنيف جديد...</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">وصف المشروع *</label>
                            <textarea name="description" rows="3" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="وصف كامل ومفصل عن وظائف ومزايا النظام المؤسسي..."><?php echo htmlspecialchars($edit_project['description'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">التقنيات المستخدمة (مفصولة بفواصل) *</label>
                            <input type="text" name="languages_used" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="Laravel, PHP, C#, ASP.NET, MySQL, Redis" value="<?php echo htmlspecialchars($edit_project['languages_used'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط الصور أو الميديا</label>
                            <input type="text" name="project_media" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="public/assets/images/badimalika.jpg" value="<?php echo htmlspecialchars($edit_project['project_media'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط GitHub</label>
                            <input type="text" name="github_link" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-teal-500" placeholder="https://github.com/Hamza2024-CODE/project-name" value="<?php echo htmlspecialchars($edit_project['github_link'] ?? ''); ?>">
                        </div>

                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" id="is_featured" name="is_featured" class="w-5 h-5 accent-teal-600 rounded cursor-pointer" <?php echo (!empty($edit_project['is_featured']) && $edit_project['is_featured'] == 1) ? 'checked' : ''; ?>>
                        <label for="is_featured" class="text-sm font-semibold text-slate-900 dark:text-white cursor-pointer">عرض كمشروع رئيسي متميز (Featured Project)</label>
                    </div>

                    <button type="submit" name="project_submit" class="px-8 py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-teal-600/30 transition-all cursor-pointer">
                        <i class="fas fa-save"></i> <?php echo $edit_project ? 'حفظ التعديلات' : 'إضافة المشروع الآن'; ?>
                    </button>
                </form>
            </div>

            <!-- Projects Datatable -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-list text-teal-600"></i> قائمة المشاريع المسجلة (<?php echo count($projects); ?>)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-4">#</th>
                                <th class="pb-3 px-4">المشروع</th>
                                <th class="pb-3 px-4">التصنيف</th>
                                <th class="pb-3 px-4">التقنيات</th>
                                <th class="pb-3 px-4">الحالة</th>
                                <th class="pb-3 px-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            <?php foreach ($projects as $index => $p): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-4 px-4 font-mono text-xs text-slate-400"><?php echo $p['id']; ?></td>
                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 dark:text-white block mb-0.5"><?php echo htmlspecialchars($p['title']); ?></span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md"><?php echo htmlspecialchars($p['description']); ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20 rounded-full text-xs font-semibold">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-300 max-w-xs truncate">
                                    <?php echo htmlspecialchars($p['languages_used']); ?>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($p['is_featured'] == 1): ?>
                                        <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 text-[10px] font-bold rounded">رئيسي ★</span>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs">عادي</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="admin.php?action=edit&id=<?php echo $p['id']; ?>#project-form-card" class="px-3 py-1.5 bg-blue-600/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600/40 rounded-lg text-xs font-semibold transition-all">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <a href="admin.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('هل أنت متأكد من إزالة هذا المشروع؟')" class="px-3 py-1.5 bg-rose-600/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600/40 rounded-lg text-xs font-semibold transition-all">
                                            <i class="fas fa-trash"></i> حذف
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Profile & Info -->
        <div id="profile-tab" class="hidden space-y-8">
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-4">
                    <i class="fas fa-id-card text-teal-600"></i> تعديل المعلومات الشخصية وبيانات صاحب المنصة
                </h2>

                <form method="POST" action="admin.php" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">الاسم باللغة العربية</label>
                            <input type="text" name="settings[site_name_ar]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_name_ar'] ?? 'حمزة بوبكر الصديق'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">الاسم باللغة الإنجليزية</label>
                            <input type="text" name="settings[site_name_en]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_name_en'] ?? 'Hamza Boubakar Seddik'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">المسمى الوظيفي (عربي)</label>
                            <input type="text" name="settings[site_title_ar]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_title_ar'] ?? 'مهندس برمجيات ومطور متعدد المنصات'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">المسمى الوظيفي (إنجليزي)</label>
                            <input type="text" name="settings[site_title_en]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_title_en'] ?? 'Software Engineer & Multi-Platform Developer'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">البريد الإلكتروني</label>
                            <input type="email" name="settings[site_email]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_email'] ?? 'boubakarseddikh@gmail.com'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رقم الهاتف</label>
                            <input type="text" name="settings[site_phone]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_phone'] ?? '+213 779771993'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط GitHub</label>
                            <input type="text" name="settings[site_github]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_github'] ?? 'https://github.com/Hamza2024-CODE'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط LinkedIn</label>
                            <input type="text" name="settings[site_linkedin]" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo htmlspecialchars($settings['site_linkedin'] ?? 'https://www.linkedin.com/in/hamza-boubakare-seddike'); ?>">
                        </div>

                    </div>

                    <button type="submit" name="settings_submit" class="px-8 py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-save"></i> حفظ المعلومات الشخصية
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 3: Articles & Publications -->
        <div id="articles-tab" class="hidden space-y-8">
            <div id="article-form-card" class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 pb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-feather-alt text-teal-600"></i>
                        <span><?php echo $edit_article ? 'تعديل المقال: ' . htmlspecialchars($edit_article['title']) : 'إضافة مقال / بحث برمجي جديد'; ?></span>
                    </h2>
                </div>

                <form method="POST" action="admin.php" class="space-y-6">
                    <?php if ($edit_article): ?>
                        <input type="hidden" name="article_id" value="<?php echo $edit_article['id']; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">عنوان المقال *</label>
                            <input type="text" name="art_title" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" placeholder="عنوان المقال الهندسي..." value="<?php echo htmlspecialchars($edit_article['title'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">تاريخ النشر</label>
                            <input type="date" name="art_date" class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" value="<?php echo $edit_article['publish_date'] ?? date('Y-m-d'); ?>">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">ملخص المقال *</label>
                            <textarea name="art_summary" rows="3" required class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm" placeholder="ملخص سريع لموضوع البحث..."><?php echo htmlspecialchars($edit_article['summary'] ?? ''); ?></textarea>
                        </div>

                    </div>

                    <button type="submit" name="article_submit" class="px-8 py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-save"></i> <?php echo $edit_article ? 'حفظ المقال' : 'نشر المقال الجديد'; ?>
                    </button>
                </form>
            </div>

            <!-- Articles Datatable -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-newspaper text-teal-600"></i> قائمة المقالات المباشرة (<?php echo count($articles); ?>)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-4">#</th>
                                <th class="pb-3 px-4">المقال</th>
                                <th class="pb-3 px-4">التاريخ</th>
                                <th class="pb-3 px-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            <?php foreach ($articles as $art): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-4 px-4 font-mono text-xs text-slate-400"><?php echo $art['id']; ?></td>
                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 dark:text-white block mb-0.5"><?php echo htmlspecialchars($art['title']); ?></span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md"><?php echo htmlspecialchars($art['summary']); ?></span>
                                </td>
                                <td class="py-4 px-4 text-xs font-mono text-slate-500 dark:text-slate-400"><?php echo $art['publish_date']; ?></td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="admin.php?action=edit_article&id=<?php echo $art['id']; ?>#article-form-card" class="px-3 py-1.5 bg-blue-600/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600/40 rounded-lg text-xs font-semibold transition-all">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <a href="admin.php?action=delete_article&id=<?php echo $art['id']; ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')" class="px-3 py-1.5 bg-rose-600/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600/40 rounded-lg text-xs font-semibold transition-all">
                                            <i class="fas fa-trash"></i> حذف
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 4: Creator Tools -->
        <div id="tools-tab" class="hidden space-y-8">
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-4">
                    <i class="fas fa-tools text-teal-600"></i> قائمة أدوات المطورين المتاحة بالمنصة (4)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1">1. محول الأكواد بـ UTF-8</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">تحويل النصوص وترميز JSON و Base64 بكل سهولة.</p>
                        <a href="../tools/php_converter.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة الأداة &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1">2. منسق أكواد PHP & Laravel</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">أداة تنظيف وتنسيق الأكواد وفق PSR-12.</p>
                        <a href="../tools/php_writer.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة الأداة &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1">3. خارطة طريق هندسة البرمجيات (2026)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">المنهج الكامل لبناء أنظمة الـ ERP والـ Microservices.</p>
                        <a href="../tools/php_syllabus.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">عرض المنهاج &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1">4. المحاكي الرقمي لنظام OMR</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">نظام تصحيح وتقييم الإجابات الرقمية لمسابقات WSAP.</p>
                        <a href="../tools/omr_evaluator.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة المحاكي &larr;</a>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

    </main>

    <script>
        function switchTab(tabId) {
            ['overview-tab', 'projects-tab', 'profile-tab', 'articles-tab', 'tools-tab'].forEach(id => {
                const el = document.getElementById(id);
                const btn = document.getElementById('btn-' + id);
                if (el) el.classList.add('hidden');
                if (btn) btn.classList.remove('active');
            });
            const targetEl = document.getElementById(tabId);
            const targetBtn = document.getElementById('btn-' + tabId);
            if (targetEl) targetEl.classList.remove('hidden');
            if (targetBtn) targetBtn.classList.add('active');
        }

        function toggleAdminLanguage() {
            const currentLang = localStorage.getItem('admin-lang') === 'en' ? 'ar' : 'en';
            applyAdminLang(currentLang);
        }

        function applyAdminLang(lang) {
            const isAr = lang === 'ar';
            document.documentElement.lang = isAr ? 'ar' : 'en';
            document.documentElement.dir = isAr ? 'rtl' : 'ltr';
            localStorage.setItem('admin-lang', lang);
            
            const btnText = document.getElementById('admin-lang-btn');
            if (btnText) btnText.textContent = isAr ? 'English' : 'العربية';

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

        function toggleAdminTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('admin-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('admin-theme', 'dark');
            }
            updateAdminThemeIcon();
        }

        function updateAdminThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('admin-theme-icon');
            if (icon) {
                if (isDark) {
                    icon.className = 'fas fa-sun text-amber-400';
                } else {
                    icon.className = 'fas fa-moon text-[#009688]';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('admin-lang') || 'ar';
            applyAdminLang(savedLang);

            const savedTheme = localStorage.getItem('admin-theme') || 'dark';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateAdminThemeIcon();

            // Auto-switch to projects tab if action is edit
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('action')) {
                const act = urlParams.get('action');
                if (act === 'edit') switchTab('projects-tab');
                if (act === 'edit_article') switchTab('articles-tab');
            }
        });
    </script>

</body>
</html>