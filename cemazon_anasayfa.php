<?php
// URL'den gelen parametreleri al
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$ara = isset($_GET['ara']) ? $_GET['ara'] : ''; 

// Veritabanı bağlantısı kontrol
if (!isset($db)) {
    die("Veritabanı bağlantısı bulunamadı.");
}

// --- SQL SORGU MANTIĞI ---
if ($ara) {
    // DURUM 1: Arama Yapılmışsa
    $sorgu = $db->prepare("SELECT * FROM urunler WHERE ad LIKE ? OR aciklama LIKE ?");
    $arama_terimi = "%" . $ara . "%";
    $sorgu->execute([$arama_terimi, $arama_terimi]);
    $baslik = '"' . htmlspecialchars($ara) . '" için arama sonuçları';
} elseif ($kategori) {
    // DURUM 2: Kategori Seçilmişse
    $sorgu = $db->prepare("SELECT * FROM urunler WHERE kategori_slug = ?");
    $sorgu->execute([$kategori]);
    $baslik = ucfirst($kategori) . ' Ürünleri';
} else {
    // DURUM 3: Hiçbir şey yoksa (Tüm Ürünler)
    $sorgu = $db->query("SELECT * FROM urunler");
    $baslik = 'Öne Çıkan Ürünler';
}

$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!$kategori && !$ara): ?>
<div id="anaSlider" class="carousel slide mb-5 shadow-lg rounded overflow-hidden" data-bs-ride="carousel">
    
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="3"></button>
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="4"></button>
    </div>
    
    <div class="carousel-inner">
        
        <div class="carousel-item active">
            <a href="#urunler">
                <img src="logolar/cemazonre.png" class="d-block w-100" alt="Cemazon" style="height: 400px; object-fit: cover; filter: brightness(1);">
            </a>
            <div class="carousel-caption d-none d-md-block text-start">
                <a href="#urunler" class="btn btn-warning btn-lg">Alışverişe Başla</a>
            </div>
        </div>

        <div class="carousel-item">
            <a href="cemazon.php?sayfa=anasayfa&kategori=elektronik">
                <img src="https://cdn.vatanbilgisayar.com/Upload/BANNER//0banner/2025/010/lg-tv-2-10-25-web.jpg" class="d-block w-100" alt="Elektronik" style="height: 400px; object-fit: cover; filter: brightness(1);">
            </a>
        </div>

        <div class="carousel-item">
            <a href="cemazon.php?sayfa=anasayfa&kategori=ev">
                <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80" class="d-block w-100" alt="Ev Yaşam" style="height: 400px; object-fit: cover; filter: brightness(0.7);">
            </a>
            <div class="carousel-caption d-none d-md-block text-start">
                <h1 class="display-4 fw-bold">Evinizin Yeni Havası</h1>
                <p class="lead">Mobilyadan dekorasyona, eviniz için her şey burada.</p>
            </div>
        </div>
        
        <div class="carousel-item">
            <a href="cemazon.php?sayfa=anasayfa&kategori=giyim">
                <img src="https://images.hepsiburada.net/banners/s/1/1280-400/gra-205179-webslider134102560496874600.jpg/format:webp" class="d-block w-100" alt="Moda" style="height: 400px; object-fit: cover; filter: brightness(0.7);">
            </a>
            <div class="carousel-caption d-none d-md-block text-start">
                <h1 class="display-4 fw-bold">Tarzını Yansıt</h1>
                <p class="lead">Yeni sezon giyim ürünlerinde kaçırılmayacak fırsatlar.</p>
                <a href="cemazon.php?sayfa=anasayfa&kategori=giyim" class="btn btn-outline-light btn-lg">Modayı Keşfet</a>
            </div>
        </div>

        <div class="carousel-item">
            <a href="cemazon.php?sayfa=anasayfa&kategori=kitap">
                <img src="https://i.dr.com.tr/pimages/Content/Uploads/BannerFiles/dr/2025-YILBASI-KITAPSEVERLER_1024-x-400_4_11zonYilbasi-Kitaplarini-Kesfet-2025.webp" class="d-block w-100" alt="Kitap" style="height: 400px; object-fit: cover; filter: brightness(1);">
            </a>
            <div class="carousel-caption d-none d-md-block text-start">
                <p class="lead">Kitap aklın ilacıdır. Ovidius</p>
            </div>
        </div>

    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#anaSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#anaSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<?php endif; ?>


<section class="py-5" id="urunler">
    <div class="container">
        <h2 class="text-center mb-5"><?php echo $baslik; ?></h2>
        
        <?php if (count($urunler) == 0): ?>
            <div class="alert alert-warning text-center">
                Aradığınız kriterlere uygun ürün bulunamadı. <br>
                <a href="cemazon.php" class="btn btn-primary mt-3">Tüm Ürünleri Gör</a>
            </div>
        <?php else: ?>
        
        <div class="row">
            <?php foreach ($urunler as $urun): ?>
                
                <?php 
                    $indirim_orani = 0;
                    if(isset($urun['eski_fiyat']) && $urun['eski_fiyat'] > 0 && $urun['eski_fiyat'] > $urun['fiyat']) {
                        $fark = $urun['eski_fiyat'] - $urun['fiyat'];
                        $indirim_orani = round(($fark / $urun['eski_fiyat']) * 100);
                    }
                ?>

                <div class="col-md-3 mb-4" data-aos="fade-up">
                    <div class="card product-card h-100 shadow-sm position-relative">
                        
                        <?php if($indirim_orani > 0): ?>
                            <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded fw-bold shadow" style="z-index: 10; font-size: 0.8rem;">
                                %<?php echo $indirim_orani; ?> İndirim
                            </div>
                        <?php endif; ?>

                        <a href="cemazon.php?sayfa=urun_detay&id=<?php echo $urun['id']; ?>" class="text-decoration-none text-dark">
                            <img src="<?php echo $urun['resim_url']; ?>" class="card-img-top product-image" alt="<?php echo $urun['ad']; ?>">
                        </a>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="cemazon.php?sayfa=urun_detay&id=<?php echo $urun['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo $urun['ad']; ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small">
                                <?php echo substr($urun['aciklama'], 0, 80) . '...'; ?>
                            </p>
                            
                            <div class="mt-auto">
                                <div class="mb-3 d-flex align-items-center">
                                    <span class="product-price fw-bold text-success fs-5"><?php echo number_format($urun['fiyat'], 2); ?> ₺</span>
                                    
                                    <?php if ($indirim_orani > 0): ?>
                                        <span class="product-old-price ms-2 text-decoration-line-through text-muted small">
                                            <?php echo number_format($urun['eski_fiyat'], 2); ?> ₺
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($urun['stok'] > 0): ?>
                                    <button class="btn btn-warning w-100" onclick="sepeteEkle(<?php echo $urun['id']; ?>)">
                                        <i class="fas fa-cart-plus me-2"></i>Sepete Ekle
                                    </button>
                                    <?php if ($urun['stok'] < 5): ?>
                                        <small class="text-danger fw-bold d-block mt-2 text-center">Son <?php echo $urun['stok']; ?> ürün!</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times-circle me-2"></i>Tükendi
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>