import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('ui', {
    darkMode: localStorage.getItem('tms_dark') === '1',
    toolbarVisible: true,
    toggleDark() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('tms_dark', this.darkMode ? '1' : '0');
        document.body.classList.toggle('dark', this.darkMode);
    },
    toggleToolbar() {
        this.toolbarVisible = !this.toolbarVisible;
    },
});

document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('tms_dark') === '1') {
        document.body.classList.add('dark');
    }

    const handleSearch = (input) => {
        const query = input.value.toLowerCase();
        const root = input.closest('.main-content') || document;
        root.querySelectorAll('table tbody tr').forEach((row) => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    };

    document.querySelectorAll('[data-table-search], .search-box input').forEach((input) => {
        input.addEventListener('input', () => handleSearch(input));
    });
});

Alpine.start();
