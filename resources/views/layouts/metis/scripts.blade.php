<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Alpine.js (if needed) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Custom Admin Scripts from public/assets -->
<script src="{{ asset('assets/metis.min.js') }}"></script>

<!-- Inline Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Hide loading screen
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
        }, 500);
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Sidebar toggle
    const toggleButton = document.querySelector('[data-sidebar-toggle]');
    const wrapper = document.getElementById('admin-wrapper');

    if (toggleButton && wrapper) {
        // Set initial state from localStorage
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            wrapper.classList.add('sidebar-collapsed');
            toggleButton.classList.add('is-active');
        }

        // Attach click listener
        toggleButton.addEventListener('click', () => {
            const isCurrentlyCollapsed = wrapper.classList.contains('sidebar-collapsed');
            
            if (isCurrentlyCollapsed) {
                wrapper.classList.remove('sidebar-collapsed');
                toggleButton.classList.remove('is-active');
                localStorage.setItem('sidebar-collapsed', 'false');
            } else {
                wrapper.classList.add('sidebar-collapsed');
                toggleButton.classList.add('is-active');
                localStorage.setItem('sidebar-collapsed', 'true');
            }
        });
    }

    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        
        const icon = themeToggle.querySelector('i');
        icon.className = currentTheme === 'light' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        
        themeToggle.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = theme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            icon.className = newTheme === 'light' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        });
    }

    // Fullscreen toggle
    const fullscreenToggle = document.querySelector('[data-fullscreen-toggle]');
    if (fullscreenToggle) {
        fullscreenToggle.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Search functionality (Ctrl+K)
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('[data-search-input]');
            if (searchInput) {
                searchInput.focus();
            }
        }
    });
});
</script>