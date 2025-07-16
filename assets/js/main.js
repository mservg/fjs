function initHeroVideo() {
  const video = document.querySelector('.hero-video');
  if (video) {
    video.play().catch(() => console.log('Autoplay failed'));
  }
}

function initAccessibility() {
  const skipLink = document.createElement('a');
  skipLink.href = '#main';
  skipLink.className = 'skip-link';
  skipLink.innerText = 'Skip to content';
  document.body.prepend(skipLink);
}

function initEnhancedMenuToggle() {
  const toggle = document.getElementById('mobile-menu-toggle');
  const menu = document.getElementById('menu-overlay');

  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      menu.classList.toggle('open');
      toggle.classList.toggle('active');
    });
  }
}

function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"], .smooth-scroll-btn').forEach(link => {
    link.addEventListener('click', function (e) {
      let targetId;
      if (this.getAttribute('href') && this.getAttribute('href').startsWith('#')) {
        targetId = this.getAttribute('href').substring(1);
      } else if (this.getAttribute('data-target')) {
        targetId = this.getAttribute('data-target');
      } else {
        return;
      }

      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        e.preventDefault();
        const header = document.getElementById('site-header');
        const headerHeight = header ? header.offsetHeight : 80;
        const targetPosition = targetElement.offsetTop - headerHeight - 40;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });

        targetElement.classList.add('scroll-target-highlight');
        setTimeout(() => {
          targetElement.classList.remove('scroll-target-highlight');
        }, 2000);
      }
    });
  });
}

function initVideoLoadingStates() {
  const heroVideo = document.querySelector('.hero-video');
  if (!heroVideo) return;

  heroVideo.style.opacity = '0';

  heroVideo.addEventListener('canplay', function () {
    this.style.opacity = '1';
    this.setAttribute('data-loaded', 'true');
  });

  heroVideo.addEventListener('error', function () {
    console.log('Video failed to load');
    const heroFallback = document.querySelector('.hero-fallback');
    if (heroFallback) {
      heroFallback.style.display = 'block';
    }
  });
}

function initializeTheme() {
  initAccessibility();
  initEnhancedMenuToggle();
  initHeroVideo();
  initSmoothScroll();
  initVideoLoadingStates();
}

document.addEventListener('DOMContentLoaded', initializeTheme);