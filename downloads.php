<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get downloads
$downloads = fetchAll("SELECT * FROM downloads WHERE is_active = 1 ORDER BY created_at DESC");

$page_title = 'Download - ' . ($settings['school_name'] ?? 'School Website');
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
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Download</h1>
            
            <?php if (!empty($downloads)): ?>
            <div class="max-w-4xl mx-auto space-y-4">
                <?php foreach ($downloads as $download): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo sanitize($download['title']); ?></h3>
                            <?php if ($download['description']): ?>
                            <p class="text-gray-600 mb-4"><?php echo sanitize($download['description']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center text-sm text-gray-600 space-x-4">
                                <?php if ($download['file_type']): ?>
                                <span><i class="fas fa-file"></i> <?php echo strtoupper(sanitize($download['file_type'])); ?></span>
                                <?php endif; ?>
                                <?php if ($download['file_size']): ?>
                                <span><i class="fas fa-hdd"></i> <?php echo sanitize($download['file_size']); ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-download"></i> <?php echo $download['download_count']; ?> downloads</span>
                            </div>
                        </div>
                        <a href="<?php echo UPLOAD_URL . '/' . sanitize($download['file_path']); ?>" 
                           download
                           class="ml-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Belum ada file yang tersedia untuk diunduh.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
