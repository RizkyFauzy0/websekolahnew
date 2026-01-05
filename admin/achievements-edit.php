<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Prestasi';

$category = isset($_GET['category']) ? escapeString($_GET['category']) : 'siswa';
if (!in_array($category, ['siswa', 'guru', 'sekolah'])) $category = 'siswa';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$achievement = null;

if ($id > 0) {
    $achievement = fetchOne("SELECT * FROM achievements WHERE id = $id");
    if (!$achievement) {
        header('Location: achievements.php?category=' . $category);
        exit;
    }
    $category = $achievement['category'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $description = escapeString($_POST['description']);
    $level = escapeString($_POST['level']);
    $achievement_date = escapeString($_POST['achievement_date']);
    $sort_order = (int)$_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $image_path = $achievement['image'] ?? '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], 'achievements', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            if ($image_path) deleteFile($image_path);
            $image_path = $upload['filepath'];
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE achievements SET title = '$title', description = '$description', level = '$level',
                achievement_date = '$achievement_date', image = '$image_path', 
                sort_order = $sort_order, is_active = $is_active WHERE id = $id";
    } else {
        $sql = "INSERT INTO achievements (title, description, category, level, achievement_date, image, sort_order, is_active) 
                VALUES ('$title', '$description', '$category', '$level', '$achievement_date', '$image_path', $sort_order, $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Prestasi berhasil disimpan', 'success');
        header('Location: achievements.php?category=' . $category);
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="achievements.php?category=<?php echo $category; ?>" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Judul Prestasi *</label>
                <input type="text" name="title" value="<?php echo sanitize($achievement['title'] ?? ''); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($achievement['description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tingkat</label>
                <input type="text" name="level" value="<?php echo sanitize($achievement['level'] ?? ''); ?>"
                       placeholder="Contoh: Nasional, Provinsi, Kabupaten"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                <input type="date" name="achievement_date" value="<?php echo sanitize($achievement['achievement_date'] ?? date('Y-m-d')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan</label>
                <input type="number" name="sort_order" value="<?php echo sanitize($achievement['sort_order'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="flex items-center mt-8">
                    <input type="checkbox" name="is_active" <?php echo (!$achievement || $achievement['is_active']) ? 'checked' : ''; ?> class="mr-2">
                    <span class="text-gray-700 font-semibold">Aktifkan</span>
                </label>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Gambar</label>
                <?php if ($achievement && $achievement['image']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($achievement['image']); ?>" class="h-32 rounded">
                </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
