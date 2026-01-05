<?php
require_once '../includes/db.php';

$page_title = 'Kelola Link Aplikasi';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $link = fetchOne("SELECT * FROM links WHERE id = $id");
    if ($link) {
        if ($link['icon']) deleteFile($link['icon']);
        query("DELETE FROM links WHERE id = $id");
        setFlashMessage('Link berhasil dihapus', 'success');
    }
    header('Location: links.php');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    query("UPDATE links SET is_active = IF(is_active = 1, 0, 1) WHERE id = $id");
    setFlashMessage('Status link berhasil diubah', 'success');
    header('Location: links.php');
    exit;
}

$links = fetchAll("SELECT * FROM links ORDER BY sort_order ASC");

ob_start();
?>

<div class="mb-6">
    <a href="links-edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Link
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($links)): ?>
                <?php foreach ($links as $link): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($link['icon']): ?>
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($link['icon']); ?>" 
                             class="h-10 w-10 object-contain">
                        <?php else: ?>
                        <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-link text-gray-400"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo sanitize($link['title']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <a href="<?php echo sanitize($link['url']); ?>" target="_blank" class="hover:text-blue-600">
                            <?php echo sanitize(substr($link['url'], 0, 50)) . '...'; ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="?toggle=<?php echo $link['id']; ?>" class="text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $link['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $link['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="links-edit.php?id=<?php echo $link['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $link['id']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus?')"
                           class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada link</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
