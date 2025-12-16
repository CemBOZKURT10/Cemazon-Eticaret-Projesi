<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // Kullanıcıyı bul
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $sorgu->execute([$email]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    // Şifre doğru mu kontrol et
    if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
        // Oturumu başlat
        $_SESSION['user_id'] = $kullanici['id'];
        $_SESSION['user_ad'] = $kullanici['ad_soyad'];
        $_SESSION['rol'] = $kullanici['rol'];

        echo "<script>window.location.href='cemazon.php';</script>";
        exit;
    } else {
        echo '<div class="alert alert-danger text-center">E-posta veya şifre hatalı!</div>';
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Giriş Yap</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Giriş Yap</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Hesabın yok mu? <a href="cemazon.php?sayfa=kayit">Hemen Kayıt Ol</a>
                </div>
            </div>
        </div>
    </div>
</div>