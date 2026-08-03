import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

import chart01 from './components/charts/chart-01';
import chart02 from './components/charts/chart-02';
import chart03 from './components/charts/chart-03';

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start();

// Init charts
document.addEventListener('DOMContentLoaded', () => {
    chart01();
    chart02();
    chart03();
});

// Get the current year
const year = document.getElementById('year');
if (year) {
    year.textContent = new Date().getFullYear();
}

// Show a small loader on the clicked link only when a page navigation
// takes longer than the delay threshold.
const NAV_LOADER_DELAY = 250;
let navTimer = null;
let navLink = null;

document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href]');
    if (!link) return;

    if (link.target === '_blank' || link.hasAttribute('download')) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || /^https?:\/\//i.test(href)) return;

    clearTimeout(navTimer);
    navLink = link;
    navTimer = setTimeout(() => {
        if (navLink) {
            navLink.classList.add('nav-loading');
        }
    }, NAV_LOADER_DELAY);
});

window.addEventListener('pageshow', () => {
    clearTimeout(navTimer);
    document.querySelectorAll('.nav-loading').forEach((el) => {
        el.classList.remove('nav-loading');
    });
});
