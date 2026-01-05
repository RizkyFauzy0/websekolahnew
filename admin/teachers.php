<?php
require_once '../includes/db.php';

$page_title = 'Kelola Guru';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $teacher = fetchOne("SELECT * FROM teachers WHERE id = $id");
    if ($teacher) {
        if ($teacher['photo']) deleteFile($teacher['photo']);
        query("DELETE FROM teachers WHERE id = $id");
        setFlashMessage('Guru berhasil dihapus', 'success');
    }
    header('Location: teachers.php');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    query("UPDATE teachers SET is_active = IF(is_active = 1, 0, 1) WHERE id = $id");
    setFlashMessage('Status guru berhasil diubah', 'success');
    header('Location: teachers.php');
    exit;
}

$teachers = fetchAll("SELECT * FROM teachers ORDER BY sort_order ASC");

ob_start();
?>

<div class="mb-6">
    <a href="teachers-edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Guru
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($teachers)): ?>
                <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($teacher['photo']): ?>
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($teacher['photo']); ?>" 
                             class="h-16 w-16 rounded-full object-cover">
                        <?php else: ?>
                        <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo sanitize($teacher['name']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo sanitize($teacher['subject']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="?toggle=<?php echo $teacher['id']; ?>" class="text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $teacher['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $teacher['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="teachers-edit.php?id=<?php echo $teacher['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $teacher['id']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus guru ini?')"
                           class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data guru</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
