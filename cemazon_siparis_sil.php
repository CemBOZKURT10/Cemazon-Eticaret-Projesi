<?php
// cemazon_siparis_sil.php - OTOMATİK TEMİZLİK ROBOTU


require_once 'cemazon_db.php';

// Güvenlik: Sadece Admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    die("Yetkisiz Giriş");
}

$islem = isset($_GET['islem']) ? $_GET['islem'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    if ($islem == 'hepsini_sil') {
        // --- BURASI KRİTİK NOKTA ---
        // Normalde tablolar birbirine bağlı olduğu için (Foreign Key) "TRUNCATE" komutu hata verebilir.
        // O yüzden önce güvenlik kilidini (Foreign Key Checks) geçici olarak kapatıyoruz.
        
        $db->query("SET FOREIGN_KEY_CHECKS = 0");
        
        // 1. Detayları Kökten Temizle (ID'ler sıfırlanır)
        $db->query("TRUNCATE TABLE siparis_detay");
        
        // 2. Siparişleri Kökten Temizle (ID'ler sıfırlanır)
        $db->query("TRUNCATE TABLE siparisler");
        
        // Güvenlik kilidini tekrar açıyoruz
        $db->query("SET FOREIGN_KEY_CHECKS = 1");
        
        $mesaj = "Tüm sipariş veritabanı fabrika ayarlarına döndürüldü (ID'ler sıfırlandı).";
        
    } elseif ($id > 0) {
        // Sadece tek bir siparişi sil (Burada DELETE kullanmaya devam ediyoruz)
        $sorgu = $db->prepare("DELETE FROM siparisler WHERE id = ?");
        $sorgu->execute([$id]);
        
        // Not: Tekil silmede detaylar veritabanındaki "CASCADE" ayarı sayesinde otomatik silinir.
        // Eğer veritabanında bu ayar yoksa, detayları da elle silmek gerekir:
        $db->query("DELETE FROM siparis_detay WHERE siparis_id = $id");
        
        $mesaj = "Sipariş #$id başarıyla silindi.";
    }

    // İşlem bitince geri dön
    echo "<script>
        alert('$mesaj');
        window.location.href = 'cemazon.php?sayfa=siparisler';
    </script>";

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
    // Hata olsa bile güvenlik kilidini açmayı garantiye alalım
    $db->query("SET FOREIGN_KEY_CHECKS = 1"); 
}
?>