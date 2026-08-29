<footer class="w-full relative min-h-[380px] h-[380px] bg-cover bg-center bg-no-repeat flex flex-col justify-end pb-8 text-white mt-auto overflow-hidden shadow-inner" style="background-image: linear-gradient(to bottom, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.1) 25%), url('public/assets/images/footer_landscape.jpg');">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs font-semibold tracking-wide border-t border-white/30 pt-6">

        <nav class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-white drop-shadow-md">
            <a href="index.php" class="hover:text-teal-300 transition-colors duration-200">Home</a>
            <span class="text-white/60 select-none">/</span>
            <a href="articles/articles.php" class="hover:text-teal-300 transition-colors duration-200">Blog</a>
            <span class="text-white/60 select-none">/</span>
            <a href="https://github.com/Hamza2024-CODE" target="_blank" rel="noopener noreferrer" class="hover:text-teal-300 transition-colors duration-200">GitHub</a>
            <span class="text-white/60 select-none">/</span>
            <a href="https://www.linkedin.com/in/hamza-boubakare-seddike" target="_blank" rel="noopener noreferrer" class="hover:text-teal-300 transition-colors duration-200">LinkedIn</a>
        </nav>

        <p class="text-center sm:text-right text-white/90 drop-shadow-md">
            &copy; <?php echo date("Y"); ?> Hamza Boubakar Seddik
        </p>

    </div>
</footer>

<?php
$path_prefix = isset($base_path) ? $base_path : '';
// If this is an AJAX/SPA request, skip rendering chat widgets and closing scripts
$is_spa = isset($_SERVER['HTTP_X_SPA_REQUEST']) && $_SERVER['HTTP_X_SPA_REQUEST'] === 'true';
?>

</div>
</section>
</main>
<?php
if ($is_spa) {
    return;
}
?>

<div id="chat-trigger-container"
    class="fixed bottom-5 right-4 sm:right-5 z-[99999] font-sans antialiased transition-all duration-200">
    <button id="chat-toggle-btn" onclick="toggleChatWindow()"
        class="flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-brandNeutral text-brandNeutral dark:text-white shadow-[0_0_15px_rgba(0,0,0,0.1)] dark:shadow-[0_0_20px_rgba(0,150,136,0.4)] hover:shadow-[0_0_25px_rgba(0,150,136,0.6)] transition-all duration-300 transform hover:scale-105 focus:outline-none">

        <span id="toggle-icon-open" class="block">
            <img src="<?php echo $path_prefix; ?>public/assets/icons/ai_assistant_icon.svg" alt="AI Assistant"
                class="w-8 h-8 block dark:hidden" />
            <img src="<?php echo $path_prefix; ?>public/assets/icons/ai_assistant_icon.svg" alt="AI Assistant"
                class="w-8 h-8 hidden dark:block invert" />
        </span>

        <span id="toggle-icon-close" class="hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </span>
    </button>
</div>

