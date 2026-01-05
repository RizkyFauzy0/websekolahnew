<?php
require_once '../includes/db.php';

$page_title = 'Dashboard';

// Get statistics
$total_news = fetchOne("SELECT COUNT(*) as total FROM news")['total'];
$total_teachers = fetchOne("SELECT COUNT(*) as total FROM teachers")['total'];
$total_achievements = fetchOne("SELECT COUNT(*) as total FROM achievements")['total'];
$total_gallery = fetchOne("SELECT COUNT(*) as total FROM gallery")['total'];

ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Berita</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_news; ?></p>
            </div>
            <div class="bg-blue-100 p-4 rounded-full">
                <i class="fas fa-newspaper text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Guru</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_teachers; ?></p>
            </div>
            <div class="bg-green-100 p-4 rounded-full">
                <i class="fas fa-chalkboard-teacher text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Prestasi</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_achievements; ?></p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-full">
                <i class="fas fa-trophy text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Galeri</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_gallery; ?></p>
            </div>
            <div class="bg-purple-100 p-4 rounded-full">
                <i class="fas fa-image text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Berita Terbaru</h2>
        <?php
        $recent_news = fetchAll("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
        if (!empty($recent_news)):
        ?>
        <div class="space-y-3">
            <?php foreach ($recent_news as $news): ?>
            <div class="flex items-center justify-between py-2 border-b">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800"><?php echo sanitize($news['title']); ?></p>
                    <p class="text-sm text-gray-600"><?php echo formatDate($news['created_at']); ?></p>
                </div>
                <a href="news-edit.php?id=<?php echo $news['id']; ?>" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-600">Belum ada berita.</p>
        <?php endif; ?>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-4">
            <a href="sliders.php" class="bg-blue-50 p-4 rounded-lg text-center hover:bg-blue-100 transition-colors">
                <i class="fas fa-images text-3xl text-blue-600 mb-2"></i>
                <p class="text-sm font-semibold">Kelola Slider</p>
            </a>
            <a href="news-edit.php" class="bg-green-50 p-4 rounded-lg text-center hover:bg-green-100 transition-colors">
                <i class="fas fa-plus text-3xl text-green-600 mb-2"></i>
                <p class="text-sm font-semibold">Tambah Berita</p>
            </a>
            <a href="teachers.php" class="bg-yellow-50 p-4 rounded-lg text-center hover:bg-yellow-100 transition-colors">
                <i class="fas fa-user-plus text-3xl text-yellow-600 mb-2"></i>
                <p class="text-sm font-semibold">Tambah Guru</p>
            </a>
            <a href="settings.php" class="bg-purple-50 p-4 rounded-lg text-center hover:bg-purple-100 transition-colors">
                <i class="fas fa-cog text-3xl text-purple-600 mb-2"></i>
                <p class="text-sm font-semibold">Pengaturan</p>
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
