// assets/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initMobileMenu();
    setActiveLink();
    initScrollEffects();

    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const menuClose = document.querySelector('.menu-close');
        const mainNav = document.querySelector('.main-nav');
        
        function closeMenu() {
            if (menuToggle && mainNav) {
                menuToggle.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        }
        
        function openMenu() {
            if (menuToggle && mainNav) {
                menuToggle.classList.add('active');
                menuToggle.setAttribute('aria-expanded', 'true');
                mainNav.classList.add('active');
                document.body.classList.add('menu-open');
            }
        }
        
        // Toggle menu
        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }
        
        // Close button
        if (menuClose) {
            menuClose.addEventListener('click', function() {
                closeMenu();
            });
        }

        // Close menu when clicking on nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                closeMenu();
            });
        });
        
        // Close menu when clicking on overlay (body background)
        document.body.addEventListener('click', function(e) {
            if (document.body.classList.contains('menu-open') && 
                !mainNav.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                closeMenu();
            }
        });
    }

    function setActiveLink() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        document.querySelectorAll('.nav-link').forEach(link => {
            const linkPath = link.getAttribute('href');
            if (linkPath === currentPage ||
                (currentPage === '' && linkPath === 'index.php')) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            } else {
                link.classList.remove('active');
                link.removeAttribute('aria-current');
            }
        });
    }

    function initNavigation() {
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                
                // Handle internal page links
                if (href.endsWith('.php') || href.endsWith('.html')) {
                    // Let the browser handle the navigation
                    return;
                }
                
                // Handle anchor links
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href;
                    const targetSection = document.querySelector(targetId);

                    if (targetSection) {
                        const header = document.querySelector('.site-header');
                        const headerHeight = header ? header.offsetHeight : 0;
                        const targetPosition = targetSection.offsetTop - headerHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }

    function initScrollEffects() {
        const header = document.querySelector('.site-header');
        
        if (header) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }
    }
});