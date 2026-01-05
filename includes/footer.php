<?php
if (!isset($settings)) {
    require_once __DIR__ . '/../includes/db.php';
    $settings = fetchOne("SELECT * FROM settings WHERE id = 1");
}
?>
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- School Info -->
            <div>
                <h3 class="text-xl font-bold mb-4"><?php echo sanitize($settings['school_name'] ?? 'School Website'); ?></h3>
                <?php if (!empty($settings['address'])): ?>
                <p class="text-gray-300 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i><?php echo sanitize($settings['address']); ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($settings['phone'])): ?>
                <p class="text-gray-300 mb-2">
                    <i class="fas fa-phone mr-2"></i><?php echo sanitize($settings['phone']); ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($settings['email'])): ?>
                <p class="text-gray-300 mb-2">
                    <i class="fas fa-envelope mr-2"></i><?php echo sanitize($settings['email']); ?>
                </p>
                <?php endif; ?>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-xl font-bold mb-4">Tautan Cepat</h3>
                <ul class="space-y-2">
                    <li><a href="<?php echo BASE_URL; ?>/profile.php?type=visi_misi" class="text-gray-300 hover:text-white">Visi Misi</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/news.php" class="text-gray-300 hover:text-white">Berita Sekolah</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/gallery.php?type=photo" class="text-gray-300 hover:text-white">Galeri</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/achievements.php?category=siswa" class="text-gray-300 hover:text-white">Prestasi</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact.php" class="text-gray-300 hover:text-white">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div>
                <h3 class="text-xl font-bold mb-4">Ikuti Kami</h3>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center hover:bg-red-700">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> <?php echo sanitize($settings['school_name'] ?? 'School Website'); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>
