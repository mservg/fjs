/**
 * Jocelyne Saab Theme - Enhanced Main JavaScript
 * Modern ES6+ JavaScript with accessibility and performance in mind
 * Updated: 2025-01-27 by karlserag
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTheme();
        initBackToTop();
        initScrollAnimations();
        initCarouselArrows();
        initNewPageFeatures();
    });

    /**
     * Initialize all theme functionality
     */
    function initializeTheme() {
        console.log('Initializing enhanced theme...');
        initHeader();
        initLazyLoading();
        initCarousels();
        initFormHandling();
        initScreeningTabs();
        initWorkshopFilters();
        initNewsCarousel();
        initFilmArchiveFiltering();
    }

    /**
     * Enhanced header with scroll effects
     */
    function initHeader() {
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
     * Lazy loading for images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize Swiper carousels (if Swiper is loaded)
     */
    function initCarousels() {
        if (typeof Swiper === 'undefined') return;

        // Stories carousel
        if (document.querySelector('.stories-swiper')) {
            new Swiper('.stories-swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                keyboard: {
                    enabled: true,
                },
                a11y: {
                    enabled: true,
                },
            });
        }

        // Film stills carousel
        if (document.querySelector('.film-stills-swiper')) {
            new Swiper('.film-stills-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
                a11y: { enabled: true },
                rtl: document.dir === 'rtl',
            });
        }

        // Trainings/Workshops carousel
        if (document.querySelector('.workshop-gallery-swiper')) {
            new Swiper('.workshop-gallery-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
                a11y: { enabled: true },
                rtl: document.dir === 'rtl',
            });
        }

        // Homepage Films carousel
        if (document.querySelector('.films-carousel')) {
            new Swiper('.films-carousel', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                keyboard: {
                    enabled: true,
                },
                a11y: {
                    enabled: true,
                },
            });
        }

        // Homepage Workshops carousel
        if (document.querySelector('.workshops-carousel')) {
            new Swiper('.workshops-carousel', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
                keyboard: {
                    enabled: true,
                },
                a11y: {
                    enabled: true,
                },
            });
        }
    }

    /**
     * Form handling and validation
     */
    function initFormHandling() {
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[type="email"]');
                const button = this.querySelector('button');
                
                if (!email.value || !isValidEmail(email.value)) {
                    showFormMessage(this, 'Please enter a valid email address.', 'error');
                    return;
                }
                
                // Disable button during submission
                button.disabled = true;
                button.textContent = 'Subscribing...';
                
                // Simulate form submission (replace with actual implementation)
                setTimeout(() => {
                    showFormMessage(this, 'Thank you for subscribing!', 'success');
                    email.value = '';
                    button.disabled = false;
                    button.textContent = 'Subscribe';
                }, 1500);
            });
        }
    }

    /**
     * Email validation helper
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Show form messages
     */
    function showFormMessage(form, message, type) {
        const existingMessage = form.querySelector('.form-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageEl = document.createElement('div');
        messageEl.className = `form-message form-message-${type}`;
        messageEl.textContent = message;
        messageEl.setAttribute('role', type === 'error' ? 'alert' : 'status');
        
        form.appendChild(messageEl);

        // Remove message after 5 seconds
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.remove();
            }
        }, 5000);
    }

    /**
     * Animate elements on scroll (fade-in/slide-up)
     */
    function initScrollAnimations() {
        const animatedEls = document.querySelectorAll('.animated-fadeinup');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            animatedEls.forEach(el => observer.observe(el));
        } else {
            // Fallback: show all
            animatedEls.forEach(el => el.classList.add('visible'));
        }
    }

    /**
     * Screening tabs functionality
     */
    function initScreeningTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const screeningSections = document.querySelectorAll('.screening-section');
        
        if (!tabButtons.length || !screeningSections.length) return;
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('aria-controls');
                const targetSection = document.getElementById(targetId);
                
                if (!targetSection) return;
                
                // Update tab states
                tabButtons.forEach(btn => {
                    btn.setAttribute('aria-selected', 'false');
                    btn.classList.remove('active');
                });
                
                this.setAttribute('aria-selected', 'true');
                this.classList.add('active');
                
                // Update section visibility
                screeningSections.forEach(section => {
                    section.classList.remove('active');
                    section.setAttribute('aria-hidden', 'true');
                });
                
                targetSection.classList.add('active');
                targetSection.setAttribute('aria-hidden', 'false');
                
                // Focus management
                targetSection.focus();
            });
        });
        
        // Keyboard navigation for tabs
        tabButtons.forEach((button, index) => {
            button.addEventListener('keydown', function(e) {
                let targetIndex;
                
                switch(e.key) {
                    case 'ArrowLeft':
                        targetIndex = index - 1;
                        if (targetIndex < 0) targetIndex = tabButtons.length - 1;
                        break;
                    case 'ArrowRight':
                        targetIndex = index + 1;
                        if (targetIndex >= tabButtons.length) targetIndex = 0;
                        break;
                    case 'Home':
                        targetIndex = 0;
                        break;
                    case 'End':
                        targetIndex = tabButtons.length - 1;
                        break;
                    default:
                        return;
                }
                
                e.preventDefault();
                tabButtons[targetIndex].focus();
                tabButtons[targetIndex].click();
            });
        });
    }

    /**
     * Workshop filters functionality
     */
    function initWorkshopFilters() {
        const filterButtons = document.querySelectorAll('.workshop-filters .filter-btn');
        const workshopCards = document.querySelectorAll('.workshop-card');
        
        if (!filterButtons.length || !workshopCards.length) return;
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter workshops
                workshopCards.forEach(card => {
                    const isUpcoming = card.classList.contains('upcoming');
                    const isPast = card.classList.contains('past');
                    
                    let show = false;
                    
                    switch(filter) {
                        case 'all':
                            show = true;
                            break;
                        case 'upcoming':
                            show = isUpcoming;
                            break;
                        case 'past':
                            show = isPast;
                            break;
                    }
                    
                    if (show) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.5s ease forwards';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Check if no workshops are shown
                const visibleCards = document.querySelectorAll('.workshop-card[style*="display: block"]');
                const noWorkshops = document.querySelector('.no-workshops');
                
                if (visibleCards.length === 0) {
                    if (!noWorkshops) {
                        const noWorkshopsDiv = document.createElement('div');
                        noWorkshopsDiv.className = 'no-workshops';
                        noWorkshopsDiv.innerHTML = '<p>' + (filter === 'upcoming' ? 'No upcoming workshops found.' : 'No past workshops found.') + '</p>';
                        document.getElementById('workshops-grid').appendChild(noWorkshopsDiv);
                    }
                } else if (noWorkshops) {
                    noWorkshops.remove();
                }
            });
        });
    }

    /**
     * Carousel arrows for news carousel
     */
    function initCarouselArrows() {
        console.log('Initializing carousel arrows...');
        const carousel = document.getElementById('news-carousel');
        const leftArrow = document.querySelector('.carousel-arrow-left');
        const rightArrow = document.querySelector('.carousel-arrow-right');
        
        console.log('Carousel:', carousel);
        console.log('Left arrow:', leftArrow);
        console.log('Right arrow:', rightArrow);
        
        if (carousel && leftArrow && rightArrow) {
            function scrollCarousel(dir) {
                const card = carousel.querySelector('.news-card');
                if (!card) {
                    console.log('No news card found');
                    return;
                }
                const scrollAmount = card.offsetWidth + 32; // 32px gap
                console.log('Scrolling by:', dir * scrollAmount);
                carousel.scrollBy({ left: dir * scrollAmount, behavior: 'smooth' });
            }
            
            leftArrow.addEventListener('click', () => {
                console.log('Left arrow clicked');
                scrollCarousel(-1);
            });
            rightArrow.addEventListener('click', () => {
                console.log('Right arrow clicked');
                scrollCarousel(1);
            });
            
            // Keyboard navigation
            leftArrow.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollCarousel(-1);
                }
            });
            
            rightArrow.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollCarousel(1);
                }
            });
            
            console.log('Carousel arrows initialized successfully');
        } else {
            console.log('Carousel arrows not found - elements missing');
        }
    }

    // ========================================
    // NEW PAGE TEMPLATES FUNCTIONALITY
    // ========================================

    // Biography Page Timeline Functionality
    function initBiographyTimeline() {
        const timelineFilters = document.querySelectorAll('.timeline-filter');
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        if (!timelineFilters.length) return;
        
        timelineFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                
                // Update active filter
                timelineFilters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
                
                // Filter timeline items
                timelineItems.forEach(item => {
                    const year = item.getAttribute('data-year');
                    if (filterValue === 'all' || (year >= filterValue.split('-')[0] && year <= filterValue.split('-')[1])) {
                        item.style.display = 'block';
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(30px)';
                        
                        // Animate in
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }

    // Association Page Team Filtering
    function initTeamFilters() {
        const teamFilters = document.querySelectorAll('.team-filter');
        const teamMembers = document.querySelectorAll('.team-member');
        
        if (!teamFilters.length) return;
        
        teamFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                
                // Update active filter
                teamFilters.forEach(f => f.classList.remove('active'));
                this.classList.add('active');
                
                // Filter team members
                teamMembers.forEach(member => {
                    const category = member.getAttribute('data-category');
                    if (filterValue === 'all' || category === filterValue) {
                        member.style.display = 'block';
                        member.style.opacity = '0';
                        member.style.transform = 'translateY(20px)';
                        
                        // Animate in
                        setTimeout(() => {
                            member.style.opacity = '1';
                            member.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        member.style.display = 'none';
                    }
                });
            });
        });
    }

    // Mission/Vision Card Details Toggle
    function initCardDetailsToggle() {
        const detailToggles = document.querySelectorAll('.details-toggle');
        
        detailToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                if (isExpanded) {
                    content.classList.remove('active');
                    this.setAttribute('aria-expanded', 'false');
                    this.textContent = 'Learn More';
                } else {
                    content.classList.add('active');
                    this.setAttribute('aria-expanded', 'true');
                    this.textContent = 'Show Less';
                }
            });
        });
    }

    // Contact Page Preference Selector
    function initContactPreferenceSelector() {
        const preferenceOptions = document.querySelectorAll('.preference-option');
        const contactFormSection = document.getElementById('contact-form-section');
        const contactInfoSection = document.getElementById('contact-info-section');
        const visitSection = document.getElementById('visit-section');
        
        if (!preferenceOptions.length) return;
        
        preferenceOptions.forEach(option => {
            option.addEventListener('click', function() {
                const preference = this.getAttribute('data-preference');
                
                // Update active option
                preferenceOptions.forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                
                // Show/hide sections
                contactFormSection.style.display = preference === 'form' ? 'block' : 'none';
                contactInfoSection.style.display = preference === 'direct' ? 'block' : 'none';
                visitSection.style.display = preference === 'visit' ? 'block' : 'none';
                
                // Animate section change
                const activeSection = document.querySelector(`#${preference}-section`);
                if (activeSection) {
                    activeSection.style.opacity = '0';
                    activeSection.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        activeSection.style.opacity = '1';
                        activeSection.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        });
    }

    // FAQ Accordion Functionality
    function initFAQAccordion() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Close other open FAQs
                faqQuestions.forEach(q => {
                    if (q !== this) {
                        q.setAttribute('aria-expanded', 'false');
                        q.nextElementSibling.classList.remove('active');
                    }
                });
                
                // Toggle current FAQ
                if (isExpanded) {
                    answer.classList.remove('active');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    answer.classList.add('active');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
            
            // Keyboard support
            question.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    }

    // Smooth Scroll for Biography Hero
    function initBiographyScroll() {
        const scrollIndicator = document.querySelector('.hero-scroll-indicator');
        
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function() {
                const timelineSection = document.querySelector('.timeline-section');
                if (timelineSection) {
                    timelineSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }
    }

    // Contact Form Enhancement
    function initContactForm() {
        const contactForm = document.querySelector('.smart-contact-form');
        
        if (!contactForm) return;
        
        // Subject field change handler
        const subjectSelect = contactForm.querySelector('#contact-subject');
        if (subjectSelect) {
            subjectSelect.addEventListener('change', function() {
                const messageField = contactForm.querySelector('#contact-message');
                const suggestions = {
                    'screening': 'Please include details about your screening request, including venue, date, and expected audience size.',
                    'collaboration': 'Please describe your proposed collaboration and how it aligns with our mission.',
                    'support': 'Please let us know how you would like to support our work and any specific areas of interest.',
                    'media': 'Please provide details about your media inquiry and deadline.',
                    'general': ''
                };
                
                const suggestion = suggestions[this.value] || '';
                if (suggestion) {
                    messageField.placeholder = suggestion;
                }
            });
        }
        
        // Form submission enhancement
        contactForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitButton.disabled = true;
            
            // Simulate form processing (replace with actual form handling)
            setTimeout(() => {
                submitButton.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                submitButton.style.background = '#28a745';
                
                // Reset form
                setTimeout(() => {
                    this.reset();
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    submitButton.style.background = '';
                }, 2000);
            }, 1500);
        });
    }

    // Team Mosaic Hover Effects
    function initTeamMosaic() {
        const mosaicItems = document.querySelectorAll('.mosaic-item');
        
        mosaicItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const name = this.getAttribute('data-name');
                if (name) {
                    // Create tooltip
                    const tooltip = document.createElement('div');
                    tooltip.className = 'mosaic-tooltip';
                    tooltip.textContent = name;
                    tooltip.style.cssText = `
                        position: absolute;
                        background: rgba(28, 47, 124, 0.9);
                        color: #fff;
                        padding: 0.5rem 1rem;
                        border-radius: 6px;
                        font-size: 0.9rem;
                        z-index: 10;
                        pointer-events: none;
                        white-space: nowrap;
                    `;
                    
                    this.appendChild(tooltip);
                    
                    // Position tooltip
                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = '50%';
                    tooltip.style.top = '-40px';
                    tooltip.style.transform = 'translateX(-50%)';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.mosaic-tooltip');
                if (tooltip) {
                    tooltip.remove();
                }
            });
        });
    }

    // Timeline Animation on Scroll
    function initTimelineScrollAnimation() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        if (!timelineItems.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        timelineItems.forEach(item => {
            observer.observe(item);
        });
    }

    // Contact Map Integration (if Leaflet is available)
    function initContactMap() {
        const contactMap = document.getElementById('contact-map');
        
        if (!contactMap || typeof L === 'undefined') return;
        
        // Initialize map (replace with actual coordinates)
        const map = L.map('contact-map').setView([0, 0], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add marker (replace with actual address coordinates)
        const marker = L.marker([0, 0]).addTo(map);
        marker.bindPopup('Jocelyne Saab Association').openPopup();
    }

    // Initialize all new page functionality
    function initNewPageFeatures() {
        // Biography page
        initBiographyTimeline();
        initBiographyScroll();
        initTimelineScrollAnimation();
        
        // Association page
        initTeamFilters();
        initCardDetailsToggle();
        initTeamMosaic();
        
        // Contact page
        initContactPreferenceSelector();
        initContactForm();
        initFAQAccordion();
        initContactMap();
    }

    /**
     * Initialize news carousel functionality
     */
    function initNewsCarousel() {
        const carousel = document.getElementById('news-carousel');
        const leftArrow = document.querySelector('.carousel-arrow-left');
        const rightArrow = document.querySelector('.carousel-arrow-right');
        
        if (!carousel || !leftArrow || !rightArrow) return;
        
        const scrollAmount = 360; // Width of card + gap
        
        leftArrow.addEventListener('click', () => {
            carousel.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
        
        rightArrow.addEventListener('click', () => {
            carousel.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Update arrow visibility based on scroll position
        function updateArrowVisibility() {
            const isAtStart = carousel.scrollLeft === 0;
            const isAtEnd = carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth;
            
            leftArrow.style.opacity = isAtStart ? '0.5' : '1';
            rightArrow.style.opacity = isAtEnd ? '0.5' : '1';
            leftArrow.style.pointerEvents = isAtStart ? 'none' : 'auto';
            rightArrow.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        }
        
        carousel.addEventListener('scroll', updateArrowVisibility);
        updateArrowVisibility(); // Initial check
    }

    /**
     * Initialize film archive filtering
     */
    function initFilmArchiveFiltering() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const workItems = document.querySelectorAll('.work-item');
        
        if (!filterButtons.length || !workItems.length) return;
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Filter items
                workItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    
                    if (category === 'all' || itemCategory === category) {
                        item.style.display = 'block';
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

})();

// Additional functionality for AJAX filtering
if (typeof saabAjax !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        initFiltering();
    });

    function initFiltering() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                const type = this.getAttribute('data-type') || 'category';
                const grid = document.querySelector('#films-grid, #portfolio-grid, #news-grid');
                
                if (!grid) return;

                // Update active states
                document.querySelectorAll(`.filter-btn[data-type="${type}"]`).forEach(b => 
                    b.classList.remove('active')
                );
                this.classList.add('active');

                // Show loading state
                grid.classList.add('loading');

                // Prepare form data
                const formData = new FormData();
                formData.append('action', 'filter_films'); // or appropriate action
                formData.append('category', filter);
                formData.append('nonce', saabAjax.nonce);

                // AJAX request
                fetch(saabAjax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    grid.innerHTML = data;
                    grid.classList.remove('loading');
                    
                    // Announce to screen readers
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = `Filtered content updated. Showing ${filter === 'all' ? 'all items' : filter + ' items'}.`;
                    document.body.appendChild(announcement);
                    
                    setTimeout(() => {
                        if (announcement.parentNode) {
                            document.body.removeChild(announcement);
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    grid.classList.remove('loading');
                    
                    // Show error message
                    if (window.saabTheme && window.saabTheme.showFormMessage) {
                        window.saabTheme.showFormMessage(grid.parentElement, saabAjax.strings.error || 'An error occurred while filtering.', 'error');
                    }
                });
            });
        });
    }
}