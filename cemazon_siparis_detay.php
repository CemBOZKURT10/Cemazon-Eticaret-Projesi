<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') die("Yetkisiz Giriş");
$id = $_GET['id'];

// Sipariş Bilgisi
$sorgu = $db->prepare("SELECT siparisler.*, kullanicilar.ad_soyad, kullanicilar.email FROM siparisler LEFT JOIN kullanicilar ON siparisler.user_id = kullanicilar.id WHERE siparisler.id = ?");
$sorgu->execute([$id]);
$siparis = $sorgu->fetch(PDO::FETCH_ASSOC);

// Ürün Detayları
$sorgu2 = $db->prepare("SELECT siparis_detay.*, urunler.ad, urunler.resim_url FROM siparis_detay LEFT JOIN urunler ON siparis_detay.urun_id = urunler.id WHERE siparis_id = ?");
$sorgu2->execute([$id]);
$urunler = $sorgu2->fetchAll(PDO::FETCH_ASSOC);

// DURUM GÜNCELLEME İŞLEMİ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yeni_durum = $_POST['durum'];
    $guncelle = $db->prepare("UPDATE siparisler SET durum = ? WHERE id = ?");
    $guncelle->execute([$yeni_durum, $id]);
    
    // Sayfayı yenile
    echo "<script>alert('Sipariş durumu güncellendi: $yeni_durum'); window.location.href='cemazon.php?sayfa=siparis_detay&id=$id';</script>";
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">Sipariş Bilgileri #<?php echo $id; ?></div>
                <div class="card-body">
                    <p><strong>Müşteri:</strong> <?php echo $siparis['ad_soyad']; ?></p>
                    <p><strong>E-Posta:</strong> <?php echo $siparis['email']; ?></p>
                    <p><strong>Tarih:</strong> <?php echo $siparis['siparis_tarihi']; ?></p>
                    <h4 class="text-success"><?php echo number_format($siparis['toplam_tutar'], 2); ?> ₺</h4>
                    <hr>
                    
                    <form method="POST">
                        <label class="form-label fw-bold">Sipariş Durumu:</label>
                        <select name="durum" class="form-select mb-3">
                            <option value="Hazırlanıyor" <?php echo $siparis['durum']=='Hazırlanıyor'?'selected':''; ?>>🟠 Hazırlanıyor</option>
                            <option value="Kargolandı" <?php echo $siparis['durum']=='Kargolandı'?'selected':''; ?>>🔵 Kargolandı</option>
                            <option value="Teslim Edildi" <?php echo $siparis['durum']=='Teslim Edildi'?'selected':''; ?>>🟢 Teslim Edildi</option>
                            <option value="İptal" <?php echo $siparis['durum']=='İptal'?'selected':''; ?>>🔴 İptal Edildi</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100">Durumu Güncelle</button>
                    </form>
                </div>
            </div>
            <a href="cemazon.php?sayfa=siparisler" class="btn btn-secondary w-100">Listeye Dön</a>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-light">Satın Alınan Ürünler</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Resim</th>
                                <th>Ürün Adı</th>
                                <th>Birim Fiyat</th>
                                <th>Adet</th>
                                <th>Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($urunler as $urun): ?>
                            <tr>
                                <td><img src="<?php echo $urun['resim_url']; ?>" width="50"></td>
                                <td><?php echo $urun['ad']; ?></td>
                                <td><?php echo number_format($urun['birim_fiyat'], 2); ?> ₺</td>
                                <td><?php echo $urun['adet']; ?></td>
                                <td><?php echo number_format($urun['birim_fiyat'] * $urun['adet'], 2); ?> ₺</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>