<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

$page_title = 'Kontak - ' . ($settings['school_name'] ?? 'School Website');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($page_title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Hubungi Kami</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
                <div>
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Informasi Kontak</h2>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Alamat</h3>
                                    <p class="text-gray-600"><?php echo sanitize($settings['address'] ?? ''); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-phone text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Telepon</h3>
                                    <p class="text-gray-600"><?php echo sanitize($settings['phone'] ?? ''); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                    <p class="text-gray-600"><?php echo sanitize($settings['email'] ?? ''); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-globe text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800 mb-1">Website</h3>
                                    <p class="text-gray-600"><?php echo sanitize($settings['website'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="font-semibold text-gray-800 mb-4">Ikuti Kami</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 text-white">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 text-white">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center hover:bg-red-700 text-white">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 text-white">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <?php if (!empty($settings['maps_embed'])): ?>
                    <div class="w-full h-full min-h-[500px] rounded-lg overflow-hidden shadow-lg">
                        <?php echo $settings['maps_embed']; ?>
                    </div>
                    <?php else: ?>
                    <div class="w-full h-full min-h-[500px] bg-gray-200 rounded-lg flex items-center justify-center shadow-lg">
                        <p class="text-gray-500">Maps belum dikonfigurasi</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
