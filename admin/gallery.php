<?php
require_once '../includes/db.php';

$page_title = 'Kelola Galeri';

$type = isset($_GET['type']) ? escapeString($_GET['type']) : 'photo';
if (!in_array($type, ['photo', 'video'])) $type = 'photo';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $item = fetchOne("SELECT * FROM gallery WHERE id = $id");
    if ($item) {
        if ($item['file_path']) deleteFile($item['file_path']);
        if ($item['thumbnail']) deleteFile($item['thumbnail']);
        query("DELETE FROM gallery WHERE id = $id");
        setFlashMessage('Item berhasil dihapus', 'success');
    }
    header('Location: gallery.php?type=' . $type);
    exit;
}

$gallery_items = fetchAll("SELECT * FROM gallery WHERE type = '$type' ORDER BY sort_order ASC");

ob_start();
?>

<div class="mb-6 flex items-center justify-between">
    <div class="space-x-2">
        <a href="gallery.php?type=photo" class="px-4 py-2 rounded-lg <?php echo $type === 'photo' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">
            <i class="fas fa-image mr-2"></i>Foto
        </a>
        <a href="gallery.php?type=video" class="px-4 py-2 rounded-lg <?php echo $type === 'video' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?>">
            <i class="fas fa-video mr-2"></i>Video
        </a>
    </div>
    <a href="gallery-edit.php?type=<?php echo $type; ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Tambah <?php echo $type === 'photo' ? 'Foto' : 'Video'; ?>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (!empty($gallery_items)): ?>
        <?php foreach ($gallery_items as $item): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if ($type === 'photo'): ?>
                <img src="<?php echo UPLOAD_URL . '/' . sanitize($item['file_path']); ?>" 
                     class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-video text-4xl text-gray-400"></i>
                </div>
            <?php endif; ?>
            
            <div class="p-4">
                <h3 class="font-bold text-gray-800 mb-2"><?php echo sanitize($item['title']); ?></h3>
                <?php if ($item['description']): ?>
                <p class="text-sm text-gray-600 mb-4"><?php echo sanitize(substr($item['description'], 0, 100)); ?></p>
                <?php endif; ?>
                
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Urutan: <?php echo $item['sort_order']; ?></span>
                    <div class="space-x-2">
                        <a href="gallery-edit.php?id=<?php echo $item['id']; ?>&type=<?php echo $type; ?>" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=<?php echo $item['id']; ?>&type=<?php echo $type; ?>" 
                           onclick="return confirm('Yakin ingin menghapus?')"
                           class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-3 text-center py-12 text-gray-500">
            Belum ada <?php echo $type === 'photo' ? 'foto' : 'video'; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
