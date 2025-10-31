// Global click fade effect for all clickable elements
document.addEventListener('click', function(e) {
    let el = e.target.closest('a, button, [role="button"]');
    if (el) {
        el.style.transition = 'opacity 0.2s';
        el.style.opacity = 0.3;
        setTimeout(() => {
            el.style.opacity = 1;
        }, 200);
    }
}); 