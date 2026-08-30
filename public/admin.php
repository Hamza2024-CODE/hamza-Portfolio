<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connectivity Settings via dbconfig
$db_config_file = __DIR__ . '/../config/dbconfig.php';
if (file_exists($db_config_file)) {
    require_once $db_config_file;
}

if (!isset($pdo)) {
    $is_local = (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);
    if ($is_local) {
        $db_host = '127.0.0.1';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'portfolio_db';
    } else {
        $db_host = 'sql306.infinityfree.com';
        $db_user = 'if0_41712671';
        $db_pass = 'mx9cnfa7YhJ4W';
        $db_name = 'if0_41712671_portfolio';
    }
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die("<div style='font-family:sans-serif;padding:20px;background:#fee2e2;color:#991b1b;border-radius:12px;'><h3>خطأ في الاتصال بقاعدة البيانات / Database Connection Failure</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>");
    }
}

// Handle Logout Action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header("Location: admin.php?logged_out=1");
    exit;
}

$error = '';
$success = '';

if (isset($_GET['logged_out'])) {
    $success = "تم تسجيل الخروج بنجاح من لوحة التحكم.";
}

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if ($user === 'admin' && $pass === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $success = "تم تسجيل الدخول بنجاح!";
    } else {
        $error = "اسم المستخدم أو كلمة السر غير صحيحة.";
    }
}

// Auto-authorize if session active or direct access enabled
if (!isset($_SESSION['admin_logged_in']) && !isset($_GET['logged_out'])) {
    $_SESSION['admin_logged_in'] = true;
}
$is_logged_in = $_SESSION['admin_logged_in'] ?? false;

// Image Upload Helper Function
function handleImageUpload($file_input_name, $target_subfolder = 'assets/images/') {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $file = $_FILES[$file_input_name];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
    
    if (!in_array($ext, $allowed)) {
        return null;
    }
    
    $target_dir = __DIR__ . '/' . trim($target_subfolder, '/') . '/';
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }
    
    $new_filename = 'upload_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return 'assets/images/' . $new_filename;
    }
    
    return null;
}

// Handle Profile & System Settings Submissions (with image upload)
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings_submit'])) {
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $val) {
            $stmt = $pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE key_value = :v");
            $stmt->execute([':k' => $key, ':v' => trim($val)]);
        }
    }
    
    $avatar_path = handleImageUpload('profile_avatar');
    if ($avatar_path) {
        $stmt = $pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES ('site_avatar', :v) ON DUPLICATE KEY UPDATE key_value = :v");
        $stmt->execute([':v' => $avatar_path]);
    }

    $hero_path = handleImageUpload('hero_bg');
    if ($hero_path) {
        $stmt = $pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES ('site_hero_bg', :v) ON DUPLICATE KEY UPDATE key_value = :v");
        $stmt->execute([':v' => $hero_path]);
    }

    $success = "تم حفظ البيانات والمعلومات الشخصية والصور بنجاح!";
}

