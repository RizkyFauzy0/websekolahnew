<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get type from URL
$type = isset($_GET['type']) ? escapeString($_GET['type']) : 'photo';

// Validate type
if (!in_array($type, ['photo', 'video'])) {
    $type = 'photo';
}

// Get gallery items
$gallery_items = fetchAll("SELECT * FROM gallery WHERE type = '$type' AND is_active = 1 ORDER BY sort_order ASC");

$page_title = ($type === 'photo' ? 'Galeri Foto' : 'Galeri Video') . ' - ' . ($settings['school_name'] ?? 'School Website');
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
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">
                <?php echo $type === 'photo' ? 'Galeri Foto' : 'Galeri Video'; ?>
            </h1>
            
            <?php if (!empty($gallery_items)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($gallery_items as $item): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if ($type === 'photo'): ?>
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($item['file_path']); ?>" 
                             alt="<?php echo sanitize($item['title']); ?>" 
                             class="w-full h-64 object-cover">
                    <?php else: ?>
                        <?php if (strpos($item['video_url'], 'youtube.com') !== false || strpos($item['video_url'], 'youtu.be') !== false): ?>
                            <?php
                            // Extract YouTube video ID
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $item['video_url'], $matches);
                            $video_id = $matches[1] ?? '';
                            ?>
                            <?php if ($video_id): ?>
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                                        class="w-full h-64" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <video controls class="w-full h-64">
                                <source src="<?php echo sanitize($item['video_url']); ?>">
                            </video>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo sanitize($item['title']); ?></h3>
                        <?php if ($item['description']): ?>
                        <p class="text-gray-600"><?php echo sanitize($item['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Belum ada <?php echo $type === 'photo' ? 'foto' : 'video'; ?> yang dipublikasikan.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
