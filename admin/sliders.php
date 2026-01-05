<?php
require_once '../includes/db.php';

$page_title = 'Kelola Slider';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $slider = fetchOne("SELECT * FROM sliders WHERE id = $id");
    
    if ($slider) {
        // Delete image file
        if ($slider['image']) {
            deleteFile($slider['image']);
        }
        
        query("DELETE FROM sliders WHERE id = $id");
        setFlashMessage('Slider berhasil dihapus', 'success');
    }
    
    header('Location: sliders.php');
    exit;
}

// Handle Toggle Active
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    query("UPDATE sliders SET is_active = IF(is_active = 1, 0, 1) WHERE id = $id");
    setFlashMessage('Status slider berhasil diubah', 'success');
    header('Location: sliders.php');
    exit;
}

// Get all sliders
$sliders = fetchAll("SELECT * FROM sliders ORDER BY sort_order ASC");

ob_start();
?>

<div class="mb-6">
    <a href="sliders-edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Slider
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($sliders)): ?>
                <?php foreach ($sliders as $slider): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($slider['image']); ?>" 
                             alt="<?php echo sanitize($slider['title']); ?>" 
                             class="h-16 w-24 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo sanitize($slider['title']); ?></div>
                        <?php if ($slider['description']): ?>
                        <div class="text-sm text-gray-500"><?php echo sanitize(substr($slider['description'], 0, 50)) . '...'; ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo $slider['sort_order']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="?toggle=<?php echo $slider['id']; ?>" class="text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $slider['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $slider['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="sliders-edit.php?id=<?php echo $slider['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $slider['id']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus slider ini?')"
                           class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada slider</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
