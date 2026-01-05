<?php
require_once '../includes/auth.php';
requireLogin();

$current_page = basename($_SERVER['PHP_SELF'], '.php');
$user = getCurrentUser();
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
        <aside id="sidebar" class="bg-gray-800 text-white w-64 min-h-screen fixed lg:static transform lg:transform-none transition-transform duration-300 -translate-x-full lg:translate-x-0 z-20">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-2">Admin Panel</h2>
                <p class="text-gray-400 text-sm"><?php echo sanitize($user['full_name']); ?></p>
            </div>
            
            <nav class="mt-6">
                <a href="index.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'index' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
                <a href="settings.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'settings' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-cog mr-3"></i> Pengaturan Sekolah
                </a>
                <a href="sliders.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'sliders' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-images mr-3"></i> Slider
                </a>
                <a href="news.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'news' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-newspaper mr-3"></i> Berita
                </a>
                <a href="teachers.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'teachers' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-chalkboard-teacher mr-3"></i> Guru
                </a>
                <a href="profile.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'profile' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-info-circle mr-3"></i> Profil Sekolah
                </a>
                <a href="gallery.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'gallery' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-image mr-3"></i> Galeri
                </a>
                <a href="achievements.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'achievements' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-trophy mr-3"></i> Prestasi
                </a>
                <a href="downloads.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'downloads' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-download mr-3"></i> Download
                </a>
                <a href="links.php" class="flex items-center px-6 py-3 <?php echo $current_page === 'links' ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                    <i class="fas fa-link mr-3"></i> Link Aplikasi
                </a>
                <a href="logout.php" class="flex items-center px-6 py-3 hover:bg-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 min-h-screen">
            <!-- Top Bar -->
            <header class="bg-white shadow-md p-4 flex items-center justify-between">
                <button id="sidebar-toggle" class="lg:hidden text-gray-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                <a href="<?php echo BASE_URL; ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-external-link-alt mr-2"></i>Lihat Website
                </a>
            </header>
            
            <!-- Content Area -->
            <main class="p-6">
                <?php if ($flash): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $flash['type'] === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 'bg-red-100 text-red-700 border border-red-400'; ?>">
                    <i class="fas <?php echo $flash['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo sanitize($flash['message']); ?>
                </div>
                <?php endif; ?>
                
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>
    
    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden"></div>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    </script>
</body>
</html>