<div id="ai-chat-window"
    class="hidden fixed bottom-0 left-0 right-0 sm:left-auto sm:right-4 sm:bottom-24 w-full sm:w-[380px] h-[60vh] sm:h-[520px] bg-white/40 dark:bg-slate-950/75 backdrop-blur-md border-t sm:border border-white/40 dark:border-slate-800/60 rounded-t-xl sm:rounded-xl flex flex-col overflow-hidden transition-all duration-300 transform scale-95 origin-bottom-right shadow-[0_8px_32px_0_rgba(15,23,42,0.08)] dark:shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] z-[99999]">

    <div
        class="px-5 py-4 border-b border-gray-200/50 dark:border-slate-800/60 flex items-center justify-between bg-white/20 dark:bg-slate-950/40 flex-shrink-0">
        <div class="flex items-center gap-2.5">
            <span class="relative flex h-2 w-2">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500 dark:bg-emerald-400"></span>
            </span>
            <span
                class="font-mono text-[11px] uppercase tracking-widest text-slate-600 dark:text-slate-400 font-semibold dark:font-normal">
                Hamza AI
            </span>
        </div>
        <button onclick="toggleChatWindow()"
            class="text-gray-400 hover:text-gray-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors focus:outline-none p-1"
            aria-label="Close Chat">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="chat-screen" class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-6 scroll-smooth bg-transparent">
        <div class="flex items-start gap-3 max-w-[92%]" data-role="assistant">
            <div
                class="w-7 h-7 rounded-lg bg-white/80 dark:bg-white border border-white dark:border-slate-200 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                <img src="<?php echo $path_prefix; ?>public/assets/icons/ai_profile.svg" alt="AI"
                    class="w-4 h-4 object-contain transition-all duration-300 dark:brightness-0 dark:opacity-80" />
            </div>
            <div class="space-y-1">
                <span
                    class="block text-[10px] uppercase font-mono tracking-wider text-slate-500 dark:text-slate-500 font-semibold dark:font-normal">
                    Hamza's AI
                </span>
                <div>
                    <div
                        class="text-slate-800 dark:text-slate-100 text-sm font-normal dark:font-light leading-relaxed bg-white/60 dark:bg-slate-900/40 px-3.5 py-2.5 rounded-lg border border-white/80 dark:border-slate-800/50 shadow-sm backdrop-blur-sm">
                        👋 Hello! I am Hamza Boubakar Seddik's AI Assistant. How can I help you explore his enterprise systems, projects, or technical skills today?
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="p-3 sm:p-4 bg-white/60 dark:bg-slate-950/80 border-t border-gray-200/50 dark:border-slate-800/60 flex items-center gap-2.5 backdrop-blur-sm flex-shrink-0">
        <input type="text" id="chat-input" placeholder="Ask anything about me...."
            class="flex-1 bg-white/80 dark:bg-slate-900/60 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 font-normal dark:font-light text-sm rounded-lg px-4 py-2.5 border border-gray-200/60 dark:border-slate-800/80 focus:outline-none focus:border-brandPrimary dark:focus:border-slate-700 transition-colors shadow-inner min-w-0">

        <button onclick="talkToAI()"
            class="bg-slate-900 hover:bg-black dark:bg-slate-900 dark:hover:bg-slate-800 text-white dark:text-emerald-400 dark:hover:text-emerald-300 p-2.5 rounded-lg border border-slate-800 dark:border-slate-800 transition-all focus:outline-none flex items-center justify-center flex-shrink-0 shadow-md"
            aria-label="Send Message">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4 transform -rotate-45 -translate-x-0.5 translate-y-0.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
        </button>
    </div>
</div>

