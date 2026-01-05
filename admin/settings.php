<?php
require_once '../includes/db.php';

$page_title = 'Pengaturan Sekolah';

// Get current settings
$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_name = escapeString($_POST['school_name']);
    $address = escapeString($_POST['address']);
    $phone = escapeString($_POST['phone']);
    $email = escapeString($_POST['email']);
    $website = escapeString($_POST['website']);
    $maps_embed = escapeString($_POST['maps_embed']);
    $student_count = (int)$_POST['student_count'];
    
    // Handle logo upload
    $logo_path = $settings['school_logo'];
    if (isset($_FILES['school_logo']) && $_FILES['school_logo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['school_logo'], 'settings', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            // Delete old logo
            if ($logo_path) {
                deleteFile($logo_path);
            }
            $logo_path = $upload['filepath'];
        }
    }
    
    $sql = "UPDATE settings SET 
            school_name = '$school_name',
            school_logo = '$logo_path',
            address = '$address',
            phone = '$phone',
            email = '$email',
            website = '$website',
            maps_embed = '$maps_embed',
            student_count = $student_count
            WHERE id = 1";
    
    if (query($sql)) {
        setFlashMessage('Pengaturan berhasil disimpan', 'success');
        header('Location: settings.php');
        exit;
    } else {
        setFlashMessage('Gagal menyimpan pengaturan', 'error');
    }
}

ob_start();
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Nama Sekolah</label>
                <input type="text" 
                       name="school_name" 
                       value="<?php echo sanitize($settings['school_name'] ?? ''); ?>"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Logo Sekolah</label>
                <?php if ($settings['school_logo']): ?>
                <div class="mb-2">
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($settings['school_logo']); ?>" 
                         alt="Logo" 
                         class="h-20">
                </div>
                <?php endif; ?>
                <input type="file" 
                       name="school_logo" 
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Format: JPG, PNG, GIF (Max 2MB)</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea name="address" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($settings['address'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Telepon</label>
                <input type="text" 
                       name="phone" 
                       value="<?php echo sanitize($settings['phone'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" 
                       name="email" 
                       value="<?php echo sanitize($settings['email'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Website</label>
                <input type="text" 
                       name="website" 
                       value="<?php echo sanitize($settings['website'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Jumlah Siswa</label>
                <input type="number" 
                       name="student_count" 
                       value="<?php echo sanitize($settings['student_count'] ?? '0'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Google Maps Embed Code</label>
                <textarea name="maps_embed" 
                          rows="4"
                          placeholder='<iframe src="..." width="600" height="450"...></iframe>'
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($settings['maps_embed'] ?? ''); ?></textarea>
                <p class="text-sm text-gray-600 mt-1">Paste embed iframe code dari Google Maps</p>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
