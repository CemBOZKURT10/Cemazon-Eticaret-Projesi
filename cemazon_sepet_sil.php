<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $islem = isset($_GET['islem']) ? $_GET['islem'] : 'sil';

    if (isset($_SESSION['sepet'][$id])) {
        if ($islem == 'sil') {
            unset($_SESSION['sepet'][$id]);
        } elseif ($islem == 'azalt') {
            if ($_SESSION['sepet'][$id] > 1) {
                $_SESSION['sepet'][$id]--;
            } else {
                unset($_SESSION['sepet'][$id]);
            }
        }
    }
}

// PHP Header YERİNE JavaScript Kullanıyoruz:
echo "<script>window.location.href='cemazon.php?sayfa=sepet';</script>";
exit;
?>