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
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
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
            padding: 25px 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
            color: white;
        }
        .sidebar-header h4 {
            margin: 10px 0 0 0;
            font-weight: 700;
        }
        .sidebar-menu {
            padding: 20px 0;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }
        .sidebar-menu a i {
            width: 24px;
            margin-right: 12px;
            font-size: 16px;
        }
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 15px 20px;
        }
        .logout-btn {
            margin: 10px 20px 20px 20px;
        }
        .logout-btn form button {
            width: 100%;
            padding: 12px;
            background: rgba(255, 107, 107, 0.2);
            border: 2px solid rgba(255, 107, 107, 0.5);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .logout-btn form button:hover {
            background: #ff6b6b;
            border-color: #ff6b6b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-navbar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title h3 {
            margin: 0;
            color: #2d3748;
            font-weight: 700;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }
        .content-area {
            padding: 30px;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }
        .stat-card .card-body {
            padding: 25px;
        }
        .stat-card h6 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .stat-card h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }
        .stat-card i {
            opacity: 0.5;
        }
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
        }
        .welcome-card h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-warehouse fa-3x"></i>
            <h4>Inventory System</h4>
            <small class="opacity-75">SuperAdmin Panel</small>
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
            <a href="{{ route('superadmin.laporan.penjualan') }}" class="{{ request()->routeIs('superadmin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Laporan Penjualan
            </a>
        </div>
        
        <div class="sidebar-divider"></div>
        
        <div class="logout-btn">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="page-title">
                <h3><i class="fas {{ $pageIcon ?? 'fa-home' }} me-2 text-primary"></i>{{ $pageTitle ?? 'Dashboard' }}</h3>
            </div>
            <div class="user-info">
                <div class="text-end">
                    <div class="fw-bold">{{ Session::get('username', 'Manajemen') }}</div>
                    <small class="text-muted">{{ Session::get('role', 'SuperAdmin') }}</small>
                </div>
                <div class="user-badge">
                    <i class="fas fa-crown me-2"></i>SuperAdmin
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
            <div class="text-center mt-5 text-muted">
                <small><i class="fas fa-copyright me-1"></i>2024 Sistem Inventory. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>