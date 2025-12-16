<footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h5>Cemazon</h5>
                    <p>Güvenilir online alışveriş deneyimi</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                    <hr class="my-4">
                    <p>&copy; 2026 Cemazon. Tüm hakları saklıdır.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // 1. HAVALI SEPETE EKLEME FONKSİYONU
    function sepeteEkle(urunId) {
        fetch('ajax_sepet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'islem=ekle&id=' + urunId
        })
        .then(response => response.text())
        .then(yeniSayi => {
            // Sepet ikonundaki sayıyı güncelle
            let badge = document.getElementById('cart-badge');
            if(badge) {
                badge.innerText = yeniSayi;
                badge.style.display = 'block';
            }
            
            // --- BURASI DEĞİŞTİ: SweetAlert ---
            Swal.fire({
                icon: 'success',
                title: 'Harika!',
                text: 'Ürün sepetinize eklendi.',
                showConfirmButton: false, // Butona basmaya gerek kalmasın
                timer: 1500, // 1.5 saniye sonra kendiliğinden kapansın
                position: 'top-end', // Sağ üst köşede çıksın
                toast: true // Bildirim balonu şeklinde olsun
            });
            // ----------------------------------
        });
    }

    // 2. Miktar Güncelleme (Aynı kalıyor)
    function miktarGuncelle(urunId, yeniAdet) {
        fetch('ajax_sepet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'islem=guncelle&id=' + urunId + '&adet=' + yeniAdet
        })
        .then(response => response.text())
        .then(yeniSayi => {
            document.getElementById('cart-badge').innerText = yeniSayi;
            location.reload(); 
        });
    }
    
    // 3. Arama Fonksiyonu (Aynı kalıyor)
    function performSearch() {
        var arananKelime = document.getElementById('searchInput').value;
        if (arananKelime.trim().length > 0) {
            window.location.href = 'cemazon.php?sayfa=anasayfa&ara=' + encodeURIComponent(arananKelime);
        } else {
            Swal.fire('Dikkat', 'Lütfen aranacak bir kelime yazın!', 'warning');
        }
    }
    </script>
</body>
</html>