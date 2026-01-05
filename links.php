<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get links
$links = fetchAll("SELECT * FROM links WHERE is_active = 1 ORDER BY sort_order ASC");

$page_title = 'Link Aplikasi - ' . ($settings['school_name'] ?? 'School Website');
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
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Link Aplikasi</h1>
            
            <?php if (!empty($links)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <?php foreach ($links as $link): ?>
                <a href="<?php echo sanitize($link['url']); ?>" 
                   target="_blank"
                   class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow block text-center">
                    <?php if ($link['icon']): ?>
                    <div class="mb-4">
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($link['icon']); ?>" 
                             alt="<?php echo sanitize($link['title']); ?>" 
                             class="w-20 h-20 mx-auto object-contain">
                    </div>
                    <?php else: ?>
                    <div class="mb-4">
                        <i class="fas fa-link text-5xl text-blue-600"></i>
                    </div>
                    <?php endif; ?>
                    
                    <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo sanitize($link['title']); ?></h3>
                    <?php if ($link['description']): ?>
                    <p class="text-gray-600 mb-4"><?php echo sanitize($link['description']); ?></p>
                    <?php endif; ?>
                    <span class="text-blue-600 font-semibold">
                        Kunjungi <i class="fas fa-external-link-alt ml-1"></i>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Belum ada link aplikasi yang tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
