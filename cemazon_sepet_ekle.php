<?php
// cemazon_sepet_ekle.php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (!isset($_SESSION['sepet'])) { $_SESSION['sepet'] = []; }

    if (isset($_SESSION['sepet'][$id])) {
        $_SESSION['sepet'][$id]++;
    } else {
        $_SESSION['sepet'][$id] = 1;
    }

    // JS ile geri gönder (PHP header kullanma)
    echo "<script>window.history.back();</script>";
} else {
    echo "<script>window.location.href='cemazon.php';</script>";
}
?>