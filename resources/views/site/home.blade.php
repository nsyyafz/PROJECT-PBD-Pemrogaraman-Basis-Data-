<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventory - Project Basis Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-warehouse text-blue-600 text-2xl"></i>
                    <span class="text-xl font-bold text-gray-800">Sistem Inventory</span>
                </div>
                <a href="/login" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white pt-32 pb-20 fade-in">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto">
                <i class="fas fa-boxes text-6xl mb-6 opacity-90"></i>
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    Sistem Manajemen Inventory
                </h1>
                <p class="text-lg md:text-xl mb-8 text-blue-100">
                    Aplikasi berbasis web untuk mengelola data barang, vendor, transaksi pembelian dan penjualan
                </p>
                <a href="/login" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Masuk ke Sistem <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Fitur Sistem</h2>
                <p class="text-gray-600">Kelola seluruh data inventory dengan mudah</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-blue-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-box text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Data Barang</h3>
                    <p class="text-gray-600">Kelola master data barang dan satuan dengan lengkap</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-green-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-truck text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Vendor & Pengadaan</h3>
                    <p class="text-gray-600">Manajemen vendor dan transaksi pembelian barang</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-purple-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shopping-cart text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Penjualan</h3>
                    <p class="text-gray-600">Catat transaksi penjualan dan hitung margin</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-yellow-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Kartu Stok</h3>
                    <p class="text-gray-600">Pantau pergerakan stok barang secara real-time</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-red-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-undo text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Retur</h3>
                    <p class="text-gray-600">Kelola retur barang ke vendor dengan mudah</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="bg-indigo-100 w-14 h-14 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">User Management</h3>
                    <p class="text-gray-600">Kelola pengguna dan hak akses sistem</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">Teknologi yang Digunakan</h2>
                    <p class="text-gray-600">Dibangun dengan teknologi modern dan reliable</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <div class="flex items-start space-x-4">
                            <i class="fas fa-server text-blue-600 text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Backend</h4>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• Laravel Framework</li>
                                    <li>• MySQL Database</li>
                                    <li>• Stored Procedure & Trigger</li>
                                    <li>• Database Views & Functions</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <div class="flex items-start space-x-4">
                            <i class="fas fa-palette text-purple-600 text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Frontend</h4>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• HTML, CSS, JavaScript</li>
                                    <li>• Tailwind CSS</li>
                                    <li>• Responsive Design</li>
                                    <li>• Interactive UI/UX</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Project Basis Data</h4>
                            <p class="text-gray-600 text-sm">
                                Sistem ini merupakan project mata kuliah Pemrograman Basis Data yang menerapkan 
                                konsep DDL, DML, Stored Procedure, Trigger, Function, dan View dalam implementasi 
                                aplikasi web.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Mulai Gunakan Sistem</h2>
            <p class="text-lg text-blue-100 mb-8">Login untuk mengakses fitur-fitur manajemen inventory</p>
            <a href="/login" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Login Sekarang <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center space-x-2 mb-3">
                <i class="fas fa-warehouse text-blue-500 text-xl"></i>
                <span class="text-lg font-semibold text-white">Sistem Inventory</span>
            </div>
            <p class="text-sm">Project Pemrograman Basis Data - D4 Teknik Informatika</p>
            <p class="text-sm mt-2">&copy; 2024 All rights reserved</p>
        </div>
    </footer>

</body>
</html>