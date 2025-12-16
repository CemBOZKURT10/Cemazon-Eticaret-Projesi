<?php
if (!isset($_SESSION['user_id'])) header("Location: cemazon.php");

$siparis_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Güvenlik: Başkasının siparişine bakmasın diye user_id kontrolü yapıyoruz!
$sorgu = $db->prepare("SELECT * FROM siparisler WHERE id = ? AND user_id = ?");
$sorgu->execute([$siparis_id, $user_id]);
$siparis = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$siparis) {
    die('<div class="container py-5 alert alert-danger">Sipariş bulunamadı veya size ait değil!</div>');
}

// Ürünleri Çek
$sorgu2 = $db->prepare("SELECT siparis_detay.*, urunler.ad, urunler.resim_url FROM siparis_detay LEFT JOIN urunler ON siparis_detay.urun_id = urunler.id WHERE siparis_id = ?");
$sorgu2->execute([$siparis_id]);
$urunler = $sorgu2->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Sipariş Detayı #<?php echo $siparis_id; ?></h3>
        <a href="cemazon.php?sayfa=profil" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>

    <div class="card shadow mb-4 border-<?php echo ($siparis['durum']=='Teslim Edildi'?'success':'warning'); ?>">
        <div class="card-body">
            <h5 class="card-title">Sipariş Durumu: <strong><?php echo $siparis['durum']; ?></strong></h5>
            <p class="card-text">Toplam Tutar: <strong><?php echo number_format($siparis['toplam_tutar'], 2); ?> ₺</strong></p>
            <p class="text-muted">Sipariş Tarihi: <?php echo $siparis['siparis_tarihi']; ?></p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">Aldığınız Ürünler</div>
        <ul class="list-group list-group-flush">
            <?php foreach($urunler as $urun): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="<?php echo $urun['resim_url']; ?>" width="60" height="60" style="object-fit:cover" class="rounded me-3">
                    <div>
                        <h6 class="mb-0"><?php echo $urun['ad']; ?></h6>
                        <small class="text-muted"><?php echo $urun['adet']; ?> Adet x <?php echo number_format($urun['birim_fiyat'], 2); ?> ₺</small>
                    </div>
                </div>
                <span class="fw-bold"><?php echo number_format($urun['adet'] * $urun['birim_fiyat'], 2); ?> ₺</span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>