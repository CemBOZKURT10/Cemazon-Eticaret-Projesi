<?php
// Sepet boşsa uyarı ver
if (!isset($_SESSION['sepet']) || count($_SESSION['sepet']) == 0) {
    echo '<div class="container py-5 text-center">
            <div class="alert alert-warning">Sepetinizde henüz ürün yok.</div>
            <a href="cemazon.php" class="btn btn-primary">Alışverişe Başla</a>
          </div>';
} else {
    // Sepet doluysa ürünleri veritabanından çekelim
    $ids = array_keys($_SESSION['sepet']); // Sepetteki ürün ID'lerini al
    $id_listesi = implode(',', $ids); // ID'leri virgülle birleştir (örn: 1,5,8)
    
    // Veritabanından bu ID'lere sahip ürünleri çek
    $sorgu = $db->query("SELECT * FROM urunler WHERE id IN ($id_listesi)");
    $urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    
    $toplam_tutar = 0;
?>

<div class="container py-5">
    <h2 class="mb-4">🛒 Alışveriş Sepetim</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php foreach($urunler as $urun): 
                        $adet = $_SESSION['sepet'][$urun['id']];
                        $ara_toplam = $urun['fiyat'] * $adet;
                        $toplam_tutar += $ara_toplam;
                    ?>
                    <div class="row align-items-center border-bottom py-3">
                        <div class="col-md-2">
                            <img src="<?php echo $urun['resim_url']; ?>" class="img-fluid rounded" style="height: 80px; object-fit: contain;">
                        </div>
                        <div class="col-md-4">
                            <h5><?php echo $urun['ad']; ?></h5>
                            <small class="text-muted">Birim Fiyat: <?php echo number_format($urun['fiyat'], 2); ?> ₺</small>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="miktarGuncelle(<?php echo $urun['id']; ?>, <?php echo $adet - 1; ?>)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                
                                <input type="text" class="form-control text-center" value="<?php echo $adet; ?>" readonly>
                                
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="miktarGuncelle(<?php echo $urun['id']; ?>, <?php echo $adet + 1; ?>)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 text-end fw-bold">
                            <?php echo number_format($ara_toplam, 2); ?> ₺
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="cemazon.php?sayfa=sepet_sil&id=<?php echo $urun['id']; ?>&islem=sil" class="text-danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mt-3">
                <a href="cemazon.php?sayfa=sepet_bosalt" class="btn btn-outline-danger btn-sm">Sepeti Boşalt</a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h4>Sipariş Özeti</h4>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Ara Toplam:</span>
                        <strong><?php echo number_format($toplam_tutar, 2); ?> ₺</strong>
                    </div>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="cemazon.php?sayfa=odeme" class="btn btn-success w-100 btn-lg">
                            Siparişi Tamamla <i class="fas fa-check-circle ms-2"></i>
                        </a>
                    <?php else: ?>
                        <a href="cemazon.php?sayfa=giris" class="btn btn-warning w-100 btn-lg">
                            Sipariş İçin Giriş Yap <i class="fas fa-sign-in-alt ms-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } // Else bitişi ?>