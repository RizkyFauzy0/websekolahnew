<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Slider';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slider = null;

if ($id > 0) {
    $slider = fetchOne("SELECT * FROM sliders WHERE id = $id");
    if (!$slider) {
        header('Location: sliders.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $description = escapeString($_POST['description']);
    $sort_order = (int)$_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image_path = $slider['image'] ?? '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], 'sliders', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            // Delete old image
            if ($image_path) {
                deleteFile($image_path);
            }
            $image_path = $upload['filepath'];
        } else {
            setFlashMessage($upload['message'], 'error');
            header('Location: sliders-edit.php' . ($id > 0 ? '?id=' . $id : ''));
            exit;
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE sliders SET 
                title = '$title',
                description = '$description',
                image = '$image_path',
                sort_order = $sort_order,
                is_active = $is_active
                WHERE id = $id";
    } else {
        // Insert
        if (empty($image_path)) {
            setFlashMessage('Gambar slider harus diupload', 'error');
            header('Location: sliders-edit.php');
            exit;
        }
        
        $sql = "INSERT INTO sliders (title, description, image, sort_order, is_active) 
                VALUES ('$title', '$description', '$image_path', $sort_order, $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Slider berhasil disimpan', 'success');
        header('Location: sliders.php');
        exit;
    } else {
        setFlashMessage('Gagal menyimpan slider', 'error');
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="sliders.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Slider
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Judul Slider</label>
                <input type="text" 
                       name="title" 
                       value="<?php echo sanitize($slider['title'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($slider['description'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Gambar Slider <?php echo $id === 0 ? '(Wajib)' : '(Opsional)'; ?></label>
                <?php if ($slider && $slider['image']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($slider['image']); ?>" 
                         alt="Current" 
                         class="h-32 rounded">
                </div>
                <?php endif; ?>
                <input type="file" 
                       name="image" 
                       accept="image/*"
                       <?php echo $id === 0 ? 'required' : ''; ?>
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Format: JPG, PNG, GIF (Resolusi optimal: 1920x1080px)</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan</label>
                <input type="number" 
                       name="sort_order" 
                       value="<?php echo sanitize($slider['sort_order'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           <?php echo (!$slider || $slider['is_active']) ? 'checked' : ''; ?>
                           class="mr-2">
                    <span class="text-gray-700 font-semibold">Aktifkan Slider</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Slider
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
