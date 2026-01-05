<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Galeri';

$type = isset($_GET['type']) ? escapeString($_GET['type']) : 'photo';
if (!in_array($type, ['photo', 'video'])) $type = 'photo';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = null;

if ($id > 0) {
    $item = fetchOne("SELECT * FROM gallery WHERE id = $id");
    if (!$item) {
        header('Location: gallery.php?type=' . $type);
        exit;
    }
    $type = $item['type'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $description = escapeString($_POST['description']);
    $video_url = escapeString($_POST['video_url'] ?? '');
    $sort_order = (int)$_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $file_path = $item['file_path'] ?? '';
    
    if ($type === 'photo' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['file'], 'gallery', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            if ($file_path) deleteFile($file_path);
            $file_path = $upload['filepath'];
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE gallery SET title = '$title', description = '$description', 
                file_path = '$file_path', video_url = '$video_url', 
                sort_order = $sort_order, is_active = $is_active WHERE id = $id";
    } else {
        if ($type === 'photo' && empty($file_path)) {
            setFlashMessage('File foto harus diupload', 'error');
            header('Location: gallery-edit.php?type=' . $type);
            exit;
        }
        $sql = "INSERT INTO gallery (title, description, type, file_path, video_url, sort_order, is_active) 
                VALUES ('$title', '$description', '$type', '$file_path', '$video_url', $sort_order, $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Galeri berhasil disimpan', 'success');
        header('Location: gallery.php?type=' . $type);
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="gallery.php?type=<?php echo $type; ?>" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah/Edit <?php echo $type === 'photo' ? 'Foto' : 'Video'; ?></h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Judul *</label>
                <input type="text" name="title" value="<?php echo sanitize($item['title'] ?? ''); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($item['description'] ?? ''); ?></textarea>
            </div>
            
            <?php if ($type === 'photo'): ?>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">File Foto <?php echo $id === 0 ? '*' : '(Opsional)'; ?></label>
                <?php if ($item && $item['file_path']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($item['file_path']); ?>" class="h-32 rounded">
                </div>
                <?php endif; ?>
                <input type="file" name="file" accept="image/*" <?php echo $id === 0 ? 'required' : ''; ?>
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php else: ?>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">URL Video (YouTube/Lainnya) *</label>
                <input type="url" name="video_url" value="<?php echo sanitize($item['video_url'] ?? ''); ?>" required
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php endif; ?>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan</label>
                <input type="number" name="sort_order" value="<?php echo sanitize($item['sort_order'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" <?php echo (!$item || $item['is_active']) ? 'checked' : ''; ?> class="mr-2">
                    <span class="text-gray-700 font-semibold">Aktifkan</span>
                </label>
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
