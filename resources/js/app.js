

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const THEME_STORAGE_KEY = 'equiprent-theme';

const applyTheme = (theme) => {
    const isLight = theme === 'light';

    document.documentElement.classList.toggle('theme-light', isLight);
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem(THEME_STORAGE_KEY, theme);

    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        const icon = toggle.querySelector('[data-theme-icon]');
        const label = toggle.querySelector('[data-theme-label]');

        toggle.setAttribute('aria-pressed', String(isLight));

        if (icon) {
            icon.setAttribute('data-lucide', isLight ? 'moon' : 'sun');
        }

        if (label) {
            label.textContent = isLight ? 'Mode gelap' : 'Mode terang';
        }
    });

    if (window.lucide?.createIcons) {
        window.lucide.createIcons();
    }
};

const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
const preferredTheme = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
applyTheme(savedTheme || preferredTheme);

document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('theme-light') ? 'dark' : 'light';
        applyTheme(nextTheme);
    });
});
