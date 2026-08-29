<?php
$pageTitle = "المحاكي الرقمي لنظام OMR والتقييم — حمزة بوبكر الصديق";
$path_prefix = '../';
include '../../src/includes/header.php';

$score = null;
$totalQuestions = 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answersKey = ['q1' => 'A', 'q2' => 'C', 'q3' => 'B', 'q4' => 'D', 'q5' => 'A'];
    $userAnswers = $_POST['answers'] ?? [];
    
    $correctCount = 0;
    foreach ($answersKey as $q => $correctChoice) {
        if (isset($userAnswers[$q]) && $userAnswers[$q] === $correctChoice) {
            $correctCount++;
        }
    }
    $score = ($correctCount / $totalQuestions) * 100;
}
?>

<div class="w-full bg-white dark:bg-[#0d1527] text-slate-900 dark:text-white box-border transition-colors duration-300 min-h-screen pt-32 pb-24 px-6 sm:px-12 md:px-16 lg:px-20">
    <div class="max-w-[1000px] mx-auto text-left rtl:text-right">
        <a href="tools/" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-4 inline-block">&larr; العودة لمركز الأدوات</a>
        <h1 class="font-sans text-3xl font-bold text-slate-900 dark:text-white mb-2">المحاكي الرقمي لنظام OMR والتقييم</h1>
        <p class="font-body text-sm text-slate-600 dark:text-slate-400 mb-8">نظام التقييم وتصحيح أوراق الإجابة الرقمية المصمم لمنصة مسابقات التكوين المهني WSAP ونظام التوجيه.</p>

        <form method="POST" class="space-y-6">
            <div class="space-y-4">
                <?php
                $questions = [
                    'q1' => '1. ما هي ميزة PHP 8 التي تسمح بتعريف الخصائص مباشرة داخل الـ Constructor؟',
                    'q2' => '2. ما هو نوع العلاقة المستخدمة عندما يملك المستخدم بروفايل واحد فقط في Laravel Eloquent؟',
                    'q3' => '3. ما هي طريقة الـ HTTP المناسبة للتحديث الجزئي للموارد في تصميم واجهات REST API؟',
                    'q4' => '4. ما هي هيكلية الفهرسة الأكثر استخداماً لمفاتيح الجدول الأساسية Primary Key في MySQL InnoDB؟',
                    'q5' => '5. ما هو الهيدر المستخدم لتمرير رموز الـ JWT في عملية المصادقة الرقمية للـ API؟'
                ];
                $choices = [
                    'q1' => ['A' => 'Constructor Property Promotion', 'B' => 'Attributes', 'C' => 'Named Arguments', 'D' => 'Match Expressions'],
                    'q2' => ['A' => 'hasMany', 'B' => 'belongsToMany', 'C' => 'hasOne', 'D' => 'morphTo'],
                    'q3' => ['A' => 'GET', 'B' => 'PATCH', 'C' => 'DELETE', 'D' => 'HEAD'],
                    'q4' => ['A' => 'Hash Index', 'B' => 'R-Tree', 'C' => 'Spatial Index', 'D' => 'B-Tree'],
                    'q5' => ['A' => 'Authorization: Bearer <token>', 'B' => 'X-API-Key', 'C' => 'Content-Type', 'D' => 'Accept: json']
                ];
                foreach ($questions as $qKey => $qText):
                ?>
                <div class="p-5 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-2xl">
                    <p class="font-bold text-sm text-slate-900 dark:text-white mb-3"><?php echo htmlspecialchars($qText); ?></p>
                    <div class="flex flex-wrap gap-4 font-body text-xs">
                        <?php foreach ($choices[$qKey] as $optKey => $optLabel): ?>
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-900/60 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-teal-500">
                            <input type="radio" name="answers[<?php echo $qKey; ?>]" value="<?php echo $optKey; ?>" class="text-teal-600 focus:ring-teal-500">
                            <span><strong><?php echo $optKey; ?>:</strong> <?php echo htmlspecialchars($optLabel); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md">تصحيح ورقة الإجابة الآن &larr;</button>
        </form>

        <?php if ($score !== null): ?>
        <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl text-center">
            <h3 class="text-xs font-bold uppercase tracking-wider mb-2 text-teal-600 dark:text-teal-400">نتيجة التقييم الرقمي</h3>
            <div class="text-4xl font-bold text-slate-900 dark:text-white mb-2"><?php echo $score; ?>%</div>
            <p class="text-xs text-slate-600 dark:text-slate-400">النتيجة محسوبة تلقائياً بواسطة محرك OMR الرقمي.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../src/includes/footer.php'; ?>
