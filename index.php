<?php
require_once 'includes/db.php';

// Get settings
$settings = fetchOne("SELECT * FROM settings WHERE id = 1");

// Get active sliders
$sliders = fetchAll("SELECT * FROM sliders WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 5");

// Get latest news
$latest_news = fetchAll("SELECT * FROM news WHERE is_published = 1 ORDER BY published_at DESC LIMIT 6");

// Get active teachers
$teachers = fetchAll("SELECT * FROM teachers WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 8");

$page_title = $settings['school_name'] ?? 'School Website';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($page_title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .slider-active {
            display: block;
        }
        .slider-inactive {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Slider/Carousel -->
    <?php if (!empty($sliders)): ?>
    <section class="relative h-96 md:h-[500px] overflow-hidden bg-gray-900">
        <div id="slider" class="relative w-full h-full">
            <?php foreach ($sliders as $index => $slider): ?>
            <div class="slider-item absolute w-full h-full <?php echo $index === 0 ? 'slider-active' : 'slider-inactive'; ?>" data-index="<?php echo $index; ?>">
                <img src="<?php echo UPLOAD_URL . '/' . sanitize($slider['image']); ?>" 
                     alt="<?php echo sanitize($slider['title']); ?>" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h2 class="text-3xl md:text-5xl font-bold mb-4"><?php echo sanitize($slider['title']); ?></h2>
                        <?php if ($slider['description']): ?>
                        <p class="text-lg md:text-xl"><?php echo sanitize($slider['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Slider controls -->
        <?php if (count($sliders) > 1): ?>
        <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 text-gray-900 p-3 rounded-full">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 text-gray-900 p-3 rounded-full">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Slider indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <?php foreach ($sliders as $index => $slider): ?>
            <button onclick="goToSlide(<?php echo $index; ?>)" 
                    class="slider-indicator w-3 h-3 rounded-full <?php echo $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50'; ?>" 
                    data-index="<?php echo $index; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Statistics -->
    <section class="py-12 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <h3 class="text-4xl font-bold"><?php echo sanitize($settings['student_count'] ?? '0'); ?></h3>
                    <p class="text-lg">Siswa</p>
                </div>
                <div>
                    <i class="fas fa-chalkboard-teacher text-4xl mb-4"></i>
                    <h3 class="text-4xl font-bold"><?php echo count($teachers); ?></h3>
                    <p class="text-lg">Guru</p>
                </div>
                <div>
                    <i class="fas fa-trophy text-4xl mb-4"></i>
                    <h3 class="text-4xl font-bold">50+</h3>
                    <p class="text-lg">Prestasi</p>
                </div>
                <div>
                    <i class="fas fa-building text-4xl mb-4"></i>
                    <h3 class="text-4xl font-bold">20+</h3>
                    <p class="text-lg">Fasilitas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Berita Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($latest_news as $news): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if ($news['image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($news['image']); ?>" 
                         alt="<?php echo sanitize($news['title']); ?>" 
                         class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">
                            <a href="news-detail.php?slug=<?php echo sanitize($news['slug']); ?>" class="hover:text-blue-600">
                                <?php echo sanitize($news['title']); ?>
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <i class="far fa-calendar"></i> <?php echo formatDate($news['published_at']); ?>
                        </p>
                        <?php if ($news['excerpt']): ?>
                        <p class="text-gray-700 mb-4"><?php echo sanitize(substr($news['excerpt'], 0, 150)) . '...'; ?></p>
                        <?php endif; ?>
                        <a href="news-detail.php?slug=<?php echo sanitize($news['slug']); ?>" 
                           class="text-blue-600 hover:text-blue-800 font-semibold">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($latest_news) >= 6): ?>
            <div class="text-center mt-8">
                <a href="news.php" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Lihat Semua Berita
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Teachers -->
    <?php if (!empty($teachers)): ?>
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Daftar Guru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($teachers as $teacher): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <?php if ($teacher['photo']): ?>
                    <img src="<?php echo UPLOAD_URL . '/' . sanitize($teacher['photo']); ?>" 
                         alt="<?php echo sanitize($teacher['name']); ?>" 
                         class="w-full h-64 object-cover">
                    <?php else: ?>
                    <div class="w-full h-64 bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-user text-6xl text-gray-500"></i>
                    </div>
                    <?php endif; ?>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo sanitize($teacher['name']); ?></h3>
                        <?php if ($teacher['subject']): ?>
                        <p class="text-blue-600 font-semibold"><?php echo sanitize($teacher['subject']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact & Maps -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Kontak Kami</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <div class="bg-gray-50 p-8 rounded-lg">
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-600 text-xl mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Alamat</h4>
                                    <p class="text-gray-600"><?php echo sanitize($settings['address'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-phone text-blue-600 text-xl mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Telepon</h4>
                                    <p class="text-gray-600"><?php echo sanitize($settings['phone'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-envelope text-blue-600 text-xl mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Email</h4>
                                    <p class="text-gray-600"><?php echo sanitize($settings['email'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-globe text-blue-600 text-xl mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Website</h4>
                                    <p class="text-gray-600"><?php echo sanitize($settings['website'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <?php if (!empty($settings['maps_embed'])): ?>
                    <div class="w-full h-96 rounded-lg overflow-hidden">
                        <?php echo $settings['maps_embed']; ?>
                    </div>
                    <?php else: ?>
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">Maps belum dikonfigurasi</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Slider Script -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slider-item');
        const indicators = document.querySelectorAll('.slider-indicator');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('slider-inactive');
                    slide.classList.add('slider-active');
                } else {
                    slide.classList.remove('slider-active');
                    slide.classList.add('slider-inactive');
                }
            });
            
            indicators.forEach((indicator, i) => {
                if (i === index) {
                    indicator.classList.remove('bg-opacity-50');
                    indicator.classList.add('bg-white');
                } else {
                    indicator.classList.add('bg-opacity-50');
                    indicator.classList.remove('bg-white');
                }
            });
            
            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % totalSlides;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(prev);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Auto advance slider
        if (totalSlides > 1) {
            setInterval(nextSlide, 5000);
        }
    </script>
</body>
</html>
