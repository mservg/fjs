/**
 * Enhanced JavaScript for Jocelyne Saab Theme
 * Improved hamburger menu, hero video, and accessibility features
 * Author: Karl Serag (karlserag)
 * Date: 2025-01-27
 * Version: 2.0.0
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeEnhancedTheme();
    });

    /**
     * Initialize all enhanced theme functionality
     */
    function initializeEnhancedTheme() {
        console.log('Initializing enhanced theme...');
        initEnhancedHeader();
        initEnhancedMenuToggle();
        initHeroVideo();
        initRotatingText();
        initSmoothScroll();
        initAccessibility();
        initScrollAnimations();
    }

    /**
     * Enhanced header with scroll effects
     */
    function initEnhancedHeader() {
        const header = document.getElementById('site-header');
        if (!header) return;
        
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateHeader() {
            const currentScrollY = window.scrollY;
            
            // Add scrolled class for background change
            if (currentScrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Hide header on scroll down, show on scroll up (optional)
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
            
            lastScrollY = currentScrollY;
            ticking = false;
        }

        function requestHeaderUpdate() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestHeaderUpdate, { passive: true });
    }

    /**
     * Enhanced hamburger menu toggle with smooth animations
     */
    function initEnhancedMenuToggle() {
        console.log('Initializing enhanced menu toggle...');
        const menuToggle = document.querySelector('#mobile-menu-toggle');
        const menuOverlay = document.getElementById('menu-overlay');
        const menuClose = document.getElementById('menu-overlay-close');
        const body = document.body;

        console.log('Menu toggle element:', menuToggle);
        console.log('Menu overlay element:', menuOverlay);

        if (!menuToggle || !menuOverlay) {
            console.error('Menu elements not found!');
            return;
        }

        // Focus trap for menu overlay
        const focusableElements = menuOverlay.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
        );
        const firstFocusableElement = focusableElements[0];
        const lastFocusableElement = focusableElements[focusableElements.length - 1];

        function openMenu() {
            console.log('Opening menu...');
            menuOverlay.setAttribute('aria-hidden', 'false');
            menuOverlay.classList.add('open');
            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.classList.add('menu-open');
            body.style.overflow = 'hidden';
            
            // Focus first element in menu
            if (firstFocusableElement) {
                setTimeout(() => {
                    firstFocusableElement.focus();
                }, 100);
            }

            // Add event listeners for closing
            document.addEventListener('keydown', handleMenuKeydown);
            menuOverlay.addEventListener('click', handleMenuOverlayClick);
        }

        function closeMenu() {
            console.log('Closing menu...');
            menuOverlay.setAttribute('aria-hidden', 'true');
            menuOverlay.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.classList.remove('menu-open');
            body.style.overflow = '';
            
            // Return focus to menu toggle
            menuToggle.focus();

            // Remove event listeners
            document.removeEventListener('keydown', handleMenuKeydown);
            menuOverlay.removeEventListener('click', handleMenuOverlayClick);
        }

        function handleMenuKeydown(e) {
            if (e.key === 'Escape') {
                closeMenu();
                return;
            }

            // Focus trap
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusableElement) {
                        e.preventDefault();
                        lastFocusableElement.focus();
                    }
                } else {
                    if (document.activeElement === lastFocusableElement) {
                        e.preventDefault();
                        firstFocusableElement.focus();
                    }
                }
            }
        }

        function handleMenuOverlayClick(e) {
            // Close menu if clicking on overlay background
            if (e.target === menuOverlay) {
                closeMenu();
            }
        }

        // Event listeners
        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                console.log('Menu toggle clicked!');
                e.preventDefault();
                if (menuOverlay.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }

        if (menuClose) {
            menuClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeMenu();
            });
        }

        // Close menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024 && menuOverlay.classList.contains('open')) {
                closeMenu();
            }
        });

        console.log('Menu toggle initialization complete');
    }

    /**
     * Enhanced hero video functionality
     */
    function initHeroVideo() {
        const heroVideo = document.querySelector('.hero-video video');
        if (!heroVideo) return;

        // Handle video load errors
        heroVideo.addEventListener('error', function() {
            console.log('Hero video failed to load, falling back to background');
            const heroBackground = document.querySelector('.hero-background');
            if (heroBackground) {
                heroBackground.classList.remove('hero-video');
                heroBackground.classList.add('hero-fallback');
            }
        });

        // Pause video when not in viewport for performance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    heroVideo.play().catch(e => console.log('Video autoplay prevented:', e));
                } else {
                    heroVideo.pause();
                }
            });
        }, {
            threshold: 0.1
        });

        observer.observe(heroVideo);

        // Handle video loading
        heroVideo.addEventListener('loadeddata', function() {
            heroVideo.style.opacity = '1';
        });

        // Fallback for browsers that don't support autoplay
        heroVideo.addEventListener('pause', function() {
            if (heroVideo.currentTime === 0) {
                // Video hasn't started, try to play
                heroVideo.play().catch(e => console.log('Video play failed:', e));
            }
        });
    }

    /**
     * Enhanced rotating text animation
     */
    function initRotatingText() {
        const rotatingContainer = document.querySelector('.rotating-text');
        if (!rotatingContainer) return;

        const words = rotatingContainer.querySelectorAll('.rotating-word');
        if (words.length <= 1) return;

        let currentIndex = 0;
        let isAnimating = false;

        function rotateText() {
            if (isAnimating) return;
            isAnimating = true;

            // Remove active class from current word
            words[currentIndex].classList.remove('active');

            // Move to next word
            currentIndex = (currentIndex + 1) % words.length;

            // Add active class to new word
            words[currentIndex].classList.add('active');

            // Reset animation flag after transition
            setTimeout(() => {
                isAnimating = false;
            }, 500);
        }

        // Initialize first word
        if (words.length > 0) {
            words[0].classList.add('active');
        }

        // Start rotation interval
        const interval = setInterval(rotateText, 3000);

        // Pause animation when not visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Resume animation
                    clearInterval(interval);
                    setInterval(rotateText, 3000);
                } else {
                    // Pause animation
                    clearInterval(interval);
                }
            });
        }, {
            threshold: 0.1
        });

        observer.observe(rotatingContainer);
    }

    /**
     * Enhanced smooth scrolling
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    
                    const header = document.getElementById('site-header');
                    const headerHeight = header ? header.offsetHeight : 80;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL hash
                    history.pushState(null, null, '#' + targetId);
                }
            });
        });
    }

    /**
     * Enhanced accessibility features
     */
    function initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }

        // Add ARIA labels to interactive elements
        document.querySelectorAll('button:not([aria-label])').forEach(button => {
            if (button.textContent.trim()) {
                button.setAttribute('aria-label', button.textContent.trim());
            }
        });

        // Handle reduced motion preference
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--transition-normal', '0s');
            document.documentElement.style.setProperty('--transition-slow', '0s');
        }
    }

    /**
     * Enhanced scroll animations
     */
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }

    /**
     * Utility function for debouncing
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Utility function for throttling
     */
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Expose functions globally for debugging
    window.saabTheme = {
        initEnhancedTheme,
        initEnhancedMenuToggle,
        initHeroVideo,
        initRotatingText
    };

})(); 