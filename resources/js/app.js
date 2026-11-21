import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

function initLucide() {
    createIcons({ icons });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLucide);
} else {
    initLucide();
}

window.refreshLucideIcons = initLucide;
