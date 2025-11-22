<header class="admin-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <!-- Logo/Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('superadmin.dashboard') }}">
                <i class="fas fa-warehouse text-primary me-2" style="font-size: 32px;"></i>
                <h1 class="h4 mb-0 fw-bold text-primary">Inventory System</h1>
            </a>

            <!-- Search Bar -->
            <div class="search-container flex-grow-1 mx-4">
                <div class="position-relative">
                    <input type="search" 
                           class="form-control" 
                           placeholder="Search... (Ctrl+K)"
                           data-search-input
                           aria-label="Search">
                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                </div>
            </div>

            <!-- Right Side Icons -->
            <div class="navbar-nav flex-row">
                <!-- Theme Toggle -->
                <button class="btn btn-outline-secondary me-2" 
                        type="button" 
                        id="theme-toggle"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Toggle theme">
                    <i class="bi bi-sun-fill"></i>
                </button>

                <!-- Fullscreen Toggle -->
                <button class="btn btn-outline-secondary me-2" 
                        type="button" 
                        data-fullscreen-toggle
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Toggle fullscreen">
                    <i class="bi bi-arrows-fullscreen icon-hover"></i>
                </button>

                <!-- Notifications -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary position-relative" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-seam me-2"></i>Stok barang menipis</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-cart-plus me-2"></i>Pengadaan baru masuk</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-return-left me-2"></i>Retur pending</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Lihat semua notifikasi</a></li>
                    </ul>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <img src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23avatarGradient)'/%3e%3cg%20fill='white'%20opacity='0.9'%3e%3ccircle%20cx='16'%20cy='12'%20r='5'/%3e%3cpath%20d='M16%2018c-5.5%200-10%202.5-10%207v1h20v-1c0-4.5-4.5-7-10-7z'/%3e%3c/g%3e%3ccircle%20cx='16'%20cy='16'%20r='15.5'%20fill='none'%20stroke='rgba(255,255,255,0.2)'%20stroke-width='1'/%3e%3cdefs%3e%3clinearGradient%20id='avatarGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236b7280;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%234b5563;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e" 
                             alt="User Avatar" 
                             width="24" 
                             height="24" 
                             class="rounded-circle me-2">
                        <span class="d-none d-md-inline">{{ Session::get('user_name', 'Administrator') }}</span>
                        <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-bold">{{ Session::get('user_name', 'Administrator') }}</div>
                            <small class="text-muted">{{ Session::get('user_role_name', 'SuperAdmin') }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
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
    </nav>
</header>