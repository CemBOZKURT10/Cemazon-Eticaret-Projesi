<?php
// Güvenlik: Sadece Satıcı (veya Admin) girebilir
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'satici') {
    die('<div class="alert alert-danger container mt-5">Yetkisiz Giriş!</div>');
}

$satici_id = $_SESSION['user_id'];

// SQL SORGUSU:
// Siparişleri getir AMA sadece içinde benim ürünüm olanları getir.
// DISTINCT komutu ile aynı sipariş numarasını tekrar tekrar göstermeyi engelliyoruz.
$sql = "SELECT DISTINCT s.id, s.siparis_tarihi, s.durum, k.ad_soyad 
        FROM siparisler s
        JOIN siparis_detay sd ON s.id = sd.siparis_id
        JOIN urunler u ON sd.urun_id = u.id
        JOIN kullanicilar k ON s.user_id = k.id
        WHERE u.satici_id = ?
        ORDER BY s.siparis_tarihi DESC";

$sorgu = $db->prepare($sql);
$sorgu->execute([$satici_id]);
$siparisler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <h2 class="mb-4">📦 Gelen Siparişlerim</h2>
    
    <?php if(count($siparisler) == 0): ?>
        <div class="alert alert-info">Henüz ürünlerinizden sipariş veren olmamış.</div>
    <?php else: ?>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sipariş No</th>
                            <th>Müşteri</th>
                            <th>Tarih</th>
                            <th>Genel Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siparisler as $siparis): ?>
                        <tr>
                            <td>#<?php echo $siparis['id']; ?></td>
                            <td><?php echo $siparis['ad_soyad']; ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($siparis['siparis_tarihi'])); ?></td>
                            <td>
                                <?php 
                                    $renk = 'secondary';
                                    if($siparis['durum'] == 'Hazırlanıyor') $renk = 'warning';
                                    if($siparis['durum'] == 'Kargolandı') $renk = 'info';
                                    if($siparis['durum'] == 'Teslim Edildi') $renk = 'success';
                                ?>
                                <span class="badge bg-<?php echo $renk; ?>"><?php echo $siparis['durum']; ?></span>
                            </td>
                            <td>
                                <a href="cemazon.php?sayfa=satici_siparis_detay&id=<?php echo $siparis['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Siparişi Yönet
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