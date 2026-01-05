<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get category from URL
$category = isset($_GET['category']) ? escapeString($_GET['category']) : 'siswa';

// Validate category
if (!in_array($category, ['siswa', 'guru', 'sekolah'])) {
    $category = 'siswa';
}

// Get achievements
$achievements = fetchAll("SELECT * FROM achievements WHERE category = '$category' AND is_active = 1 ORDER BY achievement_date DESC");

$titles = [
    'siswa' => 'Prestasi Siswa',
    'guru' => 'Prestasi Guru',
    'sekolah' => 'Prestasi Sekolah'
];

$page_title = $titles[$category] . ' - ' . ($settings['school_name'] ?? 'School Website');
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
            <h1 class="text-4xl font-bold text-center mb-12 text-gray-800"><?php echo $titles[$category]; ?></h1>
            
            <?php if (!empty($achievements)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($achievements as $achievement): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if ($achievement['image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($achievement['image']); ?>" 
                         alt="<?php echo sanitize($achievement['title']); ?>" 
                         class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo sanitize($achievement['title']); ?></h3>
                        <?php if ($achievement['level']): ?>
                        <p class="text-blue-600 font-semibold mb-2"><?php echo sanitize($achievement['level']); ?></p>
                        <?php endif; ?>
                        <?php if ($achievement['achievement_date']): ?>
                        <p class="text-gray-600 text-sm mb-4">
                            <i class="far fa-calendar"></i> <?php echo formatDate($achievement['achievement_date']); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($achievement['description']): ?>
                        <p class="text-gray-700"><?php echo sanitize($achievement['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Belum ada prestasi yang dipublikasikan.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
