<?php
// cemazon_panel.php - PROFESYONEL YÖNETİM PANELİ

// 1. GÜVENLİK KONTROLÜ
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'satici')) {
    die('<div class="container py-5 alert alert-danger">Yetkisiz Giriş! Sadece Admin ve Satıcılar girebilir.</div>');
}

$aktif_kul_id = $_SESSION['user_id'];
$aktif_rol = $_SESSION['rol'];

// 2. İSTATİSTİK VERİLERİNİ HESAPLA
if ($aktif_rol == 'admin') {
    // ADMIN VERİLERİ
    $ciro = $db->query("SELECT SUM(toplam_tutar) FROM siparisler")->fetchColumn() ?? 0;
    $siparis_sayi = $db->query("SELECT COUNT(*) FROM siparisler")->fetchColumn();
    $uye_sayi = $db->query("SELECT COUNT(*) FROM kullanicilar WHERE rol = 'user'")->fetchColumn();
    $satici_sayi = $db->query("SELECT COUNT(*) FROM kullanicilar WHERE rol = 'satici'")->fetchColumn();
    $stok_uyari = $db->query("SELECT COUNT(*) FROM urunler WHERE stok < 5")->fetchColumn();
} else {
    // SATICI VERİLERİ (Sadece kendi ürünleri)
    $ciro_sql = "SELECT SUM(sd.adet * sd.birim_fiyat) FROM siparis_detay sd JOIN urunler u ON sd.urun_id = u.id WHERE u.satici_id = ?";
    $stmt = $db->prepare($ciro_sql); $stmt->execute([$aktif_kul_id]); 
    $ciro = $stmt->fetchColumn() ?? 0;

    $siparis_sql = "SELECT COUNT(DISTINCT sd.siparis_id) FROM siparis_detay sd JOIN urunler u ON sd.urun_id = u.id WHERE u.satici_id = ?";
    $stmt = $db->prepare($siparis_sql); $stmt->execute([$aktif_kul_id]);
    $siparis_sayi = $stmt->fetchColumn();

    $stok_sql = "SELECT COUNT(*) FROM urunler WHERE stok < 5 AND satici_id = ?";
    $stmt = $db->prepare($stok_sql); $stmt->execute([$aktif_kul_id]);
    $stok_uyari = $stmt->fetchColumn();
    
    // Satıcı için boş değerler
    $uye_sayi = 0; $satici_sayi = 0;
}

// 3. ÜRÜNLERİ LİSTELE
if ($aktif_rol == 'admin') {
    $sql = "SELECT urunler.*, kullanicilar.ad_soyad as satici_adi FROM urunler LEFT JOIN kullanicilar ON urunler.satici_id = kullanicilar.id ORDER BY id DESC";
    $sorgu = $db->prepare($sql);
    $sorgu->execute();
} else {
    $sql = "SELECT * FROM urunler WHERE satici_id = ? ORDER BY id DESC";
    $sorgu = $db->prepare($sql);
    $sorgu->execute([$aktif_kul_id]);
}
$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

// 4. IZGARA AYARLARI
if ($aktif_rol == 'admin') {
    $ciro_grid = 'col-lg-4 col-md-12';
    $diger_grid = 'col-lg-2 col-md-3 col-6';
    $stok_grid = 'col-lg-2 col-md-3 col-6';
} else {
    $ciro_grid = 'col-md-4';
    $diger_grid = 'col-md-4';
    $stok_grid = 'col-md-4';
}
?>

<div class="container py-5">
    
    <div class="row mb-5 g-3">
        <div class="<?php echo $ciro_grid; ?>">
            <div class="card text-white bg-success shadow h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="overflow: hidden;">
                            <h6 class="card-title mb-1 text-white-50 fs-6"><?php echo ($aktif_rol == 'admin') ? 'Toplam Ciro' : 'Kazancım'; ?></h6>
                            <h3 class="fw-bold mb-0 text-truncate" title="<?php echo number_format($ciro, 2); ?> ₺">
                                <?php echo number_format($ciro, 2); ?> <small style="font-size: 0.5em">₺</small>
                            </h3>
                        </div>
                        <i class="fas fa-wallet fa-2x opacity-25 ms-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="<?php echo $diger_grid; ?>">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-white-50 fs-6">Siparişler</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0"><?php echo $siparis_sayi; ?></h3>
                        <i class="fas fa-box-open fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <?php if($aktif_rol == 'admin'): ?>
        <div class="<?php echo $diger_grid; ?>">
            <div class="card text-white bg-info shadow h-100">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-white-50 fs-6">Müşteriler</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0"><?php echo $uye_sayi; ?></h3>
                        <i class="fas fa-users fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="<?php echo $diger_grid; ?>">
            <div class="card text-white shadow h-100" style="background-color: #6f42c1;">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-white-50 fs-6">Satıcılar</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0"><?php echo $satici_sayi; ?></h3>
                        <i class="fas fa-user-tie fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="<?php echo $stok_grid; ?>">
            <div class="card text-white bg-danger shadow h-100">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-white-50 fs-6">Kritik Stok</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0"><?php echo $stok_uyari; ?></h3>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Ürün Yönetim Paneli</h2>
        <a href="cemazon.php?sayfa=urun_ekle" class="btn btn-success">
            <i class="fas fa-plus"></i> Yeni Ürün Ekle
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Resim</th>
                            <th>Ürün Adı</th>
                            <th>Fiyat</th>
                            <th>Stok</th>
                            <?php if($aktif_rol == 'admin'): ?> <th>Satıcı</th> <?php endif; ?>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($urunler as $urun): ?>
                        <tr>
                            <td><?php echo $urun['id']; ?></td>
                            <td><img src="<?php echo $urun['resim_url']; ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;"></td>
                            <td><?php echo $urun['ad']; ?></td>
                            <td><?php echo number_format($urun['fiyat'], 2); ?> ₺</td>
                            <td>
                                <span class="badge bg-<?php echo $urun['stok'] > 0 ? 'primary' : 'danger'; ?>">
                                    <?php echo $urun['stok']; ?> Adet
                                </span>
                            </td>
                            <?php if($aktif_rol == 'admin'): ?> 
                                <td><span class="badge bg-secondary"><?php echo $urun['satici_adi'] ?? 'Bilinmiyor'; ?></span></td>
                            <?php endif; ?>
                            <td>
                                <a href="cemazon.php?sayfa=urun_duzenle&id=<?php echo $urun['id']; ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                <a href="cemazon.php?sayfa=urun_sil&id=<?php echo $urun['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğine emin misin?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>