<script>
    (function () {
        const isMobile = window.innerWidth < 640;
        const translateXValue = isMobile ? "0px" : "40px";
        const translateYValue = isMobile ? "180px" : "260px";

        const appleStyles = `
        @keyframes appleClose {
            0% { transform: scale(1) translate(0, 0); opacity: 1; filter: blur(0px); }
            30% { transform: scaleX(0.85) scaleY(0.9) translate(0, 5px); opacity: 0.9; }
            100% { transform: scaleX(0.05) scaleY(0.1) translate(${translateXValue}, ${translateYValue}); opacity: 0; filter: blur(4px); }
        }
        @keyframes appleOpen {
            0% { transform: scaleX(0.05) scaleY(0.1) translate(${translateXValue}, ${translateYValue}); opacity: 0; filter: blur(4px); }
            60% { transform: scaleX(0.85) scaleY(0.95) translate(0, -5px); opacity: 0.9; }
            100% { transform: scale(1) translate(0, 0); opacity: 1; filter: blur(0px); }
        }
        .animate-apple-open { animation: appleOpen 380ms cubic-bezier(0.25, 1, 0.5, 1) forwards !important; }
        .animate-apple-close { animation: appleClose 340ms cubic-bezier(0.55, 0, 1, 0.45) forwards !important; }
    `;
        const styleSheet = document.createElement("style");
        styleSheet.textContent = appleStyles;
        document.head.appendChild(styleSheet);
    })();

    const screenEl = document.getElementById('chat-screen');

    // Visual Viewport Handling for Mobile Virtual Keyboards
    function adjustChatForVisualViewport() {
        if (!window.visualViewport || window.innerWidth >= 640) return;

        const chatWindow = document.getElementById('ai-chat-window');
        const triggerBtn = document.getElementById('chat-trigger-container');

        const viewportHeight = window.visualViewport.height;
        const windowHeight = window.innerHeight;
        const keyboardHeight = windowHeight - viewportHeight;

        if (keyboardHeight > 50) {
            // Keyboard is open on mobile devices
            if (chatWindow) {
                chatWindow.style.bottom = `${keyboardHeight}px`;
                chatWindow.style.height = `${viewportHeight * 0.6}px`; // dynamic scale constraint
            }
            if (triggerBtn) triggerBtn.style.bottom = `${keyboardHeight + 20}px`;
        } else {
            // Keyboard is closed
            if (chatWindow) {
                chatWindow.style.bottom = '0px';
                chatWindow.style.height = '60vh';
            }
            if (triggerBtn) triggerBtn.style.bottom = '20px';
        }
    }

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', adjustChatForVisualViewport);
        window.visualViewport.addEventListener('scroll', adjustChatForVisualViewport);
    }

    function appendMessageRow(sender, text) {
        const isAI = sender === 'ai';
        const formattedText = isAI ? formatAIResponse(text) : text;

        const rowHTML = isAI ? `
        <div class="flex items-start gap-3 max-w-[92%] animate-fadeIn">
            <div class="w-7 h-7 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-xs flex-shrink-0 shadow-inner">🤖</div>
            <div class="space-y-1">
                <span class="block text-[10px] uppercase font-mono tracking-wider text-slate-500">Hem's AI</span>
                <div class="text-slate-100 text-sm font-light leading-relaxed bg-slate-900/40 px-3.5 py-2.5 rounded-lg border border-slate-800/50 backdrop-blur-sm">
                    ${formattedText}
                </div>
            </div>
        </div>` : `
        <div class="flex items-start gap-3 max-w-[92%] ml-auto flex-row-reverse animate-fadeIn">
            <div class="w-7 h-7 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-xs flex-shrink-0 shadow-inner">👤</div>
            <div class="space-y-1 text-right">
                <span class="block text-[10px] uppercase font-mono tracking-wider text-slate-500">You</span>
                <div class="text-emerald-300 text-sm font-light leading-relaxed bg-emerald-950/20 px-3.5 py-2.5 rounded-lg border border-emerald-900/30 text-left backdrop-blur-sm">
                    ${formattedText}
                </div>
            </div>
        </div>`;

        screenEl.innerHTML += rowHTML;
        screenEl.scrollTop = screenEl.scrollHeight;
    }

    function showLoadingState() {
        removeLoadingState();
        const skeletonHTML = `
        <div id="ai-loading-skeleton" class="flex items-start gap-3 max-w-[85%] animate-pulse">
            <div class="w-7 h-7 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-xs flex-shrink-0 opacity-60">🤖</div>
            <div class="space-y-2 w-full pt-1">
                <div class="h-2.5 bg-slate-800/80 rounded w-1/3"></div>
                <div class="space-y-1.5 bg-slate-900/40 p-3 rounded-lg border border-slate-800/30 w-full">
                    <div class="h-2 bg-slate-800 rounded w-full"></div>
                    <div class="h-2 bg-slate-800 rounded w-5/6"></div>
                    <div class="h-2 bg-slate-800 rounded w-2/3"></div>
                </div>
            </div>
        </div>`;
        screenEl.innerHTML += skeletonHTML;
        screenEl.scrollTop = screenEl.scrollHeight;
    }

    function removeLoadingState() {
        const skeleton = document.getElementById('ai-loading-skeleton');
        if (skeleton) skeleton.remove();
        const placeholder = document.getElementById('ai-loading-placeholder');
        if (placeholder) placeholder.remove();
    }

    if (window.performance && window.performance.getEntriesByType) {
        const navigationEntries = window.performance.getEntriesByType('navigation');
        if (navigationEntries.length > 0 && navigationEntries[0].type === 'reload') {
            sessionStorage.removeItem('portfolio_chat_history');
        }
    } else {
        if (performance.navigation && performance.navigation.type === performance.navigation.TYPE_RELOAD) {
            sessionStorage.removeItem('portfolio_chat_history');
        }
    }

    const STORAGE_KEY = 'portfolio_chat_history';

    document.addEventListener('DOMContentLoaded', () => {
        restoreChatHistory();
    });

    function toggleChatWindow() {
        const windowEl = document.getElementById('ai-chat-window');
        const iconOpen = document.getElementById('toggle-icon-open');
        const iconClose = document.getElementById('toggle-icon-close');

        if (!windowEl) return;

        const isHidden = windowEl.classList.contains('hidden');

        if (isHidden) {
            windowEl.classList.remove('animate-apple-close', 'hidden');
            windowEl.classList.add('flex', 'animate-apple-open');

            if (iconOpen) iconOpen.classList.add('hidden');
            if (iconClose) iconClose.classList.remove('hidden');

            const inputEl = document.getElementById('chat-input');
            if (inputEl) inputEl.focus();

            if (screenEl) screenEl.scrollTop = screenEl.scrollHeight;
            adjustChatForVisualViewport();
        } else {
            windowEl.classList.remove('animate-apple-open');
            windowEl.classList.add('animate-apple-close');

            if (iconOpen) iconOpen.classList.remove('hidden');
            if (iconClose) iconClose.classList.add('hidden');

            setTimeout(() => {
                if (windowEl.classList.contains('animate-apple-close')) {
                    windowEl.classList.add('hidden');
                    windowEl.classList.remove('flex', 'animate-apple-close');
                    // Reset styling overrides when closed
                    windowEl.style.bottom = '';
                    windowEl.style.height = '';
                }
            }, 340);
        }
    }

    function formatAIResponse(text) {
        let formatted = text;
        const linkedinRegex = /https?:\/\/(www\.)?linkedin\.com\/in\/([a-zA-Z0-9_-]+)\/?/gi;
        formatted = formatted.replace(linkedinRegex, (match, sub, username) => {
            return `<a href="${match}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-[#0a66c2]/10 hover:bg-[#0a66c2]/20 text-[#0a66c2] font-semibold px-2 py-0.5 rounded-md transition-colors font-sans text-xs">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                ${username}
            </a>`;
        });

        const githubRegex = /https?:\/\/(www\.)?github\.com\/([a-zA-Z0-9_-]+)\/?/gi;
        formatted = formatted.replace(githubRegex, (match, sub, username) => {
            return `<a href="${match}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-slate-800 text-white hover:bg-slate-900 font-semibold px-2 py-0.5 rounded-md transition-colors font-sans text-xs dark:bg-slate-700 dark:hover:bg-slate-600">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                ${username}
            </a>`;
        });

        const phoneRegex = /(\+977[- ]?\d{10}|\b98\d{8}\b)/g;
        formatted = formatted.replace(phoneRegex, (match) => {
            return `<a href="tel:${match.replace(/[- ]/g, '')}" class="text-brandPrimary hover:underline font-semibold font-mono">${match}</a>`;
        });

        const emailRegex = /([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})/g;
        formatted = formatted.replace(emailRegex, (match) => {
            return `<a href="mailto:${match}" class="text-brandPrimary hover:underline font-semibold">${match}</a>`;
        });

        return formatted;
    }

    function saveChatHistory() {
        if (screenEl) {
            sessionStorage.setItem(STORAGE_KEY, screenEl.innerHTML);
        }
    }

    function restoreChatHistory() {
        const savedData = sessionStorage.getItem(STORAGE_KEY);
        if (savedData && screenEl) {
            screenEl.innerHTML = savedData;
            screenEl.scrollTop = screenEl.scrollHeight;
        }
    }

    async function talkToAI() {
        const inputEl = document.getElementById('chat-input');
        const text = inputEl.value.trim();

        if (!text) return;

        screenEl.innerHTML += `
        <div class="flex items-start gap-3 max-w-[92%] ml-auto flex-row-reverse animate-fadeIn" data-role="user">
            <div class="w-7 h-7 rounded-lg bg-white border border-gray-200/80 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                <img src="<?php echo $path_prefix; ?>public/assets/icons/user_profile.svg" alt="User" class="w-4 h-4 object-contain transition-all duration-300 brightness-0 opacity-80" />
            </div>
            <div class="space-y-1 text-right">
                <span class="block text-[10px] uppercase font-mono tracking-wider text-slate-500">You</span>
                <div>
                    <div class="text-slate-800 dark:text-slate-100 text-sm font-normal dark:font-light leading-relaxed bg-white/60 dark:bg-slate-900/40 px-3.5 py-2.5 rounded-lg border border-white/80 dark:border-slate-800/50 text-left backdrop-blur-sm shadow-sm">
                        ${text}
                    </div>
                </div>
            </div>
        </div>`;

        inputEl.value = '';
        screenEl.scrollTop = screenEl.scrollHeight;
        saveChatHistory();

        const historyArray = [];
        const chatBubbles = screenEl.querySelectorAll('#chat-screen > [data-role]');

        chatBubbles.forEach(bubble => {
            const role = bubble.getAttribute('data-role');
            const textContainer = bubble.querySelector('.backdrop-blur-sm');
            let textContent = textContainer ? textContainer.textContent : bubble.textContent;
            textContent = textContent.replace("You", "").replace("Hem's AI", "").replace("Hamza's AI", "").trim();

            if (!textContent) return;

            historyArray.push({
                role: role === "user" ? "user" : "assistant",
                content: textContent
            });
        });

        const loading = document.createElement('div');
        loading.id = "ai-loading-placeholder";
        loading.className = "flex items-start gap-3 max-w-[85%] animate-pulse";
        loading.innerHTML = `
        <div class="w-7 h-7 rounded-lg bg-white/80 dark:bg-white border border-white dark:border-slate-200 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
            <img src="<?php echo $path_prefix; ?>public/assets/icons/ai_profile.svg" alt="AI" class="w-4 h-4 object-contain transition-all duration-300 dark:brightness-0 dark:opacity-80" />
        </div>
        <div class="space-y-2 w-full pt-1">
            <div class="h-2.5 bg-gray-200 dark:bg-slate-800 rounded w-1/3"></div>
            <div class="space-y-1.5 bg-white/40 dark:bg-slate-900/40 p-3 rounded-lg border border-white/80 dark:border-slate-800/30 w-full">
                <div class="h-2 bg-gray-200 dark:bg-slate-800 rounded w-full"></div>
                <div class="h-2 bg-gray-200 dark:bg-slate-800 rounded w-5/6"></div>
            </div>
        </div>`;
        screenEl.appendChild(loading);
        screenEl.scrollTop = screenEl.scrollHeight;

        try {
            const res = await fetch('<?php echo $path_prefix; ?>src/backend/ai-backend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: text,
                    history: historyArray
                })
            });

            if (!res.ok) throw new Error(`HTTP Status ${res.status}`);
            const data = await res.json();

            removeLoadingState();
            const parsedReply = formatAIResponse(data.reply);

            screenEl.innerHTML += `
            <div class="flex items-start gap-3 max-w-[92%] animate-fadeIn" data-role="assistant">
                <div class="w-7 h-7 rounded-lg bg-white/80 dark:bg-white border border-white dark:border-slate-200 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                    <img src="<?php echo $path_prefix; ?>public/assets/icons/ai_profile.svg" alt="AI" class="w-4 h-4 object-contain transition-all duration-300 dark:brightness-0 dark:opacity-80" />
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] uppercase font-mono tracking-wider text-slate-500 dark:text-slate-500 font-semibold dark:font-normal">Hamza's AI</span>
                    <div>
                        <div class="text-slate-800 dark:text-slate-100 text-sm font-normal dark:font-light leading-relaxed bg-white/60 dark:bg-slate-900/40 px-3.5 py-2.5 rounded-lg border border-white/80 dark:border-slate-800/50 shadow-sm backdrop-blur-sm">
                            ${parsedReply}
                        </div>
                    </div>
                </div>
            </div>`;

            screenEl.scrollTop = screenEl.scrollHeight;
            saveChatHistory();
        } catch (e) {
            removeLoadingState();
            screenEl.innerHTML += `
            <div class="flex items-start gap-3 max-w-[90%]">
                <div class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/30 flex items-center justify-center flex-shrink-0 shadow-sm">⚠️</div>
                <div class="space-y-1 w-full">
                    <span class="block text-[10px] uppercase font-mono tracking-wider text-rose-500">System Core</span>
                    <div class="text-rose-800 dark:text-rose-400 text-xs font-normal dark:font-light leading-relaxed bg-rose-50/80 dark:bg-rose-950/20 px-3.5 py-2.5 rounded-lg border border-rose-200 dark:border-rose-900/30 space-y-2">
                        <p>Failed to retrieve portfolio insights. Please verify connection.</p>
                        <div class="font-mono text-[11px] bg-white/50 dark:bg-black/30 text-rose-700 dark:text-rose-300 p-2 rounded border border-rose-200 dark:border-rose-950/50 break-words overflow-x-auto">
                            Code: ${e.message || e}
                        </div>
                    </div>
                </div>
            </div>`;
            screenEl.scrollTop = screenEl.scrollHeight;
        }
    }

    document.getElementById('chat-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') talkToAI();
    });
</script>
</body>

</html>