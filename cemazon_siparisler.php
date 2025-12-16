<?php
// Sadece Admin Girebilir
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') die("Yetkisiz Giriş");

// Siparişleri Çek
$sql = "SELECT siparisler.*, kullanicilar.ad_soyad 
        FROM siparisler 
        LEFT JOIN kullanicilar ON siparisler.user_id = kullanicilar.id 
        ORDER BY siparis_tarihi DESC";
$sorgu = $db->query($sql);
$siparisler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📦 Gelen Siparişler</h2>
        
        <?php if(count($siparisler) > 0): ?>
        <a href="cemazon.php?sayfa=siparis_sil&islem=hepsini_sil" 
           class="btn btn-danger"
           onclick="return confirm('DİKKAT! Tüm sipariş geçmişi ve detayları kalıcı olarak silinecek. Bu işlem geri alınamaz! Emin misiniz?');">
            <i class="fas fa-trash-alt me-2"></i>Tüm Siparişleri Temizle
        </a>
        <?php endif; ?>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Sipariş No</th>
                            <th>Müşteri</th>
                            <th>Tutar</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siparisler as $siparis): ?>
                        <tr>
                            <td>#<?php echo $siparis['id']; ?></td>
                            <td><?php echo $siparis['ad_soyad']; ?></td>
                            <td><?php echo number_format($siparis['toplam_tutar'], 2); ?> ₺</td>
                            <td><?php echo date('d.m.Y H:i', strtotime($siparis['siparis_tarihi'])); ?></td>
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
                                <a href="cemazon.php?sayfa=siparis_detay&id=<?php echo $siparis['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detay
                                </a>

                                <a href="cemazon.php?sayfa=siparis_sil&id=<?php echo $siparis['id']; ?>" 
                                   class="btn btn-danger btn-sm ms-1"
                                   onclick="return confirm('Bu siparişi silmek istediğine emin misin?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>