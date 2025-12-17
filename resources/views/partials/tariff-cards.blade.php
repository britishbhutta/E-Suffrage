@if ($booking->payment_status == 'succeeded')
    <div class="alert alert-warning" role="alert">
        Tariff can not be changed Because Payment Has Been Received for this Tariff.
    </div>
@endif

<div class="selected-info-tariff text-muted mb-4">
    @if (!empty($selectedTariff))
    @else
        No tariff selected
    @endif
</div>

<div class="row g-2 justify-content-center wizard-cards">
    @forelse($tariffs as $tariff)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            @if ($booking->payment_status === 'succeeded')
                @if ($booking->tariff_id === $tariff->id)
                    <div class="card tariff-card tariff-card-compact selectable-card {{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'selected' : '' }}"
                        data-tariff-id="{{ $tariff->id }}" tabindex="0" role="button"
                        aria-pressed="{{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'true' : 'false' }}">
                    @else
                        <div class="card tariff-card tariff-card-compact {{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'selected' : '' }}"
                            data-tariff-id="{{ $tariff->id }}" tabindex="0" role="button"
                            aria-pressed="{{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'true' : 'false' }}">
                @endif
            @else
                <div class="card tariff-card tariff-card-compact selectable-card {{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'selected' : '' }}"
                    data-tariff-id="{{ $tariff->id }}" tabindex="0" role="button"
                    aria-pressed="{{ !empty($selectedTariff) && $selectedTariff->id === $tariff->id ? 'true' : 'false' }}">
            @endif
            <div class="card-header text-center">
                <strong>{{ $tariff->title }}</strong>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="tariff-range mb-2">{{ $tariff->description }}</p>
                <p class="tariff-note text-muted small mb-3">{{ $tariff->note }}</p>
                <div class="price-wrapper text-center mb-2">
                    <div class="price"><strong>{{ number_format($tariff->price_cents / 100, 2) }}
                            {{ $tariff->currency }}</strong></div>
                    <div class="price-underline"></div>
                </div>
                {{-- <ul class="list-unstyled mb-3 tariff-features">
                        @foreach ((array) json_decode($tariff->features ?? '[]', true) as $feature)
                            <li>✓ {{ $feature }}</li>
                        @endforeach
                    </ul> --}}
                <ul class="list-unstyled mb-3 tariff-features">
                    @foreach ($tariff->features ?? [] as $feature)
                        <li>✓ {{ $feature }}</li>
                    @endforeach
                </ul>

                <div class="mt-auto text-center">
                    <button type="button" class="btn btn-outline-dark select-btn"
                        aria-label="Select {{ $tariff->title }}">Select</button>
                </div>
            </div>
        </div>
</div>
@empty
<div class="col-12">
    <div class="alert alert-info">No tariff plans available at the moment.</div>
</div>
@endforelse
</div>

<form method="POST" action="{{ route('voting.select_tariff') }}">
    @csrf
    <input type="hidden" name="tariff" id="selectedTariffInput"
        value="{{ $selectedTariff ? $selectedTariff->id : '' }}">

    <div class="wizard-actions mt-4 d-flex justify-content-between align-items-center">
        <div class="selected-info text-muted">
            @if (!empty($selectedTariff))
                <!-- Selected: {{ $selectedTariff->title }} -->
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="termsCheckbox">
                    <label class="form-check-label" for="termsCheckbox">
                        I agree to the <a href="{{ route('t&cTariffSelection') }}" target="_blank">Terms &
                            Conditions</a>
                    </label>
                </div>
            @endif
        </div>
    </div>
    <div>
        <div class="d-flex justify-content-between mt-4">
            @php
                $prev = ($currentStep ?? 3) - 1;
                $prevUrl = $prev >= 1 ? route('voting.create.step', ['step' => $prev]) : route('voting.realized');
            @endphp

            <a href="{{ $prevUrl }}" class="btn btn-light">{{ $prev >= 1 ? 'Back' : 'Cancel' }}</a>
            <button type="submit" id="wizardNextBtn" class="btn btn-success"
                {{ empty($selectedTariff) ? 'disabled' : '' }}>Next</button>
        </div>
    </div>
</form>

<script>
    const cards = document.querySelectorAll('.selectable-card');
    const nextBtn = document.getElementById('wizardNextBtn');
    const selectedInfo = document.querySelector('.selected-info');
    const selectedInfoTariff = document.querySelector('.selected-info-tariff');
    const selectedInput = document.getElementById('selectedTariffInput');
    const termsCheckbox = document.getElementById('termsCheckbox');
    let selectedTariffId = @json($selectedTariff ? $selectedTariff->id : '');

    // Enable Next only when tariff + checkbox are valid
    function updateNextButtonState() {
        if (selectedTariffId && (!termsCheckbox || termsCheckbox.checked)) {
            nextBtn.disabled = false;
        } else {
            nextBtn.disabled = true;
        }
    }

    function setSelected(cardEl) {
        cards.forEach(c => c.classList.remove('selected'));
        cardEl.classList.add('selected');
        selectedTariffId = cardEl.getAttribute('data-tariff-id');
        const header = cardEl.querySelector('.card-header strong');
        const title = header ? header.innerText : 'Tariff';

        let isPaid = @json($booking && $booking->payment_status === 'succeeded');

        // Inject checkbox into DOM
        selectedInfo.innerHTML = `
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="termsCheckbox" ${isPaid ? 'checked' : ''}>
                <label class="form-check-label" for="termsCheckbox">
                    I agree to the <a href="{{ route('t&cTariffSelection') }}" target="_blank">Terms & Conditions</a>
                </label>
            </div>
        `;
        selectedInfoTariff.innerHTML = `
        <strong>Selected: </strong> ${title} 
        `;
        selectedInput.value = selectedTariffId;

        // Get new checkbox
        const newCheckbox = document.getElementById('termsCheckbox');

        if (isPaid) {
            nextBtn.disabled = false;
            if (newCheckbox) {
                newCheckbox.disabled = true;
            }
        } else {
            nextBtn.disabled = true;
            if (newCheckbox) {
                newCheckbox.addEventListener('change', function() {
                    nextBtn.disabled = !this.checked;
                });
            }
        }
    }

    cards.forEach(card => {
        card.addEventListener('click', function() {
            setSelected(this);
        });
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setSelected(this);
            }
        });
        const btn = card.querySelector('.select-btn');
        if (btn) {
            btn.addEventListener('click', function(ev) {
                ev.stopPropagation();
                setSelected(card);
            });
        }
    });

    // Attach listener if checkbox already in DOM
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', updateNextButtonState);
    }

    // Initialize state if already selected
    if (selectedTariffId) {
        const initialCard = [...cards].find(c => c.getAttribute('data-tariff-id') === selectedTariffId.toString());
        if (initialCard) {
            setSelected(initialCard);
        }
    } else {
        updateNextButtonState();
    }
</script>
