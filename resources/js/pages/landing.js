document.addEventListener('DOMContentLoaded', () => {
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    // Smooth scroll for internal nav links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Image Gallery Banner (static display)
    // No carousel functionality needed - displays all images at once

    // Mobile Sidebar functionality
    const burgerBtn = document.getElementById('burger-btn');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarClose = document.getElementById('sidebar-close');
    
    if (burgerBtn && mobileSidebar && sidebarOverlay && sidebarClose) {
        // Open sidebar
        burgerBtn.addEventListener('click', function() {
            mobileSidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            burgerBtn.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close sidebar
        function closeSidebar() {
            mobileSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            burgerBtn.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close sidebar when clicking close button
        sidebarClose.addEventListener('click', closeSidebar);

        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking on links
        const sidebarLinks = mobileSidebar.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });

        // Close sidebar on window resize (if going back to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    }

});



