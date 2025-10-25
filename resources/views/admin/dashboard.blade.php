<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
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
        .sidebar-menu a,
        .sidebar-menu button {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            border-left: 4px solid transparent;
            cursor: pointer;
            font-size: 16px;
            font-family: inherit;
        }
        .sidebar-menu a:hover,
        .sidebar-menu button:hover {
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
        .sidebar-menu a i,
        .sidebar-menu button i {
            width: 24px;
            margin-right: 12px;
            font-size: 16px;
        }
        .logout-btn {
            color: #ff6b6b !important;
        }
        .logout-btn:hover {
            color: white !important;
            background: rgba(255, 107, 107, 0.2) !important;
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
            <small class="opacity-75">Admin Panel</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="#">
                <i class="fas fa-boxes"></i> Data Barang
            </a>
            <a href="#">
                <i class="fas fa-balance-scale"></i> Satuan
            </a>
            <a href="#">
                <i class="fas fa-truck"></i> Vendor
            </a>
            <a href="#">
                <i class="fas fa-shopping-cart"></i> Pengadaan
            </a>
            <a href="#">
                <i class="fas fa-inbox"></i> Penerimaan
            </a>
            <a href="#">
                <i class="fas fa-cash-register"></i> Penjualan
            </a>
            <a href="#">
                <i class="fas fa-undo"></i> Retur
            </a>
            <a href="#">
                <i class="fas fa-warehouse"></i> Kartu Stok
            </a>
            <a href="#">
                <i class="fas fa-chart-line"></i> Laporan
            </a>
            <div style="height: 20px;"></div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="page-title">
                <h3><i class="fas fa-chart-pie me-2 text-success"></i>Dashboard Admin</h3>
            </div>
            <div class="user-info">
                <div class="text-end">
                    <div class="fw-bold">Manajemen</div>
                    <small class="text-muted">{{ Session::get('role') }}</small>
                </div>
                <div class="user-badge">
                    <i class="fas fa-user-shield me-2"></i>Admin
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

            <!-- Welcome Card -->
            <div class="welcome-card">
                <h2>👋 Selamat Datang, Admin!</h2>
                <p class="mb-0 opacity-90">Berikut adalah ringkasan data operasional sistem inventory</p>
            </div>

            <!-- Stats Row 1 -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card card text-white bg-success shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Barang</h6>
                                    <h2>{{ $stats['barang'] }}</h2>
                                </div>
                                <i class="fas fa-boxes fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card text-white bg-warning shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Vendor</h6>
                                    <h2>{{ $stats['vendor'] }}</h2>
                                </div>
                                <i class="fas fa-truck fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card text-white bg-info shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Pengadaan</h6>
                                    <h2>{{ $stats['pengadaan'] }}</h2>
                                </div>
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card text-white bg-dark shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Penerimaan</h6>
                                    <h2>{{ $stats['penerimaan'] }}</h2>
                                </div>
                                <i class="fas fa-inbox fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Row 2 -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card card text-white bg-danger shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Penjualan</h6>
                                    <h2>{{ $stats['penjualan'] }}</h2>
                                </div>
                                <i class="fas fa-cash-register fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card text-white shadow-sm" style="background: #ff6b6b;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="opacity-75">Total Retur</h6>
                                    <h2>{{ $stats['retur'] }}</h2>
                                </div>
                                <i class="fas fa-undo fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Total Satuan</h6>
                                    <h2 class="text-primary">{{ $stats['satuan'] }}</h2>
                                </div>
                                <i class="fas fa-balance-scale fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Total Kartu Stok</h6>
                                    <h2 class="text-info">{{ $stats['kartu_stok'] }}</h2>
                                </div>
                                <i class="fas fa-warehouse fa-3x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4CAF50, #45a049);">
                        <div class="card-body text-white p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-2"><i class="fas fa-info-circle me-2"></i>Informasi Akses</h4>
                                    <p class="mb-0 opacity-90">Anda login sebagai <strong>Admin</strong>. Anda memiliki akses ke data operasional sistem inventory, namun tidak dapat mengakses manajemen user dan role.</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-user-shield fa-5x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-5 text-muted">
                <small><i class="fas fa-copyright me-1"></i>2024 Sistem Inventory. All rights reserved.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>