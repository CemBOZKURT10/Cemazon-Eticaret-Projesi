<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') die("Yetkisiz Giriş");

if (!isset($_GET['id'])) header("Location: cemazon.php?sayfa=kullanicilar");

$id = $_GET['id'];

// Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $rol = $_POST['rol']; // Yeni yetki seviyesi

    $guncelle = $db->prepare("UPDATE kullanicilar SET ad_soyad=?, email=?, rol=? WHERE id=?");
    $guncelle->execute([$ad, $email, $rol, $id]);

    echo "<script>alert('Kullanıcı güncellendi!'); window.location.href='cemazon.php?sayfa=kullanicilar';</script>";
}

// Mevcut bilgileri çek
$sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$sorgu->execute([$id]);
$uye = $sorgu->fetch(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Kullanıcı Düzenle</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Ad Soyad</label>
                            <input type="text" name="ad_soyad" class="form-control" value="<?php echo $uye['ad_soyad']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $uye['email']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold text-danger">Kullanıcı Rolü (Yetki)</label>
                            <select name="rol" class="form-select">
                                <option value="user" <?php echo $uye['rol']=='user'?'selected':''; ?>>User (Normal Üye)</option>
                                <option value="satici" <?php echo $uye['rol']=='satici'?'selected':''; ?>>Satıcı (Ürün Ekleyebilir)</option>
                                <option value="admin" <?php echo $uye['rol']=='admin'?'selected':''; ?>>Admin (Tam Yetki)</option>
                            </select>
                            <small class="text-muted">Dikkat: Admin yetkisi verirseniz her şeye erişebilir.</small>
                        </div>

                        <button type="submit" class="btn btn-info text-white w-100">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>