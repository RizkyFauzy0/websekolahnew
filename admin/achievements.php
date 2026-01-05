<?php
require_once '../includes/db.php';

$page_title = 'Kelola Prestasi';

$category = isset($_GET['category']) ? escapeString($_GET['category']) : 'siswa';
if (!in_array($category, ['siswa', 'guru', 'sekolah'])) $category = 'siswa';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $achievement = fetchOne("SELECT * FROM achievements WHERE id = $id");
    if ($achievement) {
        if ($achievement['image']) deleteFile($achievement['image']);
        query("DELETE FROM achievements WHERE id = $id");
        setFlashMessage('Prestasi berhasil dihapus', 'success');
    }
    header('Location: achievements.php?category=' . $category);
    exit;
}

$achievements = fetchAll("SELECT * FROM achievements WHERE category = '$category' ORDER BY achievement_date DESC");

$titles = ['siswa' => 'Prestasi Siswa', 'guru' => 'Prestasi Guru', 'sekolah' => 'Prestasi Sekolah'];

ob_start();
?>

<div class="mb-6 flex items-center justify-between">
    <div class="space-x-2">
        <a href="achievements.php?category=siswa" class="px-4 py-2 rounded-lg <?php echo $category === 'siswa' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">
            Siswa
        </a>
        <a href="achievements.php?category=guru" class="px-4 py-2 rounded-lg <?php echo $category === 'guru' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">
            Guru
        </a>
        <a href="achievements.php?category=sekolah" class="px-4 py-2 rounded-lg <?php echo $category === 'sekolah' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">
            Sekolah
        </a>
    </div>
    <a href="achievements-edit.php?category=<?php echo $category; ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah Prestasi
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tingkat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($achievements)): ?>
                <?php foreach ($achievements as $achievement): ?>
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <?php if ($achievement['image']): ?>
                            <img src="<?php echo UPLOAD_URL . '/' . sanitize($achievement['image']); ?>" 
                                 class="h-10 w-10 rounded object-cover mr-3">
                            <?php endif; ?>
                            <div class="text-sm font-medium text-gray-900"><?php echo sanitize($achievement['title']); ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo sanitize($achievement['level']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatDate($achievement['achievement_date']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="achievements-edit.php?id=<?php echo $achievement['id']; ?>&category=<?php echo $category; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $achievement['id']; ?>&category=<?php echo $category; ?>" 
                           onclick="return confirm('Yakin ingin menghapus?')"
                           class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada prestasi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
