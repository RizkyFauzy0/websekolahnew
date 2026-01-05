<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Download';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$download = null;

if ($id > 0) {
    $download = fetchOne("SELECT * FROM downloads WHERE id = $id");
    if (!$download) {
        header('Location: downloads.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $description = escapeString($_POST['description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $file_path = $download['file_path'] ?? '';
    $file_size = $download['file_size'] ?? '';
    $file_type = $download['file_type'] ?? '';
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = UPLOAD_PATH . '/downloads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '_' . time() . '.' . $file_ext;
        $filepath = $upload_dir . '/' . $filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            if ($file_path) deleteFile($file_path);
            $file_path = 'downloads/' . $filename;
            $file_size = round($_FILES['file']['size'] / 1024, 2) . ' KB';
            if ($_FILES['file']['size'] > 1024 * 1024) {
                $file_size = round($_FILES['file']['size'] / (1024 * 1024), 2) . ' MB';
            }
            $file_type = $file_ext;
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE downloads SET title = '$title', description = '$description', 
                file_path = '$file_path', file_size = '$file_size', file_type = '$file_type',
                is_active = $is_active WHERE id = $id";
    } else {
        if (empty($file_path)) {
            setFlashMessage('File harus diupload', 'error');
            header('Location: downloads-edit.php');
            exit;
        }
        $sql = "INSERT INTO downloads (title, description, file_path, file_size, file_type, is_active) 
                VALUES ('$title', '$description', '$file_path', '$file_size', '$file_type', $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Download berhasil disimpan', 'success');
        header('Location: downloads.php');
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="downloads.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Judul File *</label>
                <input type="text" name="title" value="<?php echo sanitize($download['title'] ?? ''); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($download['description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">File <?php echo $id === 0 ? '(Wajib)' : '(Opsional)'; ?></label>
                <?php if ($download && $download['file_path']): ?>
                <div class="mb-2">
                    <p class="text-sm text-gray-600">
                        File saat ini: <strong><?php echo basename($download['file_path']); ?></strong>
                        (<?php echo sanitize($download['file_size']); ?>)
                    </p>
                </div>
                <?php endif; ?>
                <input type="file" name="file" <?php echo $id === 0 ? 'required' : ''; ?>
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Semua jenis file diperbolehkan (PDF, DOC, XLS, ZIP, dll)</p>
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" <?php echo (!$download || $download['is_active']) ? 'checked' : ''; ?> class="mr-2">
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
