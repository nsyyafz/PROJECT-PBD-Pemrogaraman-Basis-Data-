<aside class="admin-sidebar" id="admin-sidebar">
    <div class="sidebar-content" style="padding-bottom: 5rem;">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('superadmin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <small class="text-muted px-3 text-uppercase fw-bold">Master Data</small>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.user.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.user.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.role.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.role.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Manajemen Role</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.barang.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.barang.index') }}">
                        <i class="bi bi-box"></i>
                        <span>Data Barang</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.satuan.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.satuan.index') }}">
                        <i class="bi bi-rulers"></i>
                        <span>Satuan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.vendor.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.vendor.index') }}">
                        <i class="bi bi-truck"></i>
                        <span>Vendor</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.margin.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.margin.index') }}">
                        <i class="bi bi-percent"></i>
                        <span>Margin Penjualan</span>
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <small class="text-muted px-3 text-uppercase fw-bold">Transaksi</small>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.pengadaan.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.pengadaan.index') }}">
                        <i class="bi bi-cart-plus"></i>
                        <span>Pengadaan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.penerimaan.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.penerimaan.index') }}">
                        <i class="bi bi-inbox"></i>
                        <span>Penerimaan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.penjualan.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.penjualan.index') }}">
                        <i class="bi bi-cart-check"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
                
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.retur.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.retur.index') }}">
                        <i class="bi bi-arrow-return-left"></i>
                        <span>Retur</span>
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <small class="text-muted px-3 text-uppercase fw-bold">Laporan</small>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.kartu-stok.*') ? 'active' : '' }}" 
                       href="{{ route('superadmin.kartu-stok.index') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Kartu Stok</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>