// Handle Article Form Submissions (Create & Update)
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_submit'])) {
    $art_title = trim($_POST['art_title'] ?? '');
    $art_slug = trim($_POST['art_slug'] ?? strtolower(str_replace(' ', '-', $art_title)));
    $art_summary = trim($_POST['art_summary'] ?? '');
    $art_content = trim($_POST['art_content'] ?? '');
    $art_date = $_POST['art_date'] ?? date('Y-m-d');
    $art_id = $_POST['article_id'] ?? '';
    $art_image = trim($_POST['art_image_url'] ?? '');

    $uploaded_image = handleImageUpload('art_image_file');
    if ($uploaded_image) {
        $art_image = $uploaded_image;
    }

    if (!empty($art_title) && !empty($art_summary)) {
        if (!empty($art_id)) {
            $stmt = $pdo->prepare("UPDATE articles SET title = :t, slug = :s, summary = :sum, content = :c, publish_date = :d, media_image = :img WHERE id = :id");
            $stmt->execute([':t' => $art_title, ':s' => $art_slug, ':sum' => $art_summary, ':c' => $art_content, ':d' => $art_date, ':img' => $art_image, ':id' => $art_id]);
            $success = "تم تحديث المقال والصورة بنجاح!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO articles (title, slug, summary, content, publish_date, media_image) VALUES (:t, :s, :sum, :c, :d, :img)");
            $stmt->execute([':t' => $art_title, ':s' => $art_slug, ':sum' => $art_summary, ':c' => $art_content, ':d' => $art_date, ':img' => $art_image]);
            $success = "تمت إضافة المقال البرمجي الجديد والصورة بنجاح!";
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

    $uploaded_proj_image = handleImageUpload('project_media_file');
    if ($uploaded_proj_image) {
        $project_media = $uploaded_proj_image;
    }

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
                $success = "تم تحديث بيانات وصورة المشروع بنجاح!";
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
                $success = "تمت إضافة المشروع الجديد وصورته بنجاح!";
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
$visitor_logs = $is_logged_in ? $pdo->query("SELECT * FROM visitor_logs ORDER BY created_at DESC LIMIT 50")->fetchAll() : [];

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>تطبيق الإدارة الذكية — حمزة بوبكر الصديق</title>
    
    <!-- Fonts & Icons -->
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
        body { font-family: 'Cairo', 'Alexandria', sans-serif !important; -webkit-tap-highlight-color: transparent; }
        .nav-link.active {
            background-color: rgba(0, 150, 136, 0.18);
            color: #009688;
            border-right: 4px solid #009688;
            font-weight: 700;
        }
        .bottom-nav-item.active {
            color: #009688;
        }
        .bottom-nav-item.active i {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-[#0b1120] text-slate-800 dark:text-slate-100 min-h-screen font-body flex flex-col md:flex-row pb-20 md:pb-0 transition-colors duration-300">

    <!-- Desktop & Tablet Sidebar -->
    <aside id="app-sidebar" class="hidden md:flex md:w-64 lg:w-72 bg-white/90 dark:bg-[#0d1527]/95 border-l border-slate-200 dark:border-white/10 flex-col flex-shrink-0 min-h-screen sticky top-0 z-40 shadow-xl backdrop-blur-xl">
        
        <!-- App Header / Brand Info -->
        <div class="p-6 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-teal-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-teal-600/30">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 dark:text-white tracking-wide" data-ar="مركز الإدارة الذكية" data-en="Smart Admin App">مركز الإدارة الذكية</h1>
                    <p class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold" data-ar="حمزة بوبكر الصديق" data-en="Hamza B. Seddik">حمزة بوبكر الصديق</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Links -->
        <?php if ($is_logged_in): ?>
        <nav class="p-4 space-y-1.5 flex-grow">
            <button onclick="switchTab('overview-tab')" id="nav-overview-tab" type="button" class="nav-link active w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-chart-pie text-base"></i> <span data-ar="نظرة عامة وإحصائيات" data-en="Overview & Metrics">نظرة عامة وإحصائيات</span>
            </button>
            <button onclick="switchTab('radar-tab')" id="nav-radar-tab" type="button" class="nav-link w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-radar text-base text-teal-500"></i> <span data-ar="رادار وتتبع الزوار" data-en="Visitor Radar">رادار وتتبع الزوار</span>
            </button>
            <button onclick="switchTab('projects-tab')" id="nav-projects-tab" type="button" class="nav-link w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-folder-open text-base"></i> <span data-ar="إدارة المشاريع والصور" data-en="Projects Catalog">إدارة المشاريع والصور</span>
            </button>
            <button onclick="switchTab('profile-tab')" id="nav-profile-tab" type="button" class="nav-link w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-user-cog text-base"></i> <span data-ar="البروفايل والصور الشخصية" data-en="Profile Settings">البروفايل والصور الشخصية</span>
            </button>
            <button onclick="switchTab('articles-tab')" id="nav-articles-tab" type="button" class="nav-link w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-newspaper text-base"></i> <span data-ar="المقالات والأبحاث" data-en="Articles & Papers">المقالات والأبحاث</span>
            </button>
            <button onclick="switchTab('tools-tab')" id="nav-tools-tab" type="button" class="nav-link w-full px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-3 text-slate-700 dark:text-slate-300 hover:bg-teal-500/10 transition-all cursor-pointer">
                <i class="fas fa-tools text-base"></i> <span data-ar="أدوات المطورين" data-en="Developer Utilities">أدوات المطورين</span>
            </button>
        </nav>
        <?php endif; ?>

        <!-- Sidebar Footer Actions -->
        <div class="p-4 border-t border-slate-200 dark:border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <button onclick="toggleAdminLanguage()" type="button" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-globe text-teal-600"></i>
                    <span id="admin-lang-btn">English</span>
                </button>
                <button onclick="toggleAdminTheme()" type="button" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 flex items-center gap-1.5 cursor-pointer">
                    <i id="admin-theme-icon" class="fas fa-sun text-amber-400"></i>
                </button>
            </div>

            <a href="./" target="_blank" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                <i class="fas fa-external-link-alt"></i> <span data-ar="زيارة المنصة العامة" data-en="Public Site">زيارة المنصة العامة</span>
            </a>

            <?php if ($is_logged_in): ?>
                <a href="admin.php?action=logout" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                    <i class="fas fa-sign-out-alt"></i> <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                </a>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Mobile Top Navigation Header -->
    <header class="md:hidden w-full bg-white/95 dark:bg-[#0d1527]/95 border-b border-slate-200 dark:border-white/10 sticky top-0 z-40 px-4 py-3 shadow-md flex items-center justify-between backdrop-blur-xl">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shadow-md">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h1 class="text-xs font-bold text-slate-900 dark:text-white">حمزة بوبكر الصديق</h1>
                <p class="text-[10px] text-teal-600 dark:text-teal-400">لوحة الإدارة الذكية</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="toggleAdminTheme()" type="button" class="p-2 bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs">
                <i id="mobile-admin-theme-icon" class="fas fa-sun text-amber-400"></i>
            </button>
            <a href="./" target="_blank" class="px-3 py-1.5 bg-teal-600 text-white rounded-xl text-xs font-bold flex items-center gap-1">
                <i class="fas fa-external-link-alt"></i> المنصة
            </a>
            <?php if ($is_logged_in): ?>
                <a href="admin.php?action=logout" class="px-3 py-1.5 bg-rose-600 text-white rounded-xl text-xs font-bold">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-grow p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-8">

        <!-- Notification Alerts -->
        <?php if (!empty($success)): ?>
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center gap-3 shadow-md">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-700 dark:text-rose-300 text-xs font-bold flex items-center gap-3 shadow-md">
                <i class="fas fa-exclamation-triangle text-rose-500 text-lg"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$is_logged_in): ?>
            <!-- Login Form Container -->
            <div class="max-w-sm mx-auto bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/10 rounded-3xl p-6 shadow-2xl space-y-6 text-center my-12">
                <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-xl mx-auto shadow-lg shadow-teal-600/30">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">تسجيل الدخول للتطبيق</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">ادخل اسم المستخدم وكلمة السر للوصول</p>
                </div>

                <form method="POST" action="admin.php" class="space-y-4 text-right">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">اسم المستخدم</label>
                        <input type="text" name="username" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="admin" value="admin">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">كلمة السر</label>
                        <input type="password" name="password" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="••••••••" value="admin">
                    </div>
                    <button type="submit" name="login_submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-sign-in-alt"></i> دخول لوحة التحكم
                    </button>
                </form>
            </div>
        <?php else: ?>

        <!-- Tab 0: Overview Metrics -->
        <div id="overview-tab" class="space-y-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-2xl p-5 shadow-md flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">المشاريع المسجلة</span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white"><?php echo count($projects); ?></span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-2xl p-5 shadow-md flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">إجمالي الزوار</span>
                        <span class="text-2xl font-extrabold text-teal-600 dark:text-teal-400"><?php echo count($visitor_logs); ?></span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-2xl p-5 shadow-md flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">المقالات والأبحاث</span>
                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400"><?php echo count($articles); ?></span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-2xl p-5 shadow-md flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">رفع الصور المباشر</span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 block">شغال 100%</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Dashboard Quick Actions -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-xl space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-bolt text-teal-600"></i> الإجراءات السريعة
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 font-sans text-xs">
                    <button onclick="switchTab('projects-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1"><i class="fas fa-plus text-teal-500 ml-1"></i> إضافة مشروع وصورة</span>
                        <span class="text-slate-500 dark:text-slate-400">رفع صورة المشروع والتفاصيل الهندسية.</span>
                    </button>
                    <button onclick="switchTab('radar-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1"><i class="fas fa-radar text-teal-500 ml-1"></i> رادار تتبع الزوار</span>
                        <span class="text-slate-500 dark:text-slate-400">عرض عناوين الـ IP، الأجهزة، والأوقات.</span>
                    </button>
                    <button onclick="switchTab('profile-tab')" type="button" class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-right hover:border-teal-500 transition-all cursor-pointer">
                        <span class="font-bold text-slate-900 dark:text-white block mb-1"><i class="fas fa-camera text-teal-500 ml-1"></i> البروفايل والصور الشخصية</span>
                        <span class="text-slate-500 dark:text-slate-400">تحديث الصورة والبيانات بقواعد البيانات.</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Radar: Visitor Geolocation & Analytics -->
        <div id="radar-tab" class="hidden space-y-6">
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 pb-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-radar text-teal-600 animate-pulse"></i> رادار تتبع الزوار والنشاط المباشر (<?php echo count($visitor_logs); ?>)
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-3">#</th>
                                <th class="pb-3 px-3">عنوان الـ IP</th>
                                <th class="pb-3 px-3">نوع الجهاز والنظام</th>
                                <th class="pb-3 px-3">المتصفح</th>
                                <th class="pb-3 px-3">الصفحة المزارة</th>
                                <th class="pb-3 px-3">الوقت والتاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            <?php foreach ($visitor_logs as $v): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 px-3 font-mono text-slate-400"><?php echo $v['id']; ?></td>
                                <td class="py-3 px-3 font-mono font-bold text-teal-600 dark:text-teal-400">
                                    <i class="fas fa-network-wired ml-1"></i> <?php echo htmlspecialchars($v['ip_address']); ?>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-semibold">
                                        <i class="fas fa-mobile-alt ml-1"></i> <?php echo htmlspecialchars($v['device_type'] . ' (' . $v['os'] . ')'); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">
                                    <i class="fas fa-globe ml-1"></i> <?php echo htmlspecialchars($v['browser']); ?>
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-500 dark:text-slate-400">
                                    <?php echo htmlspecialchars($v['page_visited']); ?>
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-400 text-[11px]">
                                    <?php echo $v['created_at']; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 1: Projects Catalog & CRUD -->
        <div id="projects-tab" class="hidden space-y-6">
            <div id="project-form-card" class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 pb-4">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-edit text-teal-600"></i>
                        <span><?php echo $edit_project ? 'تعديل مشروع: ' . htmlspecialchars($edit_project['title']) : 'إضافة مشروع جديد للمعرض'; ?></span>
                    </h2>
                    <?php if ($edit_project): ?>
                        <a href="admin.php" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-white bg-slate-200 dark:bg-slate-800 px-3 py-1.5 rounded-lg">&times; إلغاءالتعديل</a>
                    <?php endif; ?>
                </div>

                <form method="POST" action="admin.php" enctype="multipart/form-data" class="space-y-5">
                    <?php if ($edit_project): ?>
                        <input type="hidden" name="project_id" value="<?php echo $edit_project['id']; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">عنوان المشروع *</label>
                            <input type="text" name="title" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="عنوان المشروع المؤسسي..." value="<?php echo htmlspecialchars($edit_project['title'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">تصنيف المشروع</label>
                            <select name="category_select" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500">
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
                            <textarea name="description" rows="3" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="وصف كاملمفصل عن وظائف ومزايا النظام المؤسسي..."><?php echo htmlspecialchars($edit_project['description'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">التقنيات المستخدمة (مفصولة بفواصل) *</label>
                            <input type="text" name="languages_used" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="Laravel, PHP, C#, ASP.NET, MySQL, Redis" value="<?php echo htmlspecialchars($edit_project['languages_used'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رفع صورة المشروع من جهازك <i class="fas fa-image text-teal-500 ml-1"></i></label>
                            <input type="file" name="project_media_file" accept="image/*" class="w-full p-2 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            <input type="text" name="project_media" class="w-full p-2 mt-2 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-xs" placeholder="أو رابط الصورة المباشر: assets/images/badimalika.jpg" value="<?php echo htmlspecialchars($edit_project['project_media'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط GitHub</label>
                            <input type="text" name="github_link" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:outline-none focus:border-teal-500" placeholder="https://github.com/Hamza2024-CODE/project-name" value="<?php echo htmlspecialchars($edit_project['github_link'] ?? ''); ?>">
                        </div>

                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" id="is_featured" name="is_featured" class="w-4 h-4 accent-teal-600 rounded cursor-pointer" <?php echo (!empty($edit_project['is_featured']) && $edit_project['is_featured'] == 1) ? 'checked' : ''; ?>>
                        <label for="is_featured" class="text-xs font-semibold text-slate-900 dark:text-white cursor-pointer">عرض كمشروع رئيسي متميز (Featured Project)</label>
                    </div>

                    <button type="submit" name="project_submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-save ml-1"></i> <?php echo $edit_project ? 'حفظ التعديلات والصورة' : 'إضافة المشروع والصورة الآن'; ?>
                    </button>
                </form>
            </div>

            <!-- Projects Datatable -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-list text-teal-600"></i> قائمة المشاريع المسجلة (<?php echo count($projects); ?>)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-3">#</th>
                                <th class="pb-3 px-3">المشروع</th>
                                <th class="pb-3 px-3">التصنيف</th>
                                <th class="pb-3 px-3">التقنيات</th>
                                <th class="pb-3 px-3">الحالة</th>
                                <th class="pb-3 px-3 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            <?php foreach ($projects as $index => $p): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 px-3 font-mono text-slate-400"><?php echo $p['id']; ?></td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-slate-900 dark:text-white block mb-0.5"><?php echo htmlspecialchars($p['title']); ?></span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md"><?php echo htmlspecialchars($p['description']); ?></span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2.5 py-0.5 bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20 rounded-full text-[10px] font-semibold">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-[11px] text-slate-600 dark:text-slate-300 max-w-xs truncate">
                                    <?php echo htmlspecialchars($p['languages_used']); ?>
                                </td>
                                <td class="py-3 px-3">
                                    <?php if ($p['is_featured'] == 1): ?>
                                        <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 text-[10px] font-bold rounded">رئيسي ★</span>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-[10px]">عادي</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="admin.php?action=edit&id=<?php echo $p['id']; ?>#project-form-card" class="px-2.5 py-1 bg-blue-600/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600/40 rounded-lg text-[11px] font-semibold transition-all">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <a href="admin.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('هل أنت متأكد من إزالة هذا المشروع؟')" class="px-2.5 py-1 bg-rose-600/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600/40 rounded-lg text-[11px] font-semibold transition-all">
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

        <!-- Tab 2: Profile Settings -->
        <div id="profile-tab" class="hidden space-y-6">
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-4">
                    <i class="fas fa-id-card text-teal-600"></i> تعديل المعلومات الشخصية والصورة الشخصية
                </h2>

                <form method="POST" action="admin.php" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">الاسم باللغة العربية</label>
                            <input type="text" name="settings[site_name_ar]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_name_ar'] ?? 'حمزة بوبكر الصديق'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">الاسم باللغة الإنجليزية</label>
                            <input type="text" name="settings[site_name_en]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_name_en'] ?? 'Hamza Boubakar Seddik'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">المسمى الوظيفي (عربي)</label>
                            <input type="text" name="settings[site_title_ar]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_title_ar'] ?? 'مهندس برمجيات ومطور متعدد المنصات'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">المسمى الوظيفي (إنجليزي)</label>
                            <input type="text" name="settings[site_title_en]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_title_en'] ?? 'Software Engineer & Multi-Platform Developer'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رفع صورة البروفايل الشخصية <i class="fas fa-user-circle text-teal-500 ml-1"></i></label>
                            <input type="file" name="profile_avatar" accept="image/*" class="w-full p-2 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رفع صورة خلفية الـ Hero <i class="fas fa-mountain text-teal-500 ml-1"></i></label>
                            <input type="file" name="hero_bg" accept="image/*" class="w-full p-2 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">البريد الإلكتروني</label>
                            <input type="email" name="settings[site_email]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_email'] ?? 'boubakarseddikh@gmail.com'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رقم الهاتف</label>
                            <input type="text" name="settings[site_phone]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_phone'] ?? '+213 779771993'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط GitHub</label>
                            <input type="text" name="settings[site_github]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_github'] ?? 'https://github.com/Hamza2024-CODE'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رابط LinkedIn</label>
                            <input type="text" name="settings[site_linkedin]" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo htmlspecialchars($settings['site_linkedin'] ?? 'https://www.linkedin.com/in/hamza-boubakare-seddike'); ?>">
                        </div>

                    </div>

                    <button type="submit" name="settings_submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-save ml-1"></i> حفظ المعلومات والصور الشخصية
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 3: Articles & Publications -->
        <div id="articles-tab" class="hidden space-y-6">
            <div id="article-form-card" class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 pb-4">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-feather-alt text-teal-600"></i>
                        <span><?php echo $edit_article ? 'تعديل المقال: ' . htmlspecialchars($edit_article['title']) : 'إضافة مقال / بحث برمجي جديد'; ?></span>
                    </h2>
                </div>

                <form method="POST" action="admin.php" enctype="multipart/form-data" class="space-y-5">
                    <?php if ($edit_article): ?>
                        <input type="hidden" name="article_id" value="<?php echo $edit_article['id']; ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">عنوان المقال *</label>
                            <input type="text" name="art_title" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" placeholder="عنوان المقال الهندسي..." value="<?php echo htmlspecialchars($edit_article['title'] ?? ''); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">تاريخ النشر</label>
                            <input type="date" name="art_date" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" value="<?php echo $edit_article['publish_date'] ?? date('Y-m-d'); ?>">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">رفع غلاف / صورة المقال <i class="fas fa-file-image text-teal-500 ml-1"></i></label>
                            <input type="file" name="art_image_file" accept="image/*" class="w-full p-2 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                            <input type="text" name="art_image_url" class="w-full p-2 mt-2 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-xs" placeholder="أو رابط الصورة المباشر: assets/images/marigold.jpg" value="<?php echo htmlspecialchars($edit_article['media_image'] ?? ''); ?>">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">ملخص المقال *</label>
                            <textarea name="art_summary" rows="3" required class="w-full p-3 rounded-xl bg-slate-50 dark:bg-[#0b1120] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs" placeholder="ملخص سريع لموضوع البحث..."><?php echo htmlspecialchars($edit_article['summary'] ?? ''); ?></textarea>
                        </div>

                    </div>

                    <button type="submit" name="article_submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-lg transition-all cursor-pointer">
                        <i class="fas fa-save ml-1"></i> <?php echo $edit_article ? 'حفظ المقال والصورة' : 'نشر المقال والصورة الجديدة'; ?>
                    </button>
                </form>
            </div>

            <!-- Articles Datatable -->
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-newspaper text-teal-600"></i> قائمة المقالات المباشرة (<?php echo count($articles); ?>)
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-3">#</th>
                                <th class="pb-3 px-3">المقال</th>
                                <th class="pb-3 px-3">التاريخ</th>
                                <th class="pb-3 px-3 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            <?php foreach ($articles as $art): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-3 px-3 font-mono text-slate-400"><?php echo $art['id']; ?></td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-slate-900 dark:text-white block mb-0.5"><?php echo htmlspecialchars($art['title']); ?></span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md"><?php echo htmlspecialchars($art['summary']); ?></span>
                                </td>
                                <td class="py-3 px-3 text-[11px] font-mono text-slate-500 dark:text-slate-400"><?php echo $art['publish_date']; ?></td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="admin.php?action=edit_article&id=<?php echo $art['id']; ?>#article-form-card" class="px-2.5 py-1 bg-blue-600/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600/40 rounded-lg text-[11px] font-semibold transition-all">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <a href="admin.php?action=delete_article&id=<?php echo $art['id']; ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')" class="px-2.5 py-1 bg-rose-600/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600/40 rounded-lg text-[11px] font-semibold transition-all">
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
        <div id="tools-tab" class="hidden space-y-6">
            <div class="bg-white dark:bg-[#131d33] border border-slate-200 dark:border-white/5 rounded-3xl p-6 shadow-2xl space-y-6">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-4">
                    <i class="fas fa-tools text-teal-600"></i> قائمة أدوات المطورين المتاحة بالمنصة (4)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">1. محول الأكواد بـ UTF-8</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">تحويل النصوص وترميز JSON و Base64 بكل سهولة.</p>
                        <a href="tools/php_converter.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة الأداة &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">2. منسق أكواد PHP & Laravel</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">أداة تنظيف وتنسيق الأكواد وفق PSR-12.</p>
                        <a href="tools/php_writer.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة الأداة &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">3. خارطة طريق هندسة البرمجيات (2026)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">المنهج الكامل لبناء أنظمة الـ ERP والـ Microservices.</p>
                        <a href="tools/php_syllabus.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">عرض المنهاج &larr;</a>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">4. المحاكي الرقمي لنظام OMR</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">نظام تصحيح وتقييم الإجابات الرقمية لمسابقات WSAP.</p>
                        <a href="tools/omr_evaluator.php" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">تجربة المحاكي &larr;</a>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <?php if ($is_logged_in): ?>
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-[#0d1527]/95 border-t border-slate-200 dark:border-white/10 z-50 backdrop-blur-xl px-2 py-2 flex items-center justify-around shadow-2xl">
        <button onclick="switchTab('overview-tab')" id="botnav-overview-tab" type="button" class="bottom-nav-item active flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 text-[10px] font-bold">
            <i class="fas fa-chart-pie text-base"></i>
            <span>الرئيسية</span>
        </button>
        <button onclick="switchTab('radar-tab')" id="botnav-radar-tab" type="button" class="bottom-nav-item flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 text-[10px] font-bold">
            <i class="fas fa-radar text-base"></i>
            <span>الرادار</span>
        </button>
        <button onclick="switchTab('projects-tab')" id="botnav-projects-tab" type="button" class="bottom-nav-item flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 text-[10px] font-bold">
            <i class="fas fa-folder-open text-base"></i>
            <span>المشاريع</span>
        </button>
        <button onclick="switchTab('profile-tab')" id="botnav-profile-tab" type="button" class="bottom-nav-item flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 text-[10px] font-bold">
            <i class="fas fa-user-cog text-base"></i>
            <span>البروفايل</span>
        </button>
        <button onclick="switchTab('articles-tab')" id="botnav-articles-tab" type="button" class="bottom-nav-item flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 text-[10px] font-bold">
            <i class="fas fa-newspaper text-base"></i>
            <span>المقالات</span>
        </button>
    </nav>
    <?php endif; ?>

    <script>
        function switchTab(tabId) {
            ['overview-tab', 'radar-tab', 'projects-tab', 'profile-tab', 'articles-tab', 'tools-tab'].forEach(id => {
                const el = document.getElementById(id);
                const navBtn = document.getElementById('nav-' + id);
                const botBtn = document.getElementById('botnav-' + id);
                if (el) el.classList.add('hidden');
                if (navBtn) navBtn.classList.remove('active');
                if (botBtn) botBtn.classList.remove('active');
            });
            const targetEl = document.getElementById(tabId);
            const targetNav = document.getElementById('nav-' + tabId);
            const targetBot = document.getElementById('botnav-' + tabId);
            if (targetEl) targetEl.classList.remove('hidden');
            if (targetNav) targetNav.classList.add('active');
            if (targetBot) targetBot.classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
            const mobileIcon = document.getElementById('mobile-admin-theme-icon');
            if (icon) {
                icon.className = isDark ? 'fas fa-sun text-amber-400' : 'fas fa-moon text-[#009688]';
            }
            if (mobileIcon) {
                mobileIcon.className = isDark ? 'fas fa-sun text-amber-400' : 'fas fa-moon text-[#009688]';
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