<?php
// Güvenlik: Sadece Satıcılar erişebilir
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'satici') {
    die('<div class="container py-5 alert alert-danger">Yetkisiz Giriş! Sadece Satıcılar erişebilir.</div>');
}

$siparis_id = $_GET['id'];
$satici_id = $_SESSION['user_id'];

// 1. Siparişin Genel Bilgisi (Adres vs. için)
$sorgu = $db->prepare("SELECT s.*, k.ad_soyad, k.email, k.adres 
                        FROM siparisler s 
                        JOIN kullanicilar k ON s.user_id = k.id 
                        WHERE s.id = ?");
$sorgu->execute([$siparis_id]);
$siparis = $sorgu->fetch(PDO::FETCH_ASSOC);

// 2. Ürünleri Getir (Sadece bu satıcıya ait ürünler)
$sql = "SELECT sd.*, u.ad, u.resim_url 
        FROM siparis_detay sd 
        JOIN urunler u ON sd.urun_id = u.id 
        WHERE sd.siparis_id = ? AND u.satici_id = ?";
$sorgu2 = $db->prepare($sql);
$sorgu2->execute([$siparis_id, $satici_id]);
$urunler = $sorgu2->fetchAll(PDO::FETCH_ASSOC);

// 3. Sipariş Durumu Güncelleme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yeni_durum = $_POST['durum'];
    $guncelle = $db->prepare("UPDATE siparisler SET durum = ? WHERE id = ?");
    $guncelle->execute([$yeni_durum, $siparis_id]);

    echo "<script>
        alert('Sipariş durumu başarıyla güncellendi: $yeni_durum');
        window.location.href = 'cemazon.php?sayfa=satici_siparis_detay&id=$siparis_id';
    </script>";
}
?>

<div class="container py-5">
    <div class="row">
        <!-- Sipariş Bilgileri -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark fw-bold">Sipariş Bilgileri #<?php echo $siparis_id; ?></div>
                <div class="card-body">
                    <p><strong>Müşteri:</strong> <?php echo $siparis['ad_soyad']; ?></p>
                    <p><strong>İletişim:</strong> <?php echo $siparis['email']; ?></p>
                    <p><strong>Adres:</strong> <?php echo $siparis['adres'] ?? 'Adres girilmemiş'; ?></p>
                    <hr>
                    <form method="POST">
                        <label class="form-label">Sipariş Durumu:</label>
                        <select name="durum" class="form-select mb-3">
                            <option value="Hazırlanıyor" <?php echo $siparis['durum'] == 'Hazırlanıyor' ? 'selected' : ''; ?>>Hazırlanıyor</option>
                            <option value="Kargolandı" <?php echo $siparis['durum'] == 'Kargolandı' ? 'selected' : ''; ?>>Kargolandı</option>
                            <option value="Teslim Edildi" <?php echo $siparis['durum'] == 'Teslim Edildi' ? 'selected' : ''; ?>>Teslim Edildi</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100">Durumu Güncelle</button>
                    </form>
                    <small class="text-muted d-block mt-2">* Not: Bu işlem genel sipariş durumunu değiştirir.</small>
                </div>
            </div>
            <a href="cemazon.php?sayfa=satici_siparisler" class="btn btn-secondary w-100">Listeye Dön</a>
        </div>

        <!-- Sipariş Ürünleri -->
        <div class="col-md-8">
            <h4 class="mb-3">Bu Siparişteki Ürünleriniz</h4>
            <div class="card shadow">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Resim</th>
                                <th>Ürün Adı</th>
                                <th>Adet</th>
                                <th>Birim Fiyat</th>
                                <th>Kazanç</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $toplam_kazanc = 0;
                            foreach ($urunler as $urun): 
                                $tutar = $urun['adet'] * $urun['birim_fiyat'];
                                $toplam_kazanc += $tutar;
                            ?>
                            <tr>
                                <td><img src="<?php echo $urun['resim_url']; ?>" width="50" class="rounded"></td>
                                <td><?php echo $urun['ad']; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $urun['adet']; ?> Adet</span></td>
                                <td><?php echo number_format($urun['birim_fiyat'], 2); ?> ₺</td>
                                <td class="fw-bold text-success"><?php echo number_format($tutar, 2); ?> ₺</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="4" class="text-end">Bu Siparişten Toplam Kazancınız:</td>
                                <td class="fw-bold text-warning"><?php echo number_format($toplam_kazanc, 2); ?> ₺</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>