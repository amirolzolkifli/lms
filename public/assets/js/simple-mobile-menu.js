// Simple Mobile Menu JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const menuButton = document.getElementById('simple_mobile_btn');
    const mobileMenu = document.getElementById('simpleMobileMenu');
    const closeButton = document.getElementById('simpleMobileClose');
    const overlay = document.getElementById('simpleMobileOverlay');
    const body = document.body;

    // Function to open menu
    function openMenu() {
        if (mobileMenu && overlay) {
            mobileMenu.classList.add('show');
            overlay.classList.add('show');
            body.classList.add('menu-open');
        }
    }

    // Function to close menu
    function closeMenu() {
        if (mobileMenu && overlay) {
            mobileMenu.classList.remove('show');
            overlay.classList.remove('show');
            body.classList.remove('menu-open');
        }
    }

    // Open menu when clicking menu button
    if (menuButton) {
        menuButton.addEventListener('click', function(e) {
            e.preventDefault();
            openMenu();
        });
    }

    // Close menu when clicking close button
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            closeMenu();
        });
    }

    // Close menu when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            e.preventDefault();
            closeMenu();
        });
    }

    // Close menu when clicking on a menu link (for better UX)
    const menuLinks = document.querySelectorAll('.simple-mobile-nav a');
    menuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // Small delay to allow navigation to start
            setTimeout(closeMenu, 100);
        });
    });

    // Close menu on window resize if transitioning to desktop
    let windowWidth = window.innerWidth;
    window.addEventListener('resize', function() {
        const newWidth = window.innerWidth;
        // If resizing from mobile to desktop (crossing 992px breakpoint)
        if (windowWidth <= 991 && newWidth > 991) {
            closeMenu();
        }
        windowWidth = newWidth;
    });
});