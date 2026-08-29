/**
 * Portfolio Single Page Application (SPA) Router
 * Hand-crafted with vanilla JS for instant, lag-free navigation, pre-fetching on hover,
 * and seamless background page transitions.
 */

(function () {
    const pageCache = new Map();
    let currentPrefetchTimeout = null;

    // Create and inject a sleek YouTube-style loading bar at the very top of the viewport
    const loadingBar = document.createElement('div');
    loadingBar.id = 'spa-loading-bar';
    loadingBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background-color: #009688;
        z-index: 999999;
        width: 0%;
        opacity: 0;
        transition: width 0.3s ease, opacity 0.3s ease;
        pointer-events: none;
    `;
    document.body.appendChild(loadingBar);

    function startLoading() {
        loadingBar.style.opacity = '1';
        loadingBar.style.width = '30%';
        // Simulate progress slowly
        setTimeout(() => {
            if (loadingBar.style.width === '30%') {
                loadingBar.style.width = '70%';
            }
        }, 300);
    }

    function stopLoading() {
        loadingBar.style.width = '100%';
        setTimeout(() => {
            loadingBar.style.opacity = '0';
            setTimeout(() => {
                loadingBar.style.width = '0%';
            }, 300);
        }, 150);
    }

    // Resolves relative URLs to absolute URLs
    function getAbsoluteUrl(href) {
        return new URL(href, window.location.href).href;
    }

    // Checks if the link is an internal site link
    function isInternalLink(link) {
        if (!link || !link.href) return false;
        
        // Exclude blank targets, downloads, mailto, tel, etc.
        if (link.getAttribute('target') === '_blank') return false;
        if (link.hasAttribute('download')) return false;
        if (link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return false;
        
        // Match base domain
        const linkUrl = new URL(link.href);
        return linkUrl.origin === window.location.origin;
    }

    // Prefetches the URL and caches it in memory
    function prefetchPage(url) {
        // Remove hash before checking cache or fetching
        const cleanUrl = url.split('#')[0];
        
        // If data saver is active or connection is slow, avoid prefetching
        if (navigator.connection) {
            if (navigator.connection.saveData || /(2g|3g)/.test(navigator.connection.effectiveType)) {
                return;
            }
        }

        if (pageCache.has(cleanUrl)) return;

        const fetchPromise = fetch(cleanUrl, {
            headers: {
                'X-SPA-Request': 'true'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP status ${response.status}`);
            const title = response.headers.get('X-SPA-Title') || document.title;
            return response.text().then(html => ({ html, title }));
        })
        .catch(err => {
            console.warn(`[SPA Router] Prefetch failed for ${cleanUrl}:`, err);
            pageCache.delete(cleanUrl);
        });

        pageCache.set(cleanUrl, fetchPromise);
    }

    // Performs the transition and swaps the content
    async function loadPage(url, pushState = true) {
        const cleanUrl = url.split('#')[0];
        const hash = url.includes('#') ? url.substring(url.indexOf('#')) : '';
        
        startLoading();

        try {
            let pagePromise = pageCache.get(cleanUrl);
            if (!pagePromise) {
                // If not cached, fetch immediately
                prefetchPage(cleanUrl);
                pagePromise = pageCache.get(cleanUrl);
            }

            const result = await pagePromise;
            if (!result) throw new Error('Empty page payload');

            const { html, title } = result;

            // Swap content in the container
            const container = document.getElementById('spa-container');
            if (!container) {
                throw new Error('[SPA Router] Target element #spa-container not found.');
            }

            // Insert HTML
            container.innerHTML = html;

            // Update title
            if (title) {
                document.title = title;
            }

            // Update history if requested
            if (pushState) {
                history.pushState(null, '', url);
            }

            // Execute inline scripts inside the new content
            executeScripts(container);

            // Update navigation link styles
            updateActiveNavigation(cleanUrl);

            // Handle scroll or anchor focus
            if (hash) {
                setTimeout(() => {
                    const targetEl = document.querySelector(hash);
                    if (targetEl) {
                        targetEl.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }, 100);
            } else {
                window.scrollTo({ top: 0 });
            }

            // Dispatch global event for other components to re-bind events
            document.dispatchEvent(new CustomEvent('spa:pageLoaded', { detail: { url } }));

        } catch (error) {
            console.error('[SPA Router] Navigation failed, falling back to browser reload:', error);
            window.location.href = url;
        } finally {
            stopLoading();
        }
    }

    // Execute scripts dynamically
    function executeScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => {
                newScript.setAttribute(attr.name, attr.value);
            });
            newScript.textContent = oldScript.textContent;
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }

    // Update the active state in navbar links
    function updateActiveNavigation(url) {
        const navLinks = document.querySelectorAll('header nav a, #mobile-menu a');
        const urlObj = new URL(url, window.location.href);
        const currentPath = urlObj.pathname;

        navLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (!linkHref) return;

            const linkUrl = new URL(linkHref, window.location.href);
            
            // We ignore links with hashes for active highlights if they point elsewhere
            if (linkHref.includes('#')) return;

            const isSamePage = linkUrl.pathname === currentPath;

            if (isSamePage) {
                link.classList.add('text-brandPrimary', 'font-semibold');
                link.classList.remove('text-gray-600', 'dark:text-gray-400');
                if (link.closest('#mobile-menu')) {
                    link.classList.add('bg-gray-100', 'dark:bg-white/5');
                }
            } else {
                link.classList.remove('text-brandPrimary', 'font-semibold', 'bg-gray-100', 'dark:bg-white/5');
                link.classList.add('text-gray-600', 'dark:text-gray-400');
            }
        });
    }

    // Setup global click and hover interceptors
    function initRouter() {
        // Intercept clicks on links
        document.body.addEventListener('click', e => {
            const link = e.target.closest('a');
            if (!link || !isInternalLink(link)) return;

            const targetUrl = link.href;
            const currentUrl = window.location.href;
            
            // Check if it is a hash link on the same page
            const targetUrlObj = new URL(targetUrl);
            const currentUrlObj = new URL(currentUrl);
            
            if (targetUrlObj.pathname === currentUrlObj.pathname) {
                if (targetUrlObj.hash) {
                    e.preventDefault();
                    const targetEl = document.querySelector(targetUrlObj.hash);
                    if (targetEl) {
                        targetEl.scrollIntoView({ behavior: 'smooth' });
                    }
                    return;
                }
            }

            // Perform SPA load
            e.preventDefault();
            loadPage(targetUrl);
        });

        // Intercept hovers for pre-fetching
        document.body.addEventListener('mouseenter', e => {
            const link = e.target.closest('a');
            if (!link || !isInternalLink(link)) return;

            const targetUrl = link.href;
            
            // Avoid prefetching if it's just a local hash link
            const targetUrlObj = new URL(targetUrl);
            if (targetUrlObj.pathname === window.location.pathname) return;

            // Wait 50ms before prefetching to avoid accidental swipes/hovers
            currentPrefetchTimeout = setTimeout(() => {
                prefetchPage(targetUrl);
            }, 50);
        }, true);

        document.body.addEventListener('mouseleave', e => {
            const link = e.target.closest('a');
            if (link && isInternalLink(link)) {
                clearTimeout(currentPrefetchTimeout);
            }
        }, true);

        // Mobile touch prefetching
        document.body.addEventListener('touchstart', e => {
            const link = e.target.closest('a');
            if (!link || !isInternalLink(link)) return;
            
            const targetUrlObj = new URL(link.href);
            if (targetUrlObj.pathname === window.location.pathname) return;

            prefetchPage(link.href);
        }, { passive: true });

        // Handle popstate (Back/Forward buttons)
        window.addEventListener('popstate', () => {
            loadPage(window.location.href, false);
        });

        // Setup Intersection Observer to prefetch internal links currently in view
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const link = entry.target;
                        if (isInternalLink(link)) {
                            prefetchPage(link.href);
                        }
                        observer.unobserve(link);
                    }
                });
            }, { rootMargin: '200px' });

            // Observe all internal links present at startup
            document.querySelectorAll('a').forEach(link => {
                if (isInternalLink(link)) {
                    observer.observe(link);
                }
            });

            // Re-observe when new pages are loaded
            document.addEventListener('spa:pageLoaded', () => {
                document.querySelectorAll('a').forEach(link => {
                    if (isInternalLink(link)) {
                        observer.observe(link);
                    }
                });
            });
        }
        
        // Highlight active navigation on first load
        updateActiveNavigation(window.location.href);
    }

    // Initialize SPA Router
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRouter);
    } else {
        initRouter();
    }

    // Expose dynamic navigation globally
    window.spaNavigate = function (url) {
        loadPage(getAbsoluteUrl(url));
    };
})();
