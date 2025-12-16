<?php
// ID gelmiş mi kontrol et
if (!isset($_GET['id'])) {
    header("Location: cemazon.php");
    exit;
}

$id = intval($_GET['id']);

// Veritabanından o ürünün bilgilerini çek
$sorgu = $db->prepare("SELECT * FROM urunler WHERE id = ?");
$sorgu->execute([$id]);
$urun = $sorgu->fetch(PDO::FETCH_ASSOC);

// Eğer ürün yoksa (belki silinmiştir) ana sayfaya at
if (!$urun) {
    header("Location: cemazon.php");
    exit;
}
?>

<div class="container py-5">
    <a href="javascript:history.back()" class="btn btn-outline-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Alışverişe Dön
    </a>

    <div class="card shadow-lg border-0">
        <div class="row g-0">
            
            <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4">
                <img src="<?php echo $urun['resim_url']; ?>" class="img-fluid rounded" 
                     style="max-height: 500px; width: 100%; object-fit: contain;" 
                     alt="<?php echo $urun['ad']; ?>">
            </div>

            <div class="col-md-6">
                <div class="card-body p-5">
                    
                    <span class="badge bg-primary mb-2"><?php echo ucfirst($urun['kategori_slug'] ?? 'Genel'); ?></span>
                    
                    <h1 class="fw-bold mb-3"><?php echo $urun['ad']; ?></h1>
                    
                    <div class="mb-4">
                        <span class="display-5 fw-bold text-success"><?php echo number_format($urun['fiyat'], 2); ?> ₺</span>
                        <?php if($urun['eski_fiyat']): ?>
                            <span class="text-muted text-decoration-line-through fs-4 ms-2">
                                <?php echo number_format($urun['eski_fiyat'], 2); ?> ₺
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="lead text-muted mb-4">
                        <?php echo nl2br($urun['aciklama']); ?>
                    </p>

                    <div class="d-grid gap-2">
                        <?php if($urun['stok'] > 0): ?>
                            <p class="text-success mb-1"><i class="fas fa-check-circle"></i> Stokta <?php echo $urun['stok']; ?> adet var</p>
                            
                            <button class="btn btn-warning btn-lg" onclick="sepeteEkle(<?php echo $urun['id']; ?>)">
                                <i class="fas fa-shopping-cart me-2"></i>Sepete Ekle
                            </button>
                            
                        <?php else: ?>
                            <div class="alert alert-danger text-center">
                                <i class="fas fa-times-circle"></i> Bu Ürün Tükendi
                            </div>
                            <button class="btn btn-secondary btn-lg" disabled>Stok Yok</button>
                        <?php endif; ?>
                    </div>

                    <div class="row mt-5 text-center text-muted">
                        <div class="col-4">
                            <i class="fas fa-lock fa-2x mb-2"></i><br><small>Güvenli Ödeme</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-truck fa-2x mb-2"></i><br><small>Hızlı Kargo</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-undo fa-2x mb-2"></i><br><small>İade Garantisi</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <h3 class="mb-4">Müşteri Yorumları</h3>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="card bg-light mb-4 shadow-sm border-0">
            <div class="card-body">
                <form action="cemazon.php?sayfa=yorum_yap" method="POST">
                    <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Puanınız:</label>
                        <select name="puan" class="form-select w-25">
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Mükemmel)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - İyi)</option>
                            <option value="3">⭐⭐⭐ (3 - Orta)</option>
                            <option value="2">⭐⭐ (2 - Kötü)</option>
                            <option value="1">⭐ (1 - Berbat)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Yorumunuz:</label>
                        <textarea name="yorum" class="form-control" rows="3" placeholder="Ürün hakkında ne düşünüyorsunuz?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Yorumu Gönder</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Yorum yapabilmek için <a href="cemazon.php?sayfa=giris">giriş yapmalısınız.</a></div>
    <?php endif; ?>

    <?php
    $yorum_sorgu = $db->prepare("SELECT y.*, k.ad_soyad FROM yorumlar y JOIN kullanicilar k ON y.user_id = k.id WHERE y.urun_id = ? ORDER BY y.tarih DESC");
    $yorum_sorgu->execute([$urun['id']]);
    $yorumlar = $yorum_sorgu->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if(count($yorumlar) == 0): ?>
        <p class="text-muted">Henüz yorum yapılmamış. İlk yorumu sen yap!</p>
    <?php else: ?>
        <?php foreach($yorumlar as $y): ?>
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold"><?php echo $y['ad_soyad']; ?></h6>
                        <span class="text-warning">
                            <?php for($i=0; $i<$y['puan']; $i++) echo '★'; ?>
                            <?php for($i=$y['puan']; $i<5; $i++) echo '☆'; ?>
                        </span>
                    </div>
                    <p class="card-text mt-2"><?php echo htmlspecialchars($y['yorum']); ?></p>
                    <small class="text-muted"><?php echo date('d.m.Y H:i', strtotime($y['tarih'])); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>