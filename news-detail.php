<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get slug from URL
$slug = isset($_GET['slug']) ? escapeString($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: news.php');
    exit;
}

// Get news details
$news = fetchOne("SELECT * FROM news WHERE slug = '$slug' AND is_published = 1");

if (!$news) {
    header('Location: news.php');
    exit;
}

// Update views
query("UPDATE news SET views = views + 1 WHERE id = {$news['id']}");

$page_title = sanitize($news['title']) . ' - ' . ($settings['school_name'] ?? 'School Website');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <?php if ($news['image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($news['image']); ?>" 
                         alt="<?php echo sanitize($news['title']); ?>" 
                         class="w-full h-96 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-8">
                        <h1 class="text-4xl font-bold mb-4 text-gray-800"><?php echo sanitize($news['title']); ?></h1>
                        
                        <div class="flex items-center text-gray-600 text-sm mb-6 space-x-4">
                            <span><i class="far fa-calendar"></i> <?php echo formatDate($news['published_at'], 'd F Y'); ?></span>
                            <?php if ($news['author']): ?>
                            <span><i class="far fa-user"></i> <?php echo sanitize($news['author']); ?></span>
                            <?php endif; ?>
                            <span><i class="far fa-eye"></i> <?php echo $news['views']; ?> views</span>
                        </div>
                        
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <?php echo nl2br($news['content']); ?>
                        </div>
                    </div>
                </article>
                
                <div class="mt-8 text-center">
                    <a href="news.php" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Berita
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
