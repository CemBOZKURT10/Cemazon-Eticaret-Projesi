<?php
// Güvenlik: Giriş yapılmış mı?
if (!isset($_SESSION['user_id'])) {
    // Giriş yapmadıysa giriş sayfasına at
    echo "<script>alert('Sipariş vermek için giriş yapmalısınız!'); window.location.href='cemazon.php?sayfa=giris';</script>";
    exit;
}

// Sepet boş mu?
if (!isset($_SESSION['sepet']) || count($_SESSION['sepet']) == 0) {
    echo "<script>alert('Sepetiniz boş!'); window.location.href='cemazon.php';</script>";
    exit;
}

try {
    // --- YENİ EKLENEN KISIM: ADRES GÜNCELLEME ---
    // Eğer ödeme sayfasından adres geldiyse, kullanıcının adresini güncelle
    if (isset($_POST['adres']) && !empty($_POST['adres'])) {
        $yeni_adres = htmlspecialchars($_POST['adres']);
        $guncelle = $db->prepare("UPDATE kullanicilar SET adres = ? WHERE id = ?");
        $guncelle->execute([$yeni_adres, $_SESSION['user_id']]);
    }
    // ---------------------------------------------

    // 1. Önce Sepetteki Ürünlerin Stok Kontrolü
    $ids = array_keys($_SESSION['sepet']);
    $id_listesi = implode(',', $ids);
        
    // Ürünlerin güncel stoklarını çek
    $sorgu = $db->query("SELECT id, ad, stok FROM urunler WHERE id IN ($id_listesi)");
    $urunler_db = $sorgu->fetchAll(PDO::FETCH_ASSOC);

    // Kontrol Döngüsü
    foreach ($urunler_db as $u) {
        $istenen_adet = $_SESSION['sepet'][$u['id']];
        
        if ($u['stok'] < $istenen_adet) {
            // HATA: Stok yetersiz!
            echo "<script>
                alert('Üzgünüz! " . $u['ad'] . " adlı üründen stokta sadece " . $u['stok'] . " adet kalmış. Lütfen sepetinizi güncelleyin.');
                window.location.href='cemazon.php?sayfa=sepet';
            </script>";
            exit; // İşlemi burada durdur
        }
    }

    // 1. Önce Sepetteki Toplam Tutarı Hesapla
    $ids = array_keys($_SESSION['sepet']);
    $id_listesi = implode(',', $ids);
    $sorgu = $db->query("SELECT * FROM urunler WHERE id IN ($id_listesi)");
    $urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

    $toplam_tutar = 0;
    // Fiyatları bir diziye alalım ki aşağıda tekrar sorgu atmayalım
    $urun_bilgileri = []; 

    foreach ($urunler as $urun) {
        $adet = $_SESSION['sepet'][$urun['id']];
        $toplam_tutar += $urun['fiyat'] * $adet;
        $urun_bilgileri[$urun['id']] = $urun; // Ürün bilgilerini sakla
    }

    // 2. Siparişi 'siparisler' tablosuna kaydet
    $ekle = $db->prepare("INSERT INTO siparisler (user_id, toplam_tutar) VALUES (?, ?)");
    $ekle->execute([$_SESSION['user_id'], $toplam_tutar]);
    
    // Oluşan Siparişin ID'sini al (Burası çok önemli!)
    $siparis_id = $db->lastInsertId();

    // 3. Siparişin detaylarını 'siparis_detay' tablosuna kaydet
    $detay_ekle = $db->prepare("INSERT INTO siparis_detay (siparis_id, urun_id, adet, birim_fiyat) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['sepet'] as $urun_id => $adet) {
        $fiyat = $urun_bilgileri[$urun_id]['fiyat']; // O anki fiyatı
        $detay_ekle->execute([$siparis_id, $urun_id, $adet, $fiyat]);
        
        // BONUS: Stoktan düşmek istersen burayı açabilirsin
        $db->query("UPDATE urunler SET stok = stok - $adet WHERE id = $urun_id");
    }

    // 4. Sepeti Boşalt ve Başarı Sayfasına Gönder
    unset($_SESSION['sepet']);
    header("Location: cemazon.php?sayfa=siparis_basarili&id=" . $siparis_id);
    exit;

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>