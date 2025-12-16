<?php
// cemazon_urun_form.php - ÜRÜN EKLEME VE DÜZENLEME FORMU

// Güvenlik: Sadece Admin ve Satıcı
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'satici')) {
    die('<div class="container py-5 alert alert-danger">Yetkisiz Giriş! Sadece Admin ve Satıcılar girebilir.</div>');
}

$islem = 'Ekle';
$baslik = 'Yeni Ürün Ekle';
// DÜZELTME 1: 'eski_fiyat' anahtarını diziye ekledik, hata vermesin diye.
$urun = ['ad' => '', 'fiyat' => '', 'eski_fiyat' => '', 'stok' => '', 'aciklama' => '', 'resim_url' => '', 'kategori_slug' => ''];

// Eğer Düzenleme modundaysak (URL'de ID varsa)
if (isset($_GET['id'])) {
    $islem = 'Guncelle';
    $baslik = 'Ürün Düzenle';
    $sorgu = $db->prepare("SELECT * FROM urunler WHERE id = ?");
    $sorgu->execute([$_GET['id']]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    // Güvenlik: Satıcı sadece kendi ürününü düzenleyebilsin (Admin hariç)
    if($_SESSION['rol'] != 'admin' && $urun['satici_id'] != $_SESSION['user_id']){
        die("Bu ürünü düzenleme yetkiniz yok.");
    }
}

// FORM GÖNDERİLDİ Mİ?
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['ad'];
    $fiyat = $_POST['fiyat'];
    
    // DÜZELTME 2: Eski Fiyatı alıyoruz. Boşsa NULL yapıyoruz ki veritabanı kirlenmesin.
    $eski_fiyat = !empty($_POST['eski_fiyat']) ? $_POST['eski_fiyat'] : NULL;
    
    $stok = $_POST['stok'];
    $aciklama = $_POST['aciklama'];
    $kategori = $_POST['kategori_slug'];
    $resim_yolu = $urun['resim_url']; // Varsayılan eski resim

    // RESİM YÜKLEME İŞLEMİ
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $dosya_adi = time() . '_' . $_FILES['resim']['name'];
        $hedef = 'uploads/' . $dosya_adi;
        if (!file_exists('uploads')) { mkdir('uploads', 0777, true); }
        if (move_uploaded_file($_FILES['resim']['tmp_name'], $hedef)) {
            $resim_yolu = $hedef;
        }
    } else if (empty($resim_yolu) && empty($_POST['resim_url_link'])) {
         $resim_yolu = "https://via.placeholder.com/300";
    }
    
    if (!empty($_POST['resim_url_link'])) {
        $resim_yolu = $_POST['resim_url_link'];
    }

    // VERİTABANI İŞLEMİ
    if ($islem == 'Ekle') {
        // DÜZELTME 3: SQL Sorgusuna 'eski_fiyat' eklendi
        $ekle = $db->prepare("INSERT INTO urunler (ad, aciklama, fiyat, eski_fiyat, resim_url, kategori_slug, stok, satici_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $ekle->execute([$ad, $aciklama, $fiyat, $eski_fiyat, $resim_yolu, $kategori, $stok, $_SESSION['user_id']]);
    } else {
        // DÜZELTME 4: Güncelleme sorgusuna 'eski_fiyat' eklendi
        $guncelle = $db->prepare("UPDATE urunler SET ad=?, aciklama=?, fiyat=?, eski_fiyat=?, resim_url=?, kategori_slug=?, stok=? WHERE id=? AND (satici_id = ? OR ? = 'admin')");
        $guncelle->execute([$ad, $aciklama, $fiyat, $eski_fiyat, $resim_yolu, $kategori, $stok, $_GET['id'], $_SESSION['user_id'], $_SESSION['rol']]);
    }

    echo "<script>alert('İşlem Başarılı! İndirim varsa rozet otomatik görünecek.'); window.location.href='cemazon.php?sayfa=panel';</script>";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?php echo $baslik; ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label>Ürün Adı</label>
                            <input type="text" name="ad" class="form-control" value="<?php echo htmlspecialchars($urun['ad']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Satış Fiyatı (TL)</label>
                                <input type="number" step="0.01" name="fiyat" class="form-control" placeholder="Örn: 75000" value="<?php echo $urun['fiyat']; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Eski Fiyat (Opsiyonel)</label>
                                <input type="number" step="0.01" name="eski_fiyat" class="form-control" placeholder="Örn: 85000" value="<?php echo $urun['eski_fiyat']; ?>">
                                <small class="text-muted" style="font-size: 0.75rem;">Daha yüksek girerseniz "İndirim" rozeti çıkar.</small>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label>Stok Adedi</label>
                                <input type="number" name="stok" class="form-control" value="<?php echo $urun['stok']; ?>" required>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label>Kategori</label>
                                <select name="kategori_slug" class="form-select">
                                    <option value="elektronik" <?php echo $urun['kategori_slug']=='elektronik'?'selected':''; ?>>Elektronik</option>
                                    <option value="giyim" <?php echo $urun['kategori_slug']=='giyim'?'selected':''; ?>>Giyim</option>
                                    <option value="ev" <?php echo $urun['kategori_slug']=='ev'?'selected':''; ?>>Ev & Yaşam</option>
                                    <option value="kitap" <?php echo $urun['kategori_slug']=='kitap'?'selected':''; ?>>Kitap</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Açıklama</label>
                            <textarea name="aciklama" class="form-control" rows="4"><?php echo htmlspecialchars($urun['aciklama']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Ürün Resmi (Dosya Yükle)</label>
                            <input type="file" name="resim" class="form-control">
                            <small class="text-muted">veya internet resim linki yapıştır:</small>
                            <input type="text" name="resim_url_link" class="form-control mt-1" placeholder="https://..." value="<?php echo strpos($urun['resim_url'], 'http') === 0 ? $urun['resim_url'] : ''; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><?php echo $islem; ?> Yap</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>