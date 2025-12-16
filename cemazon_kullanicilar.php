<?php
// SADECE ADMIN GİREBİLİR (Satıcı giremez!)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    die('<div class="container py-5 alert alert-danger">Yetkisiz Giriş! Sadece Adminler girebilir.</div>');
}

// Kullanıcıları Çek
$sorgu = $db->query("SELECT * FROM kullanicilar ORDER BY kayit_tarihi DESC");
$uyeler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 Kullanıcı Yönetimi</h2>
        <a href="cemazon.php?sayfa=kayit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Yeni Üye Ekle
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-Posta</th>
                            <th>Rol (Yetki)</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($uyeler as $uye): ?>
                        <tr>
                            <td><?php echo $uye['id']; ?></td>
                            <td><?php echo $uye['ad_soyad']; ?></td>
                            <td><?php echo $uye['email']; ?></td>
                            <td>
                                <?php 
                                    $renk = 'secondary';
                                    if($uye['rol'] == 'admin') $renk = 'danger';
                                    if($uye['rol'] == 'satici') $renk = 'warning';
                                ?>
                                <span class="badge bg-<?php echo $renk; ?>"><?php echo strtoupper($uye['rol']); ?></span>
                            </td>
                            <td><?php echo $uye['kayit_tarihi']; ?></td>
                            <td>
                                <a href="cemazon.php?sayfa=kullanici_duzenle&id=<?php echo $uye['id']; ?>" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-user-edit"></i> Düzenle
                                </a>
                                <a href="cemazon.php?sayfa=kullanici_sil&id=<?php echo $uye['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bu kullanıcıyı silmek istediğine emin misin?');">
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