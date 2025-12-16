<div class="container py-5 text-center">
    <div class="card shadow-lg p-5 border-0">
        <div class="card-body">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
            <h2 class="mt-4 text-success fw-bold">Siparişiniz Alındı!</h2>
            <p class="lead">Teşekkürler <?php echo $_SESSION['user_ad']; ?>, siparişin başarıyla oluşturuldu.</p>
            
            <?php if(isset($_GET['id'])): ?>
                <div class="alert alert-light border">
                    <strong>Sipariş Numaranız:</strong> #<?php echo $_GET['id']; ?>
                </div>
            <?php endif; ?>

            <p class="text-muted">Sipariş durumunu profilinden takip edebilirsin.</p>
            
            <div class="mt-4">
                <a href="cemazon.php" class="btn btn-primary btn-lg">Alışverişe Devam Et</a>
            </div>
        </div>
    </div>
</div>