<?php
// Güvenlik Kontrolü
// Admin VEYA Satıcı ise geçiş izni ver, değilse durdur.
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'satici')) {
    die('<div class="container py-5 alert alert-danger">Yetkisiz Giriş! Sadece Admin ve Satıcılar girebilir.</div>');
}

// ID gelmiş mi?
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Silme sorgusu
    $sorgu = $db->prepare("DELETE FROM urunler WHERE id = ?");
    $sonuc = $sorgu->execute([$id]);

    if ($sonuc) {
        // Başarılıysa panele geri dön (JavaScript ile mesaj vererek)
        echo "<script>
                alert('Ürün başarıyla silindi!');
                window.location.href = 'cemazon.php?sayfa=panel';
              </script>";
    } else {
        echo "Silme hatası oluştu.";
    }
} else {
    // ID yoksa panele at
    header("Location: cemazon.php?sayfa=panel");
}
?>