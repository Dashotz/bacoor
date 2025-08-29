document.addEventListener('DOMContentLoaded', () => {
    const buttons = Array.from(document.querySelectorAll('.tab-button'));
    const panels = Array.from(document.querySelectorAll('.tab-panel'));
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    function activate(targetSelector){
        buttons.forEach(b=>b.classList.toggle('active', b.getAttribute('data-target') === targetSelector));
        panels.forEach(p=>p.classList.toggle('active', `#${p.id}` === targetSelector));
    }

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-target') || '#login';
            activate(target);
        });
    });

    // Check for activeTab parameter from server-side
    const activeTab = document.querySelector('.tab-button.active')?.getAttribute('data-target') || '#login';
    activate(activeTab);

    // If URL contains ?tab=register or #register, activate register
    const url = new URL(window.location.href);
    const tabParam = url.searchParams.get('tab');
    const hash = window.location.hash;
    if (tabParam === 'register' || hash === '#register') {
        activate('#register');
    } else if (tabParam === 'login' || hash === '#login') {
        activate('#login');
    }
});


