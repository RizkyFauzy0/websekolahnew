<?php
require_once '../includes/db.php';

$page_title = 'Edit Profil Sekolah';

$type = isset($_GET['type']) ? escapeString($_GET['type']) : 'visi_misi';
$valid_types = ['visi_misi', 'sejarah', 'struktur', 'keunggulan'];

if (!in_array($type, $valid_types)) {
    header('Location: profile.php');
    exit;
}

$titles = [
    'visi_misi' => 'Visi dan Misi',
    'sejarah' => 'Sejarah Singkat',
    'struktur' => 'Struktur Organisasi',
    'keunggulan' => 'Keunggulan Sekolah'
];

$profile = fetchOne("SELECT * FROM profile WHERE type = '$type'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $content = escapeString($_POST['content']);
    
    $image_path = $profile['image'] ?? '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], 'profile', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            if ($image_path) deleteFile($image_path);
            $image_path = $upload['filepath'];
        }
    }
    
    if ($profile) {
        $sql = "UPDATE profile SET title = '$title', content = '$content', image = '$image_path' WHERE type = '$type'";
    } else {
        $sql = "INSERT INTO profile (type, title, content, image) VALUES ('$type', '$title', '$content', '$image_path')";
    }
    
    if (query($sql)) {
        setFlashMessage('Profil berhasil disimpan', 'success');
        header('Location: profile.php');
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="profile.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800"><?php echo $titles[$type]; ?></h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Judul</label>
                <input type="text" name="title" value="<?php echo sanitize($profile['title'] ?? $titles[$type]); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Gambar (Opsional)</label>
                <?php if ($profile && $profile['image']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($profile['image']); ?>" class="h-32 rounded">
                </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Konten (HTML allowed)</label>
                <textarea name="content" rows="15" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"><?php echo sanitize($profile['content'] ?? ''); ?></textarea>
                <p class="text-sm text-gray-600 mt-1">Anda bisa menggunakan tag HTML seperti &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, dll.</p>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Profil
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
