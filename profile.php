<?php
require_once 'includes/db.php';

$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get type from URL
$type = isset($_GET['type']) ? escapeString($_GET['type']) : 'visi_misi';

// Validate type
$valid_types = ['visi_misi', 'sejarah', 'struktur', 'keunggulan'];
if (!in_array($type, $valid_types)) {
    $type = 'visi_misi';
}

// Get profile data
$profile = fetchOne("SELECT * FROM profile WHERE type = '$type'");

// Set title based on type
$titles = [
    'visi_misi' => 'Visi dan Misi',
    'sejarah' => 'Sejarah Singkat',
    'struktur' => 'Struktur Organisasi',
    'keunggulan' => 'Keunggulan Sekolah'
];

$page_title = $titles[$type] . ' - ' . ($settings['school_name'] ?? 'School Website');
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
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl font-bold text-center mb-12 text-gray-800"><?php echo $titles[$type]; ?></h1>
                
                <?php if ($profile): ?>
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <?php if ($profile['image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($profile['image']); ?>" 
                         alt="<?php echo sanitize($profile['title']); ?>" 
                         class="w-full mb-8 rounded-lg">
                    <?php endif; ?>
                    
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        <?php echo $profile['content']; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <p class="text-gray-600">Konten belum tersedia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
