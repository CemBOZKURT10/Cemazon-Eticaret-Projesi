<?php
// ajax_sepet.php - Arka Planda Çalışan İşçi
session_start();
require_once 'cemazon_db.php'; // Veritabanı bağlantısı (Stok kontrolü için şart)

$islem = isset($_POST['islem']) ? $_POST['islem'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if (!isset($_SESSION['sepet'])) { $_SESSION['sepet'] = []; }

// 1. EKLEME İŞLEMİ
if ($islem == 'ekle' && $id > 0) {
    // Stok kontrolü yapalım
    $stok_sorgu = $db->prepare("SELECT stok FROM urunler WHERE id = ?");
    $stok_sorgu->execute([$id]);
    $stok = $stok_sorgu->fetchColumn();

    $mevcut_adet = isset($_SESSION['sepet'][$id]) ? $_SESSION['sepet'][$id] : 0;

    if (($mevcut_adet + 1) <= $stok) {
        if (isset($_SESSION['sepet'][$id])) {
            $_SESSION['sepet'][$id]++;
        } else {
            $_SESSION['sepet'][$id] = 1;
        }
    }
} 
// 2. GÜNCELLEME İŞLEMİ (Sepet Sayfası İçin)
elseif ($islem == 'guncelle' && $id > 0) {
    $adet = intval($_POST['adet']);
    
    // Stok kontrolü
    $stok_sorgu = $db->prepare("SELECT stok FROM urunler WHERE id = ?");
    $stok_sorgu->execute([$id]);
    $stok = $stok_sorgu->fetchColumn();

    if ($adet > 0) {
        // İstenen adet stoktan fazlaysa, stoğu kadar ver
        if ($adet <= $stok) {
            $_SESSION['sepet'][$id] = $adet;
        } else {
            $_SESSION['sepet'][$id] = $stok; // Maksimum stoğu ver
        }
    } else {
        // Adet 0 veya altıysa sepetten sil
        unset($_SESSION['sepet'][$id]);
    }
}

// 3. TOPLAM SAYIYI HESAPLA VE GERİ GÖNDER
$toplam = 0;
foreach($_SESSION['sepet'] as $a) { $toplam += $a; }
echo $toplam;
?>