-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 16 Ara 2025, 15:16:23
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `cemazon_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

DROP TABLE IF EXISTS `kategoriler`;
CREATE TABLE IF NOT EXISTS `kategoriler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `ad`, `slug`) VALUES
(1, 'Elektronik', 'elektronik'),
(2, 'Giyim', 'giyim'),
(3, 'Ev & Yaşam', 'ev'),
(4, 'Kitap', 'kitap');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `rol` enum('user','admin','satici') DEFAULT 'user',
  `kayit_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  `adres` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `email`, `sifre`, `rol`, `kayit_tarihi`, `adres`) VALUES
(2, 'cem', 'cem@cemazon.com', '$2y$10$9.iMsghTDs5QAToxUAWC9OX6IAeabMVC1q.Tm0CKccbNJGNFm6DJa', 'user', '2025-12-14 19:17:28', 'atatürk cd.'),
(1, 'cem', 'admin@cemazon.com', '$2y$10$/2eiuCdiJNrkcRjzW5yQWuftMnzXGoXe.XZ0a7A8J7jzgJmHEiS2y', 'admin', '2025-12-14 19:36:52', NULL),
(3, 'ali', 'alisatici@cemazon.com', '$2y$10$GvJkqzNYKdHnPGar.V/COuH.Ls8rHHKJALbgM7yciDjH5cQKC3hGe', 'satici', '2025-12-14 20:55:22', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

DROP TABLE IF EXISTS `siparisler`;
CREATE TABLE IF NOT EXISTS `siparisler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `toplam_tutar` decimal(10,2) NOT NULL,
  `siparis_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  `durum` varchar(50) DEFAULT 'Hazırlanıyor',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `user_id`, `toplam_tutar`, `siparis_tarihi`, `durum`) VALUES
(1, 2, 115000.00, '2025-12-16 17:57:03', 'Teslim Edildi'),
(2, 2, 1299.00, '2025-12-16 17:57:29', 'Kargolandı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_detay`
--

DROP TABLE IF EXISTS `siparis_detay`;
CREATE TABLE IF NOT EXISTS `siparis_detay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `siparis_id` int NOT NULL,
  `urun_id` int NOT NULL,
  `adet` int NOT NULL,
  `birim_fiyat` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siparis_id` (`siparis_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `siparis_detay`
--

INSERT INTO `siparis_detay` (`id`, `siparis_id`, `urun_id`, `adet`, `birim_fiyat`) VALUES
(1, 1, 1, 1, 115000.00),
(2, 2, 33, 1, 1299.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

DROP TABLE IF EXISTS `urunler`;
CREATE TABLE IF NOT EXISTS `urunler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kategori_slug` varchar(50) DEFAULT NULL,
  `ad` varchar(100) NOT NULL,
  `aciklama` text,
  `fiyat` decimal(10,2) NOT NULL,
  `eski_fiyat` decimal(10,2) DEFAULT NULL,
  `resim_url` varchar(255) DEFAULT NULL,
  `stok` int DEFAULT '100',
  `satici_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`id`, `kategori_slug`, `ad`, `aciklama`, `fiyat`, `eski_fiyat`, `resim_url`, `stok`, `satici_id`) VALUES
