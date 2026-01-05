<?php
require_once '../includes/db.php';

$page_title = 'Tambah/Edit Berita';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$news = null;

if ($id > 0) {
    $news = fetchOne("SELECT * FROM news WHERE id = $id");
    if (!$news) {
        header('Location: news.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = escapeString($_POST['title']);
    $slug = generateSlug($_POST['slug'] ?: $title);
    $content = escapeString($_POST['content']);
    $excerpt = escapeString($_POST['excerpt']);
    $author = escapeString($_POST['author']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $published_at = escapeString($_POST['published_at']);
    
    // Handle image upload
    $image_path = $news['image'] ?? '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image'], 'news', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload['success']) {
            if ($image_path) {
                deleteFile($image_path);
            }
            $image_path = $upload['filepath'];
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE news SET 
                title = '$title',
                slug = '$slug',
                content = '$content',
                excerpt = '$excerpt',
                image = '$image_path',
                author = '$author',
                is_published = $is_published,
                published_at = '$published_at'
                WHERE id = $id";
    } else {
        // Insert
        $sql = "INSERT INTO news (title, slug, content, excerpt, image, author, is_published, published_at) 
                VALUES ('$title', '$slug', '$content', '$excerpt', '$image_path', '$author', $is_published, '$published_at')";
    }
    
    if (query($sql)) {
        setFlashMessage('Berita berhasil disimpan', 'success');
        header('Location: news.php');
        exit;
    } else {
        setFlashMessage('Gagal menyimpan berita', 'error');
    }
}

ob_start();
?>

<div class="mb-6">
    <a href="news.php" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Berita
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Judul Berita *</label>
                    <input type="text" 
                           name="title" 
                           value="<?php echo sanitize($news['title'] ?? ''); ?>"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Slug (URL)</label>
                    <input type="text" 
                           name="slug" 
                           value="<?php echo sanitize($news['slug'] ?? ''); ?>"
                           placeholder="akan di-generate otomatis dari judul"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Excerpt/Ringkasan</label>
                    <textarea name="excerpt" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($news['excerpt'] ?? ''); ?></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Konten Berita *</label>
                    <textarea name="content" 
                              rows="15"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo sanitize($news['content'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Berita</label>
                    <?php if ($news && $news['image']): ?>
                    <div class="mb-2">
                        <img src="<?php echo UPLOAD_URL . '/' . sanitize($news['image']); ?>" 
                             alt="Current" 
                             class="w-full rounded">
                    </div>
                    <?php endif; ?>
                    <input type="file" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Author</label>
                    <input type="text" 
                           name="author" 
                           value="<?php echo sanitize($news['author'] ?? 'Admin'); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Publikasi</label>
                    <input type="datetime-local" 
                           name="published_at" 
                           value="<?php echo $news ? date('Y-m-d\TH:i', strtotime($news['published_at'])) : date('Y-m-d\TH:i'); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_published" 
                               <?php echo (!$news || $news['is_published']) ? 'checked' : ''; ?>
                               class="mr-2">
                        <span class="text-gray-700 font-semibold">Publikasikan Berita</span>
                    </label>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Berita
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>
