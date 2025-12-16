<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') die("Yetkisiz Giriş");

if (isset($_GET['id'])) {
    // Kendini silmesini engelle
    if ($_GET['id'] == $_SESSION['user_id']) {
        echo "<script>alert('Kendini silemezsin!'); window.location.href='cemazon.php?sayfa=kullanicilar';</script>";
        exit;
    }

    $sil = $db->prepare("DELETE FROM kullanicilar WHERE id = ?");
    $sil->execute([$_GET['id']]);
    
    echo "<script>alert('Kullanıcı silindi!'); window.location.href='cemazon.php?sayfa=kullanicilar';</script>";
}
?>