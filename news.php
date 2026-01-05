<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");
$page_title = 'Berita - ' . ($settings['school_name'] ?? 'School Website');

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Get total news count
$total_result = fetchOne("SELECT COUNT(*) as total FROM news WHERE is_published = 1");
$total_news = $total_result['total'];
$total_pages = ceil($total_news / $per_page);

// Get news
$news_list = fetchAll("SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT $per_page OFFSET $offset");
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
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Berita Sekolah</h1>
            
            <?php if (!empty($news_list)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($news_list as $news): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if ($news['image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($news['image']); ?>" 
                         alt="<?php echo sanitize($news['title']); ?>" 
                         class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2 text-gray-800">
                            <a href="news-detail.php?slug=<?php echo sanitize($news['slug']); ?>" class="hover:text-blue-600">
                                <?php echo sanitize($news['title']); ?>
                            </a>
                        </h2>
                        <p class="text-gray-600 text-sm mb-4">
                            <i class="far fa-calendar"></i> <?php echo formatDate($news['published_at']); ?>
                        </p>
                        <?php if ($news['excerpt']): ?>
                        <p class="text-gray-700 mb-4"><?php echo sanitize(substr($news['excerpt'], 0, 150)) . '...'; ?></p>
                        <?php endif; ?>
                        <a href="news-detail.php?slug=<?php echo sanitize($news['slug']); ?>" 
                           class="text-blue-600 hover:text-blue-800 font-semibold">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center mt-12">
                <nav class="flex space-x-2">
                    <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-blue-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       class="px-4 py-2 border rounded-lg <?php echo $i === $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 hover:bg-blue-50'; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-blue-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Belum ada berita yang dipublikasikan.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