(1, 'elektronik', 'iPhone 17 Pro', 'En yeni iPhone modeli', 115000.00, 120000.00, 'https://productimages.hepsiburada.net/s/777/375-375/110001191547050.jpg', 82, 1),
(2, 'elektronik', 'Samsung Galaxy S25 Ultra', 'Güçlü Android telefon', 74999.00, 82999.00, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/samsung/thumb/1-185_large.jpg', 99, 1),
(3, 'giyim', 'Nike Air Force', 'Spor ayakkabı', 5999.00, 6999.00, 'https://img.sportinn.com.tr/nike-air-force-1-07-erkek-sneaker-ayakkabi-cw2288-111-153736-43-B.jpg', 99, 1),
(4, 'elektronik', 'MacBook Air M4', 'Profesyonel laptop', 75000.00, 85000.00, 'https://productimages.hepsiburada.net/s/777/375-375/110000951875311.jpg', 100, 1),
(5, 'giyim', 'Levis Kot Pantolon', 'Klasik kot pantolon', 1299.00, 1699.00, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=300&fit=crop', 100, 1),
(6, 'ev', 'Kahve Makinesi', 'Otomatik kahve makinesi', 29999.00, 32999.00, 'https://cdn.evkur.com.tr/c/Product/2_fa03bg.jpg', 10, 1),
(7, 'kitap', 'Php Kitap', 'Programlama kitabı', 299.00, 499.00, 'https://www.kodlab.com/1364-thickbox_default/php.jpg', 99, 1),
(8, 'elektronik', 'JBL Bluetooth Kulaklık', 'Kablosuz kulaklık', 1599.00, 2199.00, 'https://static.ticimax.cloud/55525/uploads/urunresimleri/buyuk/cc24f4ec-f290-4661-995d-f903c0dfe5a2-d36f-4.jpg', 9, 1),
(13, 'elektronik', 'Apple iPhone 17 256 GB Siyah', 'iPhone 17: Teknolojinin Zirvesi\r\niPhone 17, şıklığı ve gücü bir araya getiriyor. Yüksek çözünürlüklü ekranı, gelişmiş kamera sistemi ve daha hızlı işlemcisi ile her anı ölümsüzleştirirken, zarif tasarımıyla dikkatleri üzerine çekiyor. Yepyeni özellikleriyle daha akıllı, daha hızlı ve daha kullanıcı dostu. Teknoloji tutkunlarının beklediği iPhone 17, sınırları zorluyor.', 77999.00, NULL, 'uploads/1765807522_iphone17.webp', 10, 1),
(15, 'elektronik', 'Apple iPad 10.Nesil 64GB ', 'iPad: Gücünü Hayal Gücünden Alır\r\niPad, ince ve hafif tasarımıyla her yerde seninle. Güçlü performansı, etkileyici ekranı ve akıllı özellikleri sayesinde hem üretkenliğini artırır hem de eğlenceyi zirveye taşır. Çiz, yaz, izle, üret — iPad ile sınır yok.', 19999.00, NULL, 'uploads/1765814830_ipad.jpeg', 5, 3),
(16, 'elektronik', 'MacBook Pro M3 Chip', 'Profesyoneller için üretilen en güçlü laptop. 16GB Ram, 512GB SSD ve uzay grisi rengiyle.', 75000.00, 85000.00, 'https://www.apple.com/newsroom/images/2023/10/apple-unveils-new-macbook-pro-featuring-m3-chips/tile/Apple-MacBook-Pro-2up-231030.jpg.landing-big_2x.jpg', 10, 1),
(17, 'elektronik', 'Sony Kablosuz Kulaklık', 'Gürültü engelleme özelliği ile dünyadan kopun. 30 saat pil ömrü.', 8500.00, NULL, 'https://m.media-amazon.com/images/I/510cs9VwjUL._AC_UF1000,1000_QL80_.jpg', 25, 1),
(18, 'giyim', 'Nike Air Jordan Kırmızı', 'Klasikleşmiş tasarım, maksimum konfor. Sokak stilini yansıt.', 6500.00, NULL, 'https://img.sportinn.com.tr/air-jordan-1-mid-erkek-basketbol-ayakkabisi-dq8426-060-113706-55-B.jpg', 5, 1),
(19, 'elektronik', 'iPhone 15 128 Gb', 'iPhone 15\r\nŞıklığı ve gücü bir araya getiren iPhone 15, günlük kullanımdan profesyonel çekimlere kadar her anında yanında. Geliştirilmiş kamerası, akıcı performansı ve zarif tasarımıyla eline aldığın anda farkını hissettirir. Teknolojiyi keyfe dönüştürmek isteyenler için ideal.', 50499.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/apple/thumb/0007-mtp43tu-a_large.jpg', 25, 1),
(20, 'elektronik', 'Xiaomi 15 Ultra', 'Üst düzey performans ve profesyonel kamera deneyimi tek cihazda. Xiaomi 15 Ultra, güçlü donanımı, çarpıcı fotoğraf kalitesi ve premium tasarımıyla beklentilerin ötesine geçiyor. Elinize aldığınız anda fark yaratmak isteyenler için gerçek bir amiral gemisi.', 79999.00, NULL, 'https://i02.appmifile.com/605_item_tr/09/12/2025/c1a5fd810b96f08f757f46de01ac95a0.png?thumb=1&f=webp&q=85', 10, 1),
(21, 'elektronik', 'Samsung Galaxy Z Fold7', 'Telefon ve tablet deneyimini tek cihazda buluşturan Galaxy Z Fold7, geniş katlanabilir ekranı ve güçlü performansıyla sınırları ortadan kaldırıyor. Çoklu görev, eğlence ve üretkenlik hiç bu kadar keyifli olmamıştı. Geleceğin teknolojisi avucunda', 117499.00, NULL, 'https://images.samsung.com/is/image/samsung/assets/tr/f2507/offer/Q7_Global_Color_Group_KV_PC_944x510.jpg?imbypass=true', 10, 1),
(22, 'elektronik', 'Samsung Galaxy Tab S10 Fe', 'Hafif, şık ve güçlü. Galaxy Tab S10 FE, canlı ekranı ve akıcı performansıyla hem eğlence hem de iş için ideal. Not almaktan dizi izlemeye kadar her anına eşlik eden, keyifli bir tablet deneyimi sunar.', 19699.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/samsung/thumb/150723-1_large.jpg', 25, 1),
(23, 'elektronik', 'MSI KATANA', 'Gücünü hissettiren performans, keskin bir tasarım. MSI Katana, yüksek oyun performansı ve hızlı tepkileriyle rekabeti senin lehine çevirir. Oyun ve yoğun işler için tasarlanan bu laptop, her hamlede kontrolü eline almanı sağlar.', 81649.00, NULL, 'https://img-itopya.mncdn.com/cdn/1000/katana-17-hx-b14wfk-252xtr-6621f5.png', 15, 1),
(24, 'elektronik', 'ASUS TUF GAMING A15', 'Dayanıklılığı ve gücü bir arada sunan ASUS TUF Gaming A15, akıcı oyun performansı ve sağlam yapısıyla sınırları zorlar. Uzun oyun maratonları ve yoğun görevler için güvenilir, güçlü ve her an hazır.', 41976.00, NULL, 'https://img-itopya.mncdn.com/cdn/1000/1-b0ae97.png', 25, 1),
(25, 'elektronik', 'HP Victus', 'Şık tasarımı ve güçlü performansıyla öne çıkan HP Victus, hem oyun hem de günlük kullanım için ideal. Akıcı grafikler, hızlı tepkiler ve dengeli gücüyle keyifli bir deneyim sunar. Oynamaya hazır, her an yanında.', 52999.00, NULL, 'https://assets.mmsrg.com/isr/166325/c1/-/ASSET_MMS_159125106?x=536&y=402&format=jpg&quality=80&sp=yes&strip=yes&trim&ex=536&ey=402&align=center&resizesource&unsharp=1.5x1+0.7+0.02&cox=0&coy=0&cdx=536&cdy=402', 10, 1),
(26, 'elektronik', 'Apple Watch Ultra 3', 'Zorlu koşullar için tasarlandı, günlük hayatta fark yaratır. Apple Watch Ultra 3; güçlü yapısı, gelişmiş sağlık ve spor özellikleriyle her adımda seninle. Macerayı, performansı ve şıklığı bileğinde taşımak isteyenler için.', 56999.00, NULL, 'https://productimages.hepsiburada.net/s/777/424-600/110001200140311.jpg/format:webp', 40, 1),
(27, 'elektronik', 'Samsung Galaxy Watch8', 'Şıklık ve teknoloji bileğinizde buluşuyor. Galaxy Watch8, sağlık takibi, akıllı bildirimler ve uzun pil ömrü ile günlük yaşamınızı kolaylaştırır. Tarzınızı yansıtan bir akıllı saat deneyimi.', 17999.00, NULL, 'https://images.samsung.com/is/image/samsung/p6pim/tr/f2507/gallery/tr-galaxy-watch8-classic-l500-sm-l500nzkatur-547652247?$Q90_1920_1280_F_PNG$', 20, 1),
(28, 'elektronik', 'Canon Eos 2000D', 'Anı yakalamanın tam zamanı! Canon EOS 2000D, net ve canlı fotoğraflarıyla hem yeni başlayanlar hem de hobi fotoğrafçıları için ideal. Yaratıcılığınızı özgürce keşfedin ve her karede fark yaratın.', 19499.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/canon/thumb/97845-1_large.jpg', 5, 1),
(29, 'elektronik', 'DJI Mini 4 Pro', 'Gökyüzünü keşfetmenin en keyifli yolu! DJI Mini 4 Pro, hafif tasarımı, güçlü kamerası ve akıllı uçuş özellikleriyle profesyonel çekimler yapmanı sağlar. Her anı havadan yakala, yaratıcılığını özgür bırak.', 52999.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/dji/thumb/141500-3_large.jpg', 10, 1),
(30, 'elektronik', 'LG 65QNED70 65 inç 165 cm 4K Smart TV', 'Evinizde sinema keyfi! LG 65QNED70, canlı renkleri, keskin 4K görüntüsü ve akıllı özellikleriyle her sahneyi gerçek gibi hissettirir. Büyük ekran, büyük heyecan, büyük deneyim', 46399.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/lg/thumb/65qned70a6a_large.jpg', 10, 1),
(31, 'elektronik', 'LG OLEDB56 65 inç 165 cm 4K OLED Smart TV', 'Gerçek siyahlar, canlı renkler, büyüleyici detaylar. LG OLED B56, 65 inç ekranıyla her sahneyi sinema kalitesinde sunar. Akıllı özellikleriyle eğlence ve performans elinizin altında.', 89999.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/lg/thumb/oled65b56la_large.jpg', 5, 1),
(32, 'elektronik', 'TCL 50V6CGTV 50inc 127 cm 4K UHD Google Smart TV', 'Eğlenceyi akıllı hale getirin! TCL 50V6CGTV, canlı 4K görüntüsü ve Google TV özellikleriyle film, dizi ve oyun deneyiminizi üst seviyeye taşır. Kompakt boyut, büyük keyif', 18499.00, NULL, 'https://cdn.vatanbilgisayar.com/Upload/PRODUCT/tcl/thumb/155040-1_large.jpg', 15, 1),
(33, 'giyim', 'Erkek Jean Ceket', 'Sadeliğin özen ve kaliteyle buluştuğu Mavi Edition koleksiyonundan Oakland Mavi Edition Koyu Antrasit Jean Ceket. Astarlı, iki adet yan cep.Mavi Edition Koleksiyonu, yalın çizgileri ve kendine güvenen tarzıyla günümüz moda dünyasında rafine şıklığın ifadesi olarak öne çıkıyor..Pamuk içeren ürünlerimizi tercih ederek Better Cotton misyonuna yaptığımız yatırımı destekliyorsunuz. Bu ürün, kütle denge modeli aracılığıyla tedarik edildiği için Better Cotton içermeyebilir.', 1299.00, 2299.00, 'https://sky-static.mavi.com/mnresize/820/1162/0110719-90841_image_2.jpg', 29, 3),
(34, 'giyim', 'Erkek Siyah Suni Deri Ceket', 'Cemazon\'ın dış giyim koleksiyonundan Siyah Suni Deri Ceket. İki adet fermuarlı yan cep, bir adet iç cep.', 1499.00, 2599.00, 'https://sky-static.mavi.com/mnresize/820/1162/0110635-71379_image_1.jpg', 20, 3),
(35, 'giyim', 'Erkek Jean Pantolon', 'Cemazon Black koleksiyonundan Martin Mavi Black Koyu Puslu Mavi Jean Pantolon. Basende rahat, düz paça.', 899.00, 1259.00, 'https://sky-static.mavi.com/mnresize/820/1162/0037890982_image_3.jpg', 20, 3),
(36, 'giyim', 'Erkek Jean Pantolon', 'Skinny. Dar paça. Vücuda yakın, saran kesim. Hareket kolaylığı ve esneklik ile eşleşen giyim konforu. Bu ürün aynı zamanda Mavi nin All Blue koleksiyonunun bir parçasıdır. Bu ürünün üretiminde geri dönüştürülmüş polyester kullanılmıştır.', 999.00, 1699.00, 'https://sky-static.mavi.com/mnresize/820/1162/001070-90983_image_1.jpg', 15, 3),
(37, 'giyim', 'Erkek Jean Pantolon', 'Black Antrasit Jean Pantolon. Tapered fit. Dar kesim. Ayakkabı seçimini öne çıkaran daha ince kesim, daralan, kısa paça.', 799.00, 1499.00, 'https://sky-static.mavi.com/mnresize/820/1162/0081089340_image_4.jpg', 10, 3),
(38, 'giyim', 'Erkek Beyaz Gömlek', 'Cemazon\'ın erkek koleksiyonundan Beyaz Gömlek.', 599.00, 1499.00, 'https://sky-static.mavi.com/mnresize/820/1162/0211518-620_image_2.jpg', 20, 3),
(39, 'giyim', 'Erkek Siyah Gömlek', 'Cemazon\'ın erkek koleksiyonundan M Logo Nakışlı Siyah Gömlek. Düğmeli gömlek yaka.', 399.00, 1199.00, 'https://sky-static.mavi.com/mnresize/820/1162/0211192-900_image_3.jpg', 35, 3),
(40, 'giyim', 'Erkek Kapüşonlu Siyah Şişme Mont', 'Cemazon\'ın dış giyim koleksiyonundan Kapüşonlu Siyah Şişme Mont. İki adet fermuarlı yan cep, sabit kapüşon, bir adet iç cep.', 1599.00, 2799.00, 'https://sky-static.mavi.com/mnresize/820/1162/0110360-900_image_2.jpg', 10, 3),
(41, 'giyim', 'Kadın Antrasit Pantolon', 'Kadın koleksiyonundan Antrasit Pantolon.', 799.00, 1599.00, 'https://sky-static.mavi.com/mnresize/820/1162/1011028-80023_image_1.jpg', 20, 3),
(42, 'giyim', 'Kadın Su İtici Beli Kemerli Lacivert Parka', 'Dış giyim koleksiyonundan Su İtici Beli Kemerli Lacivert Parka. İki adet çıt çıtlı kapaklı ön cep, sabit kapüşon, çıkabilir kemerli.', 2299.00, 3499.00, 'https://sky-static.mavi.com/mnresize/820/1162/1110703-83699_image_2.jpg', 10, 3),
(43, 'giyim', 'Kadın Su İtici Kapüşonlu Siyah Şişme Mont', 'Dış giyim koleksiyonundan Su İtici Kapüşonlu Siyah Şişme Mont. İki adet çıt çıtlı yan cep, sabit kapüşon, içten stoperli iple ayarlanabilir etek.', 1999.00, 2999.00, 'https://sky-static.mavi.com/mnresize/820/1162/1110445-900_image_3.jpg', 15, 3),
(44, 'giyim', 'Kadın Baklava Desenli Gri Kazak', 'Kadın koleksiyonundan Baklava Desenli Gri Kazak.', 1399.00, 1799.00, 'https://sky-static.mavi.com/mnresize/820/1162/1710826-87829_image_1.jpg', 10, 3),
(45, 'giyim', 'Kadın Kapüşonlu Lacivert Sweatshirt', 'Kadın koleksiyonundan Kapüşonlu Lacivert Sweatshirt. Şardonsuz, kapüşonlu.', 759.00, 1199.00, 'https://sky-static.mavi.com/mnresize/820/1162/1S10362-87518_image_1.jpg', 10, 3),
(46, 'giyim', 'Kadın Bordo Basic Sweatshirt', 'Kadın koleksiyonundan Bisiklet Yaka Bordo Basic Sweatshirt. Şardonlu.', 799.00, 1099.00, 'https://sky-static.mavi.com/mnresize/820/1162/1610198-70415_image_2.jpg', 10, 3),
(47, 'ev', 'Estella Koltuk Takımı', 'Kumaş İsmi: Nora Krem\r\nKumaş Türü: Örme Kumaş\r\nAyak Rengi: Sarı', 49999.00, NULL, 'https://www.bellona.com.tr/idea/kc/78/myassets/products/395/estella-koltuk-takimi-pati-dostu-kumas-01.jpg?revision=1765543698', 7, 1),
(48, 'ev', 'Seul Köşe Koltuk - Krem', 'Kumaş İsmi: Moyei Krem\r\nKumaş Türü: Dokuma Kumaş', 58299.00, NULL, 'https://www.bellona.com.tr/idea/kc/78/myassets/products/532/seul-krem-l-koltuk-takimi-01.jpg?revision=1765527216', 5, 1),
(49, 'elektronik', 'Boheems Yemek Odası Takımı', 'Kumaş Türü: Dokuma Kumaş\r\nKumaş İsmi: Yedi Tepe Krem\r\nAyak Rengi: Ceviz', 58000.00, NULL, 'https://www.bellona.com.tr/idea/kc/78/myassets/products/489/1-boheems-yemek-odasi-takimi-14.jpg?revision=1763468058', 3, 1),
(50, 'ev', 'Velda Salıncak', 'Salıncak', 29999.00, NULL, 'https://www.bellona.com.tr/idea/kc/78/myassets/products/346/volde-bahce-mobilyasi-04.jpg?revision=1765537642', 10, 1),
(51, 'ev', 'Allah Muhammed Yazılı Ahşap & Pleksi Duvar Dekoru', '', 900.00, NULL, 'https://productimages.hepsiburada.net/s/777/424-600/110001325789422.jpg/format:webp', 10, 1),
(52, 'ev', 'Gold Metal Çerçeveli 180*70 cm Ayaklı Oval Boy Aynası', 'Ayna', 1500.00, NULL, 'https://productimages.hepsiburada.net/s/51/424-600/11068563357746.jpg/format:webp', 10, 1),
(53, 'ev', 'Metal Ayaklı Çalışma Masası', 'Çalışma masası', 2000.00, NULL, 'https://productimages.hepsiburada.net/s/777/848-1200/110001120061687.jpg/format:webp', 12, 1),
(54, 'ev', '3 Lü Avize', 'Avize', 1000.00, NULL, 'https://productimages.hepsiburada.net/s/777/424-600/110000759446368.jpg/format:webp', 15, 1),
(55, 'ev', '4 Seviyeli Raf', 'Raf', 4500.00, NULL, 'https://m.media-amazon.com/images/I/71F1YwnahHL._AC_SL1500_.jpg', 10, 1),
(56, 'ev', 'Kütahya Porselen', 'Renk	Krem\r\nMalzeme	Porselen\r\nMarka	Kütahya Porselen\r\nDesen	Düz\r\nKoleksiyon Adı	Tüm Mevsimler', 1599.00, NULL, 'https://m.media-amazon.com/images/I/61v54i3BXbL._AC_SL1500_.jpg', 10, 1),
(57, 'ev', ' Kütahya Porselen Topkapı Fincan Takımı', 'Fincan Takımı', 599.00, NULL, 'https://m.media-amazon.com/images/I/71sy6odwr9L._AC_SL1500_.jpg', 15, 1),
(58, 'ev', 'Korkmaz Perla Çelik Çeyiz Seti', 'Korkmaz Perla Çelik Çeyiz Seti', 6499.00, NULL, 'https://productimages.hepsiburada.net/s/100/960-1280/110000043492807.jpg', 15, 1),
(59, 'kitap', 'Bir İdam Mahkumunun Son Günü', '', 78.00, 120.00, 'https://cdn.bkmkitap.com/bir-idam-mahkumunun-son-gunu-57569-12243410-57-B.jpg', 100, 1),
(60, 'kitap', '1984', '', 100.00, NULL, 'https://cdn.bkmkitap.com/1984-13999958-64-B.jpg', 1000, 1),
(61, 'kitap', 'Suç ve Ceza', '', 150.00, 250.00, 'https://cdn.bkmkitap.com/suc-ve-ceza-31170-13634260-31-B.png', 800, 1),
(62, 'kitap', 'Küçük Prens', '', 74.00, NULL, 'https://cdn.bkmkitap.com/kucuk-prens-138332-13906659-13-B.jpg', 150, 1),
(63, 'kitap', 'Romeo ve Juliet', '', 76.00, 150.00, 'https://cdn.bkmkitap.com/romeo-ve-juliet-31158-11545267-31-B.png', 100, 1),
(64, 'kitap', 'Nutuk', 'Yazar: Mustafa Kemal Atatürk', 200.00, 220.00, 'https://i.dr.com.tr/cache/500x400-0/originals/0002023838001-1.jpg', 100, 1),
(65, 'kitap', 'Tutunamayanlar', 'Yazar: Oğuz Atay', 440.00, 500.00, 'https://i.dr.com.tr/cache/600x600-0/originals/0000000061424-1.jpg', 100, 1),
(66, 'kitap', 'Sefiller', 'Yazar: Victor Hugo', 450.00, 775.00, 'https://i.dr.com.tr/cache/600x600-0/originals/0000000651687-1.jpg', 50, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

DROP TABLE IF EXISTS `yorumlar`;
CREATE TABLE IF NOT EXISTS `yorumlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `urun_id` int NOT NULL,
  `user_id` int NOT NULL,
  `puan` int NOT NULL,
  `yorum` text,
  `tarih` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `urun_id`, `user_id`, `puan`, `yorum`, `tarih`) VALUES
(1, 1, 2, 5, 'gerçekten harika bir ürün satıcının ilgisi ve alakasıda çok iyiydi cemazonu her zaman tercih edeceğim :)', '2025-12-15 23:24:18');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
