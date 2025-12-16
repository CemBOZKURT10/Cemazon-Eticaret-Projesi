<?php
// BU KISIM DOSYANIN EN TEPESİNDE OLMALI (HTML'den Önce)

// Konum değiştirme isteği gelmiş mi?
if (isset($_GET['konum_degistir'])) {
    $_SESSION['konum'] = htmlspecialchars($_GET['konum_degistir']);
    // Sayfayı temizle ki URL'de ?konum_degistir=... kalmasın
    header("Location: cemazon.php");
    exit;
}

// Mevcut konumu belirle (Eğer yoksa 'Konum Seçin' yazsın)
$mevcut_konum = isset($_SESSION['konum']) ? $_SESSION['konum'] : 'Konum Seçin';
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <!-- Sayfanın karakter setini belirler -->
    <meta charset="UTF-8">
    
    <!-- Sayfanın mobil uyumlu olmasını sağlar, viewport ayarları yapar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Sayfa favicon'u (tarayıcı sekmesindeki simge) tanımlar -->
    <link rel="icon" href="logolar/c.png" type="image/x-icon">
    
    <!-- Sayfa başlığını tanımlar -->
    <title>CEMAZON - Online Alışveriş</title>
    
    <!-- Bootstrap CSS bağlantısı: Sayfanın hızlıca şık bir tasarıma sahip olmasını sağlar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome bağlantısı: Sayfada ikonları kullanmamıza olanak tanır -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Kendi stil dosyanız: Cemazon'a özgü CSS stilini tanımlar -->
    <style>
        /* Cemazon için Özel CSS */
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #ffc107;
            --success-color: #198754;
            --danger-color: #dc3545;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        .hero-section {
            background: linear-gradient(135deg, #4a90e2 0%, #50c878 100%) !important;
            color: #ffffff !important;
            text-align: center;
            padding: 2rem;
        }
        .hero-section h1 {
            font-weight: 700;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        .hero-section p {
            font-size: 1.25rem;
            font-weight: 500;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        .product-card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .product-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--success-color);
        }
        .product-old-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .btn-add-cart {
            background-color: var(--secondary-color);
            border: none;
            color: #000;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-add-cart:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        .badge {
            font-size: 0.7rem;
        }
        footer {
            background-color: #212529 !important;
        }
        .social-links a {
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: var(--secondary-color) !important;
        }
        .cart-item {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        header h2 img {
            display: block;
            margin: 0;
            max-height: 100px;
            max-width: 100%;
        }
        header .fa-map-marker-alt {
            color: var(--secondary-color);
            font-size: 1.5rem;
        }
        header span {
            font-size: 1.2rem;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .product-card {
                margin-bottom: 1rem;
            }
            .social-links a {
                font-size: 1.5rem;
            }
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #f1f1f1;
        }
        @media (prefers-reduced-motion: reduce) {
            .product-card,
            .btn-add-cart,
            .navbar-nav .nav-link,
            .social-links a {
                transition: none;
            }
            .loading {
                animation: none;
            }
        }
        header {
            background: linear-gradient(135deg, #4a90e2 0%, #50c878 100%) !important;
            display: flex;
            align-items: center;
            padding: 0;
        }
        /* SAYFA DÜZENİ: Footer'ı en alta itmek için */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        footer {
            margin-top: auto;
        }

        /* ÜRÜN KARTLARI VE RESİMLERİ */
        .product-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Daha yumuşak gölge */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border-radius: 12px; /* Köşeleri yuvarlat */
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* RESİM BOYUTUNU SABİTLEME */
        .product-image {
            height: 250px;       /* Yükseklik sabit */
            width: 100%;         /* Genişlik full */
            object-fit: contain; /* Resim sığsın diye 'contain' yaptım, kırpılsın istersen 'cover' yap */
            background-color: #fff; /* Resim küçük kalırsa arkası beyaz olsun */
            padding: 10px;
        }
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> <style>
    /* 1. Ürün Kartı Efekti: Üstüne gelince havaya kalksın */
    .product-card {
        transition: all 0.3s ease-in-out; /* Yumuşak geçiş */
        border: none; /* Kenar çizgisini kaldır, modern olsun */
        border-radius: 15px; /* Köşeleri yuvarla */
        overflow: hidden; /* Taşan kısımları gizle */
    }
    
    .product-card:hover {
        transform: translateY(-10px); /* 10 pixel yukarı fırla */
        box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important; /* Gölgeyi büyüt */
        cursor: pointer;
    }

    /* 2. Resim Efekti: Kartın üstüne gelince resim azıcık büyüsün */
    .product-card:hover .product-image {
        transform: scale(1.05); /* %5 Büyü */
        transition: transform 0.5s ease;
    }

    /* 3. Sayfa Arkaplanı: Dümdüz beyaz olmasın, çok hafif gri olsun */
    body {
        background-color: #f8f9fa;
    }

    /* 4. Butonları Yuvarlayalım */
    .btn {
        border-radius: 50px; /* Hap şeklinde butonlar */
        padding-left: 20px;
        padding-right: 20px;
        font-weight: bold;
    }
    /* Konum butonuna tıklayınca çıkan mavi çerçeveyi kaldır */
    #konumMenu:focus {
        box-shadow: none;
        background-color: rgba(255, 255, 255, 0.1); /* Tıklayınca hafif parlasın */
    }
    #konumMenu:hover {
        border: 1px solid white !important; /* Üstüne gelince ince beyaz çerçeve */
        border-radius: 5px;
    }
