<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconContainer = document.getElementById('menu-icon-container');

        const hamburgerIcon =
            `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>`;
        const closeIcon =
            `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;

        if (!btn || !menu) return;

        btn.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');
            // Jika menu tersembunyi (hidden), pakai hamburger. Jika tidak, pakai silang.
            iconContainer.innerHTML = isHidden ? hamburgerIcon : closeIcon;
        });
    });
</script>
