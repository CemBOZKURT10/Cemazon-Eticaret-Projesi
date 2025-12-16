<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $urun_id = intval($_POST['urun_id']);
    $user_id = $_SESSION['user_id'];
    $puan = intval($_POST['puan']);
    $yorum = htmlspecialchars($_POST['yorum']);

    $kaydet = $db->prepare("INSERT INTO yorumlar (urun_id, user_id, puan, yorum) VALUES (?, ?, ?, ?)");
    $kaydet->execute([$urun_id, $user_id, $puan, $yorum]);

    // Geri dön
    header("Location: cemazon.php?sayfa=urun_detay&id=$urun_id");
    exit;
}
?>