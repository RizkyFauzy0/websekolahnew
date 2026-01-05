<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Link Aplikasi';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$link = null;

if ($id > 0) {
    $link = fetchOne("SELECT * FROM links WHERE id = $id");
    if (!$link) {
        header('Location: links.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $url = escapeString($_POST['url']);
    $description = escapeString($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $icon_path = $link['icon'] ?? '';
    
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['icon'], 'links', ['jpg', 'jpeg', 'png', 'gif', 'svg']);
        if ($upload['success']) {
            if ($icon_path) deleteFile($icon_path);
            $icon_path = $upload['filepath'];
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE links SET title = '$title', url = '$url', description = '$description', 
                icon = '$icon_path', sort_order = $sort_order, is_active = $is_active WHERE id = $id";
    } else {
        $sql = "INSERT INTO links (title, url, description, icon, sort_order, is_active) 
                VALUES ('$title', '$url', '$description', '$icon_path', $sort_order, $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Link berhasil disimpan', 'success');
        header('Location: links.php');
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="links.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Judul Link *</label>
                <input type="text" name="title" value="<?php echo sanitize($link['title'] ?? ''); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">URL *</label>
                <input type="url" name="url" value="<?php echo sanitize($link['url'] ?? ''); ?>" required
                       placeholder="https://example.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($link['description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Icon (Opsional)</label>
                <?php if ($link && $link['icon']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($link['icon']); ?>" class="h-20 w-20 object-contain">
                </div>
                <?php endif; ?>
                <input type="file" name="icon" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Upload icon/logo aplikasi (PNG/JPG/SVG)</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan</label>
                <input type="number" name="sort_order" value="<?php echo sanitize($link['sort_order'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" <?php echo (!$link || $link['is_active']) ? 'checked' : ''; ?> class="mr-2">
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
