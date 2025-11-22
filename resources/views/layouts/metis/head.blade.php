<!-- Custom CSS dari public/assets -->
<link rel="stylesheet" href="{{ asset('assets/metis.min.css') }}">

<!-- Fix Sidebar Scroll -->
<style>
/* Fix Scroll untuk Sidebar */
.admin-sidebar {
    height: 100vh !important;
    max-height: 100vh !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    position: fixed;
}

.sidebar-content {
    overflow-y: auto !important;
    overflow-x: hidden !important;
    height: 100%;
}

.sidebar-nav {
    overflow-y: auto !important;
}

/* Custom Scrollbar */
.admin-sidebar::-webkit-scrollbar {
    width: 6px;
}

.admin-sidebar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
}

.admin-sidebar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

.admin-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.4);
}
</style>

@stack('styles')