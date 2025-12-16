<?php
// Form gönderildi mi kontrol et
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // Şifreyi güvenli hale getir (Hashleme)
    $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

    // Veritabanına ekle
    try {
        $sorgu = $db->prepare("INSERT INTO kullanicilar (ad_soyad, email, sifre) VALUES (?, ?, ?)");
        $sorgu->execute([$ad, $email, $sifre_hash]);
        
        echo '<div class="alert alert-success">Kayıt başarılı! <a href="cemazon.php?sayfa=giris">Giriş yapabilirsiniz.</a></div>';
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Tekrar eden email hatası
            echo '<div class="alert alert-danger">Bu e-posta zaten kayıtlı.</div>';
        } else {
            echo '<div class="alert alert-danger">Hata: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Kayıt Ol</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Ad Soyad</label>
                            <input type="text" name="ad_soyad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Zaten hesabın var mı? <a href="cemazon.php?sayfa=giris">Giriş Yap</a>
                </div>
            </div>
        </div>
    </div>
</div>