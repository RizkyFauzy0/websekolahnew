<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Guru';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$teacher = null;

if ($id > 0) {
    $teacher = fetchOne("SELECT * FROM teachers WHERE id = $id");
    if (!$teacher) {
        header('Location: teachers.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = escapeString($_POST['name']);
    $subject = escapeString($_POST['subject']);
    $education = escapeString($_POST['education']);
    $email = escapeString($_POST['email']);
    $phone = escapeString($_POST['phone']);
    $sort_order = (int)$_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $photo_path = $teacher['photo'] ?? '';
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['photo'], 'teachers', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            if ($photo_path) deleteFile($photo_path);
            $photo_path = $upload['filepath'];
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE teachers SET 
                name = '$name', subject = '$subject', education = '$education',
                email = '$email', phone = '$phone', photo = '$photo_path',
                sort_order = $sort_order, is_active = $is_active
                WHERE id = $id";
    } else {
        $sql = "INSERT INTO teachers (name, subject, education, email, phone, photo, sort_order, is_active) 
                VALUES ('$name', '$subject', '$education', '$email', '$phone', '$photo_path', $sort_order, $is_active)";
    }
    
    if (query($sql)) {
        setFlashMessage('Guru berhasil disimpan', 'success');
        header('Location: teachers.php');
        exit;
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="teachers.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                <input type="text" name="name" value="<?php echo sanitize($teacher['name'] ?? ''); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran</label>
                <input type="text" name="subject" value="<?php echo sanitize($teacher['subject'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Pendidikan</label>
                <input type="text" name="education" value="<?php echo sanitize($teacher['education'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="<?php echo sanitize($teacher['email'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Telepon</label>
                <input type="text" name="phone" value="<?php echo sanitize($teacher['phone'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan</label>
                <input type="number" name="sort_order" value="<?php echo sanitize($teacher['sort_order'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Foto</label>
                <?php if ($teacher && $teacher['photo']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($teacher['photo']); ?>" class="h-32 rounded">
                </div>
                <?php endif; ?>
                <input type="file" name="photo" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" <?php echo (!$teacher || $teacher['is_active']) ? 'checked' : ''; ?> class="mr-2">
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
