<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SuperAdmin - Sistem Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #1e3c72;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #2c3e50;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        .sidebar-header {
            padding: 30px 20px;
            background: rgba(0,0,0,0.2);
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .sidebar-header h4 {
            margin: 10px 0 5px 0;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 0.5px;
        }
        
        .sidebar-header small {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 14.5px;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            border-left-color: rgba(255,255,255,0.5);
            padding-left: 30px;
        }
        
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }
        
        .sidebar-menu a i {
            width: 24px;
            margin-right: 15px;
            font-size: 16px;
            text-align: center;
        }
        
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.15);
            margin: 20px 25px;
        }
        
        .logout-btn {
            margin: 15px 20px 25px 20px;
        }
        
        .logout-btn form button {
            width: 100%;
            padding: 13px;
            background: rgba(231, 76, 60, 0.15);
            border: 2px solid rgba(231, 76, 60, 0.4);
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .logout-btn form button:hover {
            background: #e74c3c;
            border-color: #e74c3c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .logout-btn form button i {
            margin-right: 8px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f5f6fa;
        }
        
        .top-navbar {
            background: white;
            padding: 22px 35px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e8eaed;
        }
        
        .page-title h3 {
            margin: 0;
            color: #2c3e50;
            font-weight: 700;
            font-size: 24px;
        }
        
        .page-title h3 i {
            color: #1e3c72;
            margin-right: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info .text-end {
            text-align: right;
        }
        
        .user-info .fw-bold {
            color: #2c3e50;
            font-size: 15px;
            font-weight: 600;
        }
        
        .user-info .text-muted {
            font-size: 13px;
            color: #7f8c8d;
        }
        
        .user-badge {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 10px 22px;
            border-radius: 25px;
            font-size: 13.5px;
            font-weight: 600;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.2);
        }
        
        .user-badge i {
            margin-right: 8px;
            font-size: 14px;
        }
        
        /* Content Area */
        .content-area {
            padding: 35px;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 16px 20px;
            font-size: 14.5px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert i {
            font-size: 16px;
        }
        
        /* Footer */
        .text-center.mt-5 {
            padding: 25px 0;
            border-top: 1px solid #e8eaed;
            margin-top: 50px !important;
        }
        
        .text-center.mt-5 small {
            color: #95a5a6;
            font-size: 13px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .top-navbar {
                padding: 18px 20px;
            }
            
            .content-area {
                padding: 20px;
            }
            
            .user-badge {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-warehouse"></i>
            <h4>Inventory System</h4>
            <small>SuperAdmin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.user.index') }}" class="{{ request()->routeIs('superadmin.user.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Manajemen User
            </a>
            <a href="{{ route('superadmin.role.index') }}" class="{{ request()->routeIs('superadmin.role.*') ? 'active' : '' }}">
                <i class="fas fa-user-tag"></i> Manajemen Role
            </a>
            <a href="{{ route('superadmin.barang.index') }}" class="{{ request()->routeIs('superadmin.barang.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Data Barang
            </a>
            <a href="{{ route('superadmin.satuan.index') }}" class="{{ request()->routeIs('superadmin.satuan.*') ? 'active' : '' }}">
                <i class="fas fa-balance-scale"></i> Satuan
            </a>
            <a href="{{ route('superadmin.vendor.index') }}" class="{{ request()->routeIs('superadmin.vendor.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Vendor
            </a>
            <a href="{{ route('superadmin.pengadaan.index') }}" class="{{ request()->routeIs('superadmin.pengadaan.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Pengadaan
            </a>
            <a href="{{ route('superadmin.penerimaan.index') }}" class="{{ request()->routeIs('superadmin.penerimaan.*') ? 'active' : '' }}">
                <i class="fas fa-inbox"></i> Penerimaan
            </a>
            <a href="{{ route('superadmin.penjualan.index') }}" class="{{ request()->routeIs('superadmin.penjualan.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> Penjualan
            </a>
            <a href="{{ route('superadmin.margin.index') }}" class="{{ request()->routeIs('superadmin.margin.*') ? 'active' : '' }}">
                <i class="fas fa-percentage"></i> Margin Penjualan
            </a>
            <a href="{{ route('superadmin.retur.index') }}" class="{{ request()->routeIs('superadmin.retur.*') ? 'active' : '' }}">
                <i class="fas fa-undo"></i> Retur
            </a>
            <a href="{{ route('superadmin.kartuStok.index') }}" class="{{ request()->routeIs('superadmin.kartuStok.*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i> Kartu Stok
            </a>
        </div>
        
        <div class="sidebar-divider"></div>
        
        <div class="logout-btn">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="page-title">
                <h3><i class="fas {{ $pageIcon ?? 'fa-home' }}"></i>{{ $pageTitle ?? 'Dashboard' }}</h3>
            </div>
            <div class="user-info">
                <div class="text-end">
                    <div class="fw-bold">{{ Session::get('user_name', 'Administrator') }}</div>
                    <small class="text-muted">{{ Session::get('user_role_name', 'SuperAdmin') }}</small>
                </div>
                <div class="user-badge">
                    <i class="fas fa-crown"></i>SuperAdmin
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')

            <!-- Footer -->
            <div class="text-center mt-5">
                <small><i class="fas fa-copyright me-1"></i>2024 Sistem Inventory. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>