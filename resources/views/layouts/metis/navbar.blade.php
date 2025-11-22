{{-- resources/views/layouts/metis/navbar.blade.php --}}
<header class="admin-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <!-- Mobile Toggle -->
            <button class="mobile-toggle me-3" type="button" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Page Title -->
            <div class="page-title">
                <h3>
                    <i class="@yield('icon', 'fas fa-home')"></i>
                    @yield('page-title', 'Dashboard')
                </h3>
            </div>

            <!-- Right Side Menu -->
            <div class="navbar-nav ms-auto flex-row align-items-center gap-2">
                <!-- Fullscreen Toggle -->
                <button class="btn btn-outline-secondary" 
                        type="button" 
                        onclick="toggleFullscreen()"
                        data-bs-toggle="tooltip"
                        title="Toggle fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary position-relative" 
                            type="button" 
                            data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-person-plus me-2 text-primary"></i>
                            User baru terdaftar
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-box-seam me-2 text-success"></i>
                            Stok barang menipis
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-cash me-2 text-warning"></i>
                            Pengadaan baru masuk
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center text-primary" href="#">
                            Lihat semua notifikasi
                        </a></li>
                    </ul>
                </div>

                <!-- User Menu -->
                <div class="user-menu">
                    <div class="user-info d-none d-md-block">
                        <span class="user-name">{{ auth()->user()->name ?? 'SuperAdmin' }}</span>
                        <small class="user-role d-block">{{ auth()->user()->role ?? 'Administrator' }}</small>
                    </div>
                    
                    <div class="dropdown">
                        <button class="user-badge" 
                                type="button" 
                                data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-none d-lg-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    .admin-header {
        position: sticky;
        top: 0;
        z-index: 999;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .mobile-toggle {
        display: none;
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: var(--gray-700);
        cursor: pointer;
    }

    .page-title h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    .user-badge {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .user-badge:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    @media (max-width: 992px) {
        .mobile-toggle {
            display: block;
        }

        .page-title h3 {
            font-size: 1.1rem;
        }
    }
</style>

<script>
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }
</script>