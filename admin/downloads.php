<?php
require_once '../includes/db.php';

$page_title = 'Kelola Download';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $download = fetchOne("SELECT * FROM downloads WHERE id = $id");
    if ($download) {
        if ($download['file_path']) deleteFile($download['file_path']);
        query("DELETE FROM downloads WHERE id = $id");
        setFlashMessage('File berhasil dihapus', 'success');
    }
    header('Location: downloads.php');
    exit;
}

$downloads = fetchAll("SELECT * FROM downloads ORDER BY created_at DESC");

ob_start();
?>

<div class="mb-6">
    <a href="downloads-edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah File
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Downloads</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($downloads)): ?>
                <?php foreach ($downloads as $download): ?>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo sanitize($download['title']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo strtoupper(sanitize($download['file_type'])); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo sanitize($download['file_size']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo $download['download_count']; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="downloads-edit.php?id=<?php echo $download['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $download['id']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus?')"
                           class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada file</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
