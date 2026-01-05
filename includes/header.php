<?php
if (!isset($settings)) {
    require_once __DIR__ . '/../includes/db.php';
    $settings = fetchOne("SELECT * FROM settings WHERE id = 1");
}
?>
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <!-- Top bar -->
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center space-x-4">
                <?php if (!empty($settings['school_logo'])): ?>
                <img src="<?php echo UPLOAD_URL . '/' . sanitize($settings['school_logo']); ?>" 
                     alt="Logo" class="h-12 md:h-16">
                <?php endif; ?>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                        <?php echo sanitize($settings['school_name'] ?? 'School Website'); ?>
                    </h1>
                    <?php if (!empty($settings['address'])): ?>
                    <p class="text-sm text-gray-600 hidden md:block"><?php echo sanitize($settings['address']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="lg:hidden text-gray-800 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav id="main-nav" class="hidden lg:block border-t border-gray-200">
            <ul class="flex flex-wrap items-center py-4 space-x-6">
                <li><a href="<?php echo BASE_URL; ?>/index.php" class="text-gray-700 hover:text-blue-600 font-semibold">Dashboard</a></li>
                
                <!-- Profil Dropdown -->
                <li class="relative group">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-semibold flex items-center">
                        Profil <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                    <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=visi_misi" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Visi Misi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=sejarah" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Sejarah Singkat</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=struktur" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Struktur Organisasi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=keunggulan" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Keunggulan</a></li>
                    </ul>
                </li>
                
                <li><a href="<?php echo BASE_URL; ?>/news.php" class="text-gray-700 hover:text-blue-600 font-semibold">Berita Sekolah</a></li>
                
                <!-- Galeri Dropdown -->
                <li class="relative group">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-semibold flex items-center">
                        Galeri <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                    <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <li><a href="<?php echo BASE_URL; ?>/gallery.php?type=photo" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Foto</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/gallery.php?type=video" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Video</a></li>
                    </ul>
                </li>
                
                <!-- Prestasi Dropdown -->
                <li class="relative group">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-semibold flex items-center">
                        Prestasi <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </a>
                    <ul class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=siswa" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Prestasi Siswa</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=guru" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Prestasi Guru</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=sekolah" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Prestasi Sekolah</a></li>
                    </ul>
                </li>
                
                <li><a href="<?php echo BASE_URL; ?>/downloads.php" class="text-gray-700 hover:text-blue-600 font-semibold">Download</a></li>
                <li><a href="<?php echo BASE_URL; ?>/links.php" class="text-gray-700 hover:text-blue-600 font-semibold">Link Aplikasi</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact.php" class="text-gray-700 hover:text-blue-600 font-semibold">Kontak</a></li>
            </ul>
        </nav>
        
        <!-- Mobile Navigation -->
        <nav id="mobile-nav" class="lg:hidden hidden border-t border-gray-200 pb-4">
            <ul class="py-4 space-y-2">
                <li><a href="<?php echo BASE_URL; ?>/index.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Dashboard</a></li>
                
                <li>
                    <button onclick="toggleMobileDropdown('profil-menu')" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-between">
                        Profil <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <ul id="profil-menu" class="hidden bg-gray-50 py-2">
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=visi_misi" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Visi Misi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=sejarah" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Sejarah Singkat</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=struktur" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Struktur Organisasi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile.php?type=keunggulan" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Keunggulan</a></li>
                    </ul>
                </li>
                
                <li><a href="<?php echo BASE_URL; ?>/news.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Berita Sekolah</a></li>
                
                <li>
                    <button onclick="toggleMobileDropdown('galeri-menu')" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-between">
                        Galeri <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <ul id="galeri-menu" class="hidden bg-gray-50 py-2">
                        <li><a href="<?php echo BASE_URL; ?>/gallery.php?type=photo" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Foto</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/gallery.php?type=video" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Video</a></li>
                    </ul>
                </li>
                
                <li>
                    <button onclick="toggleMobileDropdown('prestasi-menu')" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-between">
                        Prestasi <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <ul id="prestasi-menu" class="hidden bg-gray-50 py-2">
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=siswa" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Prestasi Siswa</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=guru" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Prestasi Guru</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=sekolah" class="block px-8 py-2 text-gray-700 hover:text-blue-600">Prestasi Sekolah</a></li>
                    </ul>
                </li>
                
                <li><a href="<?php echo BASE_URL; ?>/downloads.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Download</a></li>
                <li><a href="<?php echo BASE_URL; ?>/links.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Link Aplikasi</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact.php" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Kontak</a></li>
            </ul>
        </nav>
    </div>
</header>

<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileNav = document.getElementById('mobile-nav');
        mobileNav.classList.toggle('hidden');
    });

    // Toggle mobile dropdown
    function toggleMobileDropdown(menuId) {
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }
</script>
