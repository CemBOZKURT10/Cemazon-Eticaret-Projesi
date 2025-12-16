<?php
// Güvenlik: Giriş yapmayan giremez
if (!isset($_SESSION['user_id'])) {
    header("Location: cemazon.php?sayfa=giris");
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Kullanıcı Bilgilerini Çek
$kullanici_sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$kullanici_sorgu->execute([$user_id]);
$kullanici = $kullanici_sorgu->fetch(PDO::FETCH_ASSOC);

// 2. Sipariş Geçmişini Çek
$siparis_sorgu = $db->prepare("SELECT * FROM siparisler WHERE user_id = ? ORDER BY siparis_tarihi DESC");
$siparis_sorgu->execute([$user_id]);
$siparisler = $siparis_sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>
                    <h4><?php echo $kullanici['ad_soyad']; ?></h4>
                    <p class="text-muted"><?php echo $kullanici['email']; ?></p>
                    <span class="badge bg-primary"><?php echo strtoupper($kullanici['rol']); ?> ÜYE</span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h3 class="mb-4">📦 Sipariş Geçmişim</h3>
            
            <?php if(count($siparisler) == 0): ?>
                <div class="alert alert-info">Henüz hiç sipariş vermediniz. <a href="cemazon.php">Alışverişe Başla</a></div>
            <?php else: ?>
            
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sipariş No</th>
                                    <th>Tarih</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Detay</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($siparisler as $siparis): ?>
                                <tr>
                                    <td>#<?php echo $siparis['id']; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($siparis['siparis_tarihi'])); ?></td>
                                    <td><?php echo number_format($siparis['toplam_tutar'], 2); ?> ₺</td>
                                    <td>
                                        <?php 
                                            $renk = 'secondary';
                                            if($siparis['durum'] == 'Hazırlanıyor') $renk = 'warning';
                                            if($siparis['durum'] == 'Kargolandı') $renk = 'info';
                                            if($siparis['durum'] == 'Teslim Edildi') $renk = 'success';
                                            if($siparis['durum'] == 'İptal') $renk = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $renk; ?>"><?php echo $siparis['durum']; ?></span>
                                    </td>
                                    <td>
                                        <a href="cemazon.php?sayfa=profil_siparis_detay&id=<?php echo $siparis['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            İncele
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>