</style>
</head>

<body>
    <!-- Header: Sayfanın üst kısmındaki başlık ve arama alanını içerir -->
    <header class="bg-primary text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 d-flex align-items-center">
                    <h2 class="mb-0">
                        <a class="navbar-brand fw-bold fs-3" href="cemazon.php">Cemazon</a>
                    </h2>
                    <div class="dropdown ms-2 me-3 d-none d-md-block">
                        <button class="btn btn-outline-light border-0 text-start lh-1" type="button" id="konumMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt fa-lg me-2 text-warning"></i>
                                <div>
                                    <small class="d-block text-white-50" style="font-size: 0.75rem;">Teslimat yeri:</small>
                                    <span class="fw-bold"><?php echo $mevcut_konum; ?></span>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu shadow" aria-labelledby="konumMenu">
                            <li><h6 class="dropdown-header">Şehir Seçin</h6></li>
                            <li><a class="dropdown-item" href="?konum_degistir=İstanbul"><i class="fas fa-city me-2 text-muted"></i>İstanbul</a></li>
                            <li><a class="dropdown-item" href="?konum_degistir=Ankara"><i class="fas fa-landmark me-2 text-muted"></i>Ankara</a></li>
                            <li><a class="dropdown-item" href="?konum_degistir=İzmir"><i class="fas fa-water me-2 text-muted"></i>İzmir</a></li>
                            <li><a class="dropdown-item" href="?konum_degistir=Bartın"><i class="fas fa-tree me-2 text-muted"></i>Bartın</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?konum_degistir=Diğer"><i class="fas fa-map me-2 text-muted"></i>Diğer...</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Arama alanı -->
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Ürün ara..." id="searchInput">
                        <button class="btn btn-warning" type="button" id="searchBtn">
                            <!-- Arama ikonu -->
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <!-- Giriş ve sepet butonları -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_ad']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="cemazon.php?sayfa=profil">📦 Siparişlerim & Hesabım</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if($_SESSION['rol'] == 'admin' || $_SESSION['rol'] == 'satici'): ?>
                                    <?php if($_SESSION['rol'] == 'satici'): ?>
                                        <li><a class="dropdown-item" href="cemazon.php?sayfa=satici_siparisler">📦 Sipariş Yönetimi</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="cemazon.php?sayfa=panel">Ürün Yönetimi</a></li>
                                <?php endif; ?>
                                <?php if($_SESSION['rol'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="cemazon.php?sayfa=kullanicilar">Kullanıcı Yönetimi</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="cemazon.php?sayfa=siparisler">📦 Siparişler</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="cemazon.php?sayfa=cikis">Çıkış Yap</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="cemazon.php?sayfa=giris" class="btn btn-outline-light me-2">
                            <i class="fas fa-user"></i> Giriş / Kayıt
                        </a>
                    <?php endif; ?>
                    <a href="cemazon.php?sayfa=sepet" class="btn btn-warning position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              style="display: <?php echo (isset($_SESSION['sepet']) && count($_SESSION['sepet']) > 0) ? 'block' : 'none'; ?>">
                            <?php 
                                $toplam = 0;
                                if(isset($_SESSION['sepet'])) {
                                    foreach($_SESSION['sepet'] as $adet) $toplam += $adet;
                                }
                                echo $toplam;
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation: Sayfanın üst kısmındaki gezinme menüsünü içerir -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php $kat = isset($_GET['kategori']) ? $_GET['kategori'] : ''; ?>

                    <li class="nav-item">
                        <a class="nav-link <?php echo $kat == '' ? 'active fw-bold' : ''; ?>" 
                           href="cemazon.php?sayfa=anasayfa">Tüm Ürünler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $kat == 'elektronik' ? 'active fw-bold' : ''; ?>" 
                           href="cemazon.php?sayfa=anasayfa&kategori=elektronik">Elektronik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $kat == 'giyim' ? 'active fw-bold' : ''; ?>" 
                           href="cemazon.php?sayfa=anasayfa&kategori=giyim">Giyim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $kat == 'ev' ? 'active fw-bold' : ''; ?>" 
                           href="cemazon.php?sayfa=anasayfa&kategori=ev">Ev & Yaşam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $kat == 'kitap' ? 'active fw-bold' : ''; ?>" 
                           href="cemazon.php?sayfa=anasayfa&kategori=kitap">Kitap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchInput');
            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', performSearch);
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    }
                });
            }
        });

        function performSearch() {
    // 1. Kutudaki yazıyı al
    var arananKelime = document.getElementById('searchInput').value;
    
    // 2. Eğer kutu boş değilse PHP'ye gönder
    if (arananKelime.trim().length > 0) {
        // Özel karakterleri (boşluk vs.) URL formatına çevir
        var urlUyumluKelime = encodeURIComponent(arananKelime);
        
        // Anasayfaya 'ara' parametresiyle gönder
        window.location.href = 'cemazon.php?sayfa=anasayfa&ara=' + urlUyumluKelime;
    } else {
        alert("Lütfen aranacak bir şey yazın!");
    }
}
    </script>
</body>
</html>
