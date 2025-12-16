<?php
// cemazon.php - ANA YÖNETİM MERKEZİ (FİNAL DÜZELTME)

ob_start(); // Çıktı tamponlamayı başlat (Bu, header hatalarını engellemek için sihirli değnektir)
session_start();
require_once 'cemazon_db.php'; // Veritabanı bağlantısı

// --- KONUM DEĞİŞTİRME MANTIĞI ---
if (isset($_GET['konum_degistir'])) {
    $_SESSION['konum'] = htmlspecialchars($_GET['konum_degistir']);
    // Sayfayı temizle ki URL'de ?konum_degistir=... kalmasın
    header("Location: cemazon.php");
    exit;
}

// Varsayılan Konum (Eğer seçilmediyse)
$mevcut_konum = isset($_SESSION['konum']) ? $_SESSION['konum'] : 'Konum Seçin';

// URL'den sayfa bilgisini al
$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 'anasayfa';

// --- BÖLÜM 1: GÖRÜNMEZ İŞLEMLER (HTML'den Önce Çalışmalı) ---
// Bu sayfalar yönlendirme yapar, o yüzden header.php'den önce çağrılmalı.

if ($sayfa == 'cikis') {
    session_destroy();
    header("Location: cemazon.php");
    exit;
}
elseif ($sayfa == 'sepet_ekle') {
    include 'cemazon_sepet_ekle.php';
    exit; // İşlem bitince kod dursun
}
elseif ($sayfa == 'sepet_sil') {
    include 'cemazon_sepet_sil.php';
    exit;
}
elseif ($sayfa == 'sepet_bosalt') {
    unset($_SESSION['sepet']);
    header("Location: cemazon.php?sayfa=sepet");
    exit;
}
elseif ($sayfa == 'urun_sil') {
    include 'cemazon_urun_sil.php';
    exit;
}
elseif ($sayfa == 'kullanici_sil') {
    include 'cemazon_kullanici_sil.php';
    exit;
}

// --- BÖLÜM 2: GÖRÜNÜR SAYFALAR (HTML Başlıyor) ---

include 'cemazon_header.php'; // Menü ve Logo burada basılır

if ($sayfa == 'anasayfa') {
    include 'cemazon_anasayfa.php';
} elseif ($sayfa == 'sepet') {
    include 'cemazon_sepet.php';
} elseif ($sayfa == 'giris') {
    include 'cemazon_giris.php';
} elseif ($sayfa == 'kayit') {
    include 'cemazon_kayit.php';
} elseif ($sayfa == 'panel') {
    include 'cemazon_panel.php';
} elseif ($sayfa == 'urun_ekle' || $sayfa == 'urun_duzenle') {
    include 'cemazon_urun_form.php';
} elseif ($sayfa == 'kullanicilar') {
    include 'cemazon_kullanicilar.php';
} elseif ($sayfa == 'kullanici_duzenle') {
    include 'cemazon_kullanici_duzenle.php';
} elseif ($sayfa == 'siparis_onay') {
    include 'cemazon_siparis_onay.php';
} elseif ($sayfa == 'siparis_basarili') {
    include 'cemazon_siparis_basarili.php';
} elseif ($sayfa == 'siparisler') {
    include 'cemazon_siparisler.php';       // Admin Sipariş Listesi
} elseif ($sayfa == 'siparis_detay') {
    include 'cemazon_siparis_detay.php';    // Siparişin İçindekiler
} elseif ($sayfa == 'siparis_durum') {
    include 'cemazon_siparis_durum.php';    // Durum Güncelleme (Görünmez İşçi)
} elseif ($sayfa == 'profil') {
    include 'cemazon_profil.php';    // Kullanıcı Profil Sayfası
} elseif ($sayfa == 'profil_siparis_detay') {
    include 'cemazon_profil_siparis_detay.php'; // Kullanıcı sipariş detayı
} elseif ($sayfa == 'urun_detay') {
    include 'cemazon_urun_detay.php';   // Ürünün büyük resmini ve bilgisini gösteren sayfa
} elseif ($sayfa == 'satici_siparisler') {
    include 'cemazon_satici_siparisler.php';      // Satıcının sipariş listesi
} elseif ($sayfa == 'satici_siparis_detay') {
    include 'cemazon_satici_siparis_detay.php'; // Satıcının sipariş detayı
} elseif ($sayfa == 'siparis_sil') {
    include 'cemazon_siparis_sil.php';      // Sipariş silme işlemi (Görünmez işçi)
} elseif ($sayfa == 'odeme') {
    include 'cemazon_odeme.php';      // Ödeme ve Adres Ekranı
} elseif ($sayfa == 'yorum_yap') { 
    include 'cemazon_yorum_yap.php'; 
} else {
    // Eğer yukarıdaki hiçbir şarta uymazsa
    echo "<div class='container py-5 text-center text-muted'>Sayfa Yükleniyor...</div>";
}

include 'cemazon_footer.php'; // Alt kısım
ob_end_flush(); // Tamponu boşalt ve çıktıyı ver
?>