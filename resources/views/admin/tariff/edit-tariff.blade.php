@extends('admin.layout.master')

@section('title', 'Edit Tariff')

@section('content')
<div class="pagetitle">
    <h1>Edit Tariff</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Tariff</h5>

                    <form action="{{ route('admin.tariff.update', $tariff->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $tariff->title }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="2">{{ $tariff->note }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $tariff->description }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Features</label>
                                <div id="features-wrapper">
                                    @foreach ($tariff->features as $feature)
                                        <div class="input-group mb-2">
                                            <input type="text" name="features[]" class="form-control" value="{{ $feature }}">
                                            <button type="button" class="btn btn-danger remove-feature">−</button>
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-success add-feature">+</button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (cents)</label>
                                <input type="number" name="price_cents" class="form-control" value="{{ $tariff->price_cents }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" name="currency" class="form-control" value="{{ $tariff->currency }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Available Votes</label>
                                <input type="number" name="available_votes" class="form-control" value="{{ $tariff->available_votes }}">
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Tariff</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('features-wrapper');

    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-feature')) {
            const html = `
                <div class="input-group mb-2">
                    <input type="text" name="features[]" class="form-control" placeholder="Enter feature">
                    <button type="button" class="btn btn-danger remove-feature">−</button>
                </div>
            `;
            wrapper.insertAdjacentHTML('beforeend', html);
        }

        if (e.target.classList.contains('remove-feature')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>

@endsection
