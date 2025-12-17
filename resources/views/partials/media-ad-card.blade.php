<style>
    .media-logo {
        width: 200px;
        height: auto;
        margin: 0 20px;
        display: block;
    }

    .selectable-card.selected {
        border: 2px solid #353e67;
        box-shadow: 0 0 10px rgba(26, 64, 139, 0.5);
    }
    .select-media-btn.selected {
        background-color: #353e67 !important;
        color: #fff !important;
        border-color: #353e67 !important;
    }
   
</style>

<div class="row g-2 justify-content-center wizard-cards mt-4">
    @php $mediaAdId = 1; @endphp
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card media-card media-card-compact selectable-card" data-media-id="{{ $mediaAdId }}">
            <div class="card-body d-flex flex-column">
                <img class="media-logo" src="{{ asset('images/LOGO-GIF-FV.gif') }}" alt="Media Ad">
                <p class="mb-2"><strong>Pak Vs SA Cricket Match</strong></p>
                <p class="mb-3">Time : 20:00 - 22:00</p>
                <p class="mb-3">Date : 2025-10-15</p>
                <p class="mb-3">Pakistan</p>
                <p class="mb-3">Its A T20 Match</p>
                <p class="mb-3">29 Pakistan (≈ $0.10 USD)</p>
                <div class="mt-auto text-center">
                    <button type="button" class="btn btn-outline-dark select-media-btn" aria-label="Select {{ $mediaAdId }}">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('store.media.ad') }}">
    @csrf
    <input type="hidden" name="media_ad_id" id="selectedMediaIdInput" value="{{ $mediaAdId }}">

    <div class="wizard-actions mt-4 d-flex justify-content-center align-items-center">
        <div class="selected-info-media text-muted">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="termsCheckboxMedia">
                <label class="form-check-label" for="termsCheckboxMedia">
                    I agree to the <a href="" target="_blank">Terms & Conditions</a>
                </label>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        @php
            $prev = ($currentStep ?? 3) - 1;
            $prevUrl = $prev >= 1 ? route('voting.create.step', ['step' => $prev]) : route('voting.realized');
        @endphp
        <a href="{{ $prevUrl }}" class="btn btn-light">{{ $prev >= 1 ? 'Back' : 'Cancel' }}</a>
        <button type="submit" id="wizardNextBtn" class="btn btn-success" disabled>Next</button>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    const mediaAdId = <?php echo json_encode($booking->media_ad_id); ?>;
    const mediaCards = document.querySelectorAll('.selectable-card');
    const termsCheckbox = document.getElementById('termsCheckboxMedia');
    const nextBtn = document.getElementById('wizardNextBtn');
    const selectedMediaInput = document.getElementById('selectedMediaIdInput');
    if(!mediaAdId){
        let selectedMediaId = null;

        // Initially hide the terms checkbox
        termsCheckbox.closest('.form-check').style.display = 'none';

        // Handle media card selection
        mediaCards.forEach(card => {
            card.querySelector('.select-media-btn').addEventListener('click', function() {
                this.textContent = 'Selected';
                this.disabled = true;
                selectedMediaId = card.dataset.mediaId;
                selectedMediaInput.value = selectedMediaId;

                termsCheckbox.closest('.form-check').style.display = 'block';

                nextBtn.disabled = !termsCheckbox.checked;
            });
        });

        termsCheckbox.addEventListener('change', function() {
            nextBtn.disabled = !(this.checked && selectedMediaId);
        });
    }else{
        mediaCards.forEach(card => {
        if (card.dataset.mediaId == mediaAdId) {
            card.classList.add('selected');
            const btn = card.querySelector('.select-media-btn');
            btn.textContent = 'Selected';
            btn.disabled = true;

            selectedMediaInput.value = mediaAdId;
        } else {
            const btn = card.querySelector('.select-media-btn');
            btn.disabled = true;
            btn.textContent = 'Select';
        }
    });

    termsCheckbox.closest('.form-check').style.display = 'block';
    termsCheckbox.checked = true;
    termsCheckbox.disabled = true;

    nextBtn.disabled = false;
    }
});
</script>
