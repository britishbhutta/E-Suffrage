<style>
    .footer-custom{
        width: 620px;
    }
    a.btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    a.btn {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 45px;
    }
    @media (max-width: 430px) {
        .footer-custom {
            width: 320px;
        }
    }
</style>
<footer class="bg-white text-center py-4 border-top">
    <div class="container footer-custom">
        <div class="row justify-content-center g-2"> 

            <!-- Facebook -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn btn-primary w-100 rounded-pill d-flex align-items-center justify-content-center">
                    <i class="bi bi-facebook me-2"></i> Facebook
                </a>
            </div>

            <!-- X -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn btn-dark w-100 rounded-pill d-flex align-items-center justify-content-center">
                    <i class="bi bi-twitter-x me-2"></i> X
                </a>
            </div>

            <!-- WhatsApp -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn btn-success w-100 rounded-pill d-flex align-items-center justify-content-center">
                    <i class="bi bi-whatsapp me-2"></i> WhatsApp
                </a>
            </div>

            <!-- Gmail -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn btn-danger w-100 rounded-pill d-flex align-items-center justify-content-center">
                    <i class="bi bi-envelope-fill me-2"></i> Gmail
                </a>
            </div>

            <!-- LinkedIn -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn btn-info w-100 rounded-pill d-flex align-items-center justify-content-center text-white">
                    <i class="bi bi-linkedin me-2"></i> LinkedIn
                </a>
            </div>

            <!-- Viber -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn w-100 rounded-pill d-flex align-items-center justify-content-center text-white" style="background-color: #8f5db7;">
                    <i class="bi bi-telephone-fill me-2"></i> Viber
                </a>
            </div>

            <!-- Telegram -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn w-100 rounded-pill d-flex align-items-center justify-content-center text-white" style="background-color: #2ca5e0;">
                    <i class="bi bi-telegram me-2"></i> Telegram
                </a>
            </div>

            <!-- Pinterest -->
            <div class="col-6 col-md-3">
                <a href="#" class="btn w-100 rounded-pill d-flex align-items-center justify-content-center text-white" style="background-color: #e60023;">
                    <i class="bi bi-pinterest me-2"></i> Pinterest
                </a>
            </div>

        </div>

        <p class="mt-4 mb-0 text-muted small">© {{ date('Y') }} Your Company Name</p>
    </div>

    
</footer>

