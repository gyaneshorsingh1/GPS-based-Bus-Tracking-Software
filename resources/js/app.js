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
