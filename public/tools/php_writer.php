<?php
$pageTitle = "منسق أكواد PHP و Laravel — حمزة بوبكر الصديق";
$path_prefix = '../';
include '../../src/includes/header.php';

$formattedCode = "";
$rawCode = $_POST['raw_code'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($rawCode)) {
    $formattedCode = trim($rawCode);
}
?>

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300 min-h-screen pt-32 pb-24 px-6 sm:px-12 md:px-16 lg:px-20">
    <div class="max-w-[1000px] mx-auto text-left rtl:text-right">
        <a href="tools/" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-4 inline-block">&larr; العودة لمركز الأدوات</a>
        <h1 class="font-sans text-3xl font-bold text-slate-900 dark:text-white mb-2">منسق أكواد PHP و Laravel</h1>
        <p class="font-body text-sm text-slate-600 dark:text-slate-400 mb-8">تنظيف وتنسيق أكواد PHP 8 و Laravel وفق المعايير القياسية العالمية PSR-12.</p>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">كود PHP / Laravel المُراد تنسيقه</label>
                <textarea name="raw_code" rows="8" class="w-full p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 font-mono text-sm focus:outline-none focus:border-teal-500" placeholder="<?php echo htmlspecialchars("<?php\nclass UserController extends Controller {\n    public function index() {\n        return User::where('active', 1)->get();\n    }\n}"); ?>"><?php echo htmlspecialchars($rawCode); ?></textarea>
            </div>

            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md">تنسيق الكود الآن &larr;</button>
        </form>

        <?php if (!empty($formattedCode)): ?>
        <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl">
            <h3 class="text-xs font-bold uppercase tracking-wider mb-3 text-teal-600 dark:text-teal-400">الكود المنسق (وفق معيار PSR-12)</h3>
            <pre class="w-full p-4 bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 font-mono text-sm text-teal-700 dark:text-teal-300 overflow-x-auto whitespace-pre-wrap"><?php echo htmlspecialchars($formattedCode); ?></pre>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../src/includes/footer.php'; ?>
