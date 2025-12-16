<?php
// Giriş Kontrolü
if (!isset($_SESSION['user_id'])) { header("Location: cemazon.php?sayfa=giris"); exit; }

// Sepet Boşsa Anasayfaya At
if (!isset($_SESSION['sepet']) || count($_SESSION['sepet']) == 0) { header("Location: cemazon.php"); exit; }

// Kullanıcının Mevcut Adresini Çek (Varsa kutuya dolu gelsin)
$sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$sorgu->execute([$_SESSION['user_id']]);
$user = $sorgu->fetch(PDO::FETCH_ASSOC);

// Toplam Tutarı Hesapla
$toplam = 0;
$urun_ids = implode(',', array_keys($_SESSION['sepet']));
$sorgu_urun = $db->query("SELECT * FROM urunler WHERE id IN ($urun_ids)");
$urunler = $sorgu_urun->fetchAll(PDO::FETCH_ASSOC);

foreach($urunler as $u) {
    $toplam += $u['fiyat'] * $_SESSION['sepet'][$u['id']];
}
?>

<div class="container py-5">
    <h2 class="mb-4">💳 Ödeme ve Teslimat Bilgileri</h2>
    
    <form action="cemazon.php?sayfa=siparis_onay" method="POST">
        <div class="row">
            
            <div class="col-md-8">
                
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-map-marker-alt me-2"></i>Teslimat Adresi
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Adres Başlığı</label>
                            <input type="text" class="form-control" placeholder="Evim, İşyerim vb.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açık Adres (Kargo için önemli)</label>
                            <textarea name="adres" class="form-control" rows="3" required placeholder="Mahalle, Sokak, Bina No, Kapı No, İlçe/İL"><?php echo $user['adres']; ?></textarea>
                            <small class="text-muted">* Bu adresi güncellerseniz profilinize de kaydedilecektir.</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-credit-card me-2"></i>Kart Bilgileri (Güvenli Ödeme)
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kart Numarası</label>
                                <input type="text" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kart Üzerindeki İsim</label>
                                <input type="text" class="form-control" placeholder="Ad Soyad" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Ay</label>
                                <select class="form-select">
                                    <option>01</option><option>02</option><option>12</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Yıl</label>
                                <select class="form-select">
                                    <option>2025</option><option>2026</option><option>2027</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" placeholder="123" maxlength="3" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <i class="fab fa-cc-visa fa-2x me-2 text-primary"></i>
                                <i class="fab fa-cc-mastercard fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">Sipariş Özeti</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Ara Toplam</span>
                                <strong><?php echo number_format($toplam, 2); ?> ₺</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Kargo</span>
                                <span class="text-success">Ücretsiz</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span class="fw-bold">Toplam</span>
                                <span class="fw-bold text-primary fs-5"><?php echo number_format($toplam, 2); ?> ₺</span>
                            </li>
                        </ul>
                        
                        <button type="submit" class="btn btn-success w-100 btn-lg py-3">
                            <i class="fas fa-check-circle me-2"></i>Ödemeyi Onayla
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted"><i class="fas fa-lock"></i> 256-Bit SSL ile korunmaktadır.</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>