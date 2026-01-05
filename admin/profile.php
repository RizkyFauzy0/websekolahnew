<?php
require_once '../includes/db.php';

$page_title = 'Kelola Profil Sekolah';

// Get all profile types
$profiles = [];
$types = ['visi_misi', 'sejarah', 'struktur', 'keunggulan'];
$titles = [
    'visi_misi' => 'Visi dan Misi',
    'sejarah' => 'Sejarah Singkat',
    'struktur' => 'Struktur Organisasi',
    'keunggulan' => 'Keunggulan Sekolah'
];

foreach ($types as $type) {
    $profile = fetchOne("SELECT * FROM profile WHERE type = '$type'");
    $profiles[$type] = $profile ?: ['type' => $type, 'title' => $titles[$type], 'content' => '', 'image' => ''];
}

ob_start();
?>

<div class="space-y-6">
    <?php foreach ($profiles as $type => $profile): ?>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800"><?php echo $titles[$type]; ?></h2>
            <a href="profile-edit.php?type=<?php echo $type; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
        
        <?php if ($profile['image']): ?>
        <div class="mb-4">
            <img src="<?php echo UPLOAD_URL . '/' . sanitize($profile['image']); ?>" 
                 alt="<?php echo sanitize($profile['title']); ?>" 
                 class="h-32 rounded">
        </div>
        <?php endif; ?>
        
        <div class="prose max-w-none text-gray-700">
            <?php echo $profile['content'] ? substr(strip_tags($profile['content']), 0, 300) . '...' : '<em class="text-gray-500">Belum ada konten</em>'; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
