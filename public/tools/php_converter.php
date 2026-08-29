<?php
$pageTitle = "محول الأكواد وترميز UTF-8 بـ PHP — حمزة بوبكر الصديق";
$path_prefix = '../';
include '../../src/includes/header.php';

$outputResult = "";
$inputText = $_POST['input_text'] ?? "";
$action = $_POST['action'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($inputText)) {
    if ($action === 'encode_utf8') {
        $outputResult = bin2hex($inputText);
    } elseif ($action === 'html_entities') {
        $outputResult = htmlspecialchars($inputText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    } elseif ($action === 'json_encode') {
        $outputResult = json_encode(['data' => $inputText], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } elseif ($action === 'base64') {
        $outputResult = base64_encode($inputText);
    }
}
?>

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300 min-h-screen pt-32 pb-24 px-6 sm:px-12 md:px-16 lg:px-20">
    <div class="max-w-[1000px] mx-auto text-left rtl:text-right">
        <a href="tools/" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-4 inline-block">&larr; العودة لمركز الأدوات</a>
        <h1 class="font-sans text-3xl font-bold text-slate-900 dark:text-white mb-2">محول الأكواد وترميز UTF-8 بـ PHP</h1>
        <p class="font-body text-sm text-slate-600 dark:text-slate-400 mb-8">قم بتحويل النصوص، ترميزات UTF-8، الرموز البرمجية، وتهيئة مصفوفات JSON بكل سهولة.</p>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-700 dark:text-slate-300">النص / الكود المُراد تحويله</label>
                <textarea name="input_text" rows="6" class="w-full p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 font-mono text-sm focus:outline-none focus:border-teal-500" placeholder="اكتب أو الصق الكود أو النص هنا..."><?php echo htmlspecialchars($inputText); ?></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" name="action" value="encode_utf8" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md">تحويل إلى Hex UTF-8</button>
                <button type="submit" name="action" value="html_entities" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">تطهير HTML Entities</button>
                <button type="submit" name="action" value="json_encode" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">تنسيق كمصفوفة JSON</button>
                <button type="submit" name="action" value="base64" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md">ترميز Base64</button>
            </div>
        </form>

        <?php if (!empty($outputResult)): ?>
        <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl">
            <h3 class="text-xs font-bold uppercase tracking-wider mb-3 text-teal-600 dark:text-teal-400">نتيجة التحويل</h3>
            <pre class="w-full p-4 bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 font-mono text-sm text-slate-800 dark:text-slate-200 overflow-x-auto whitespace-pre-wrap"><?php echo htmlspecialchars($outputResult); ?></pre>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../src/includes/footer.php'; ?>
