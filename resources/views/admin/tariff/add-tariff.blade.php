@extends('admin.layout.master')

@section('title', 'Add Tariff')

@section('content')
    <div class="pagetitle">
        <h1>Add Tariff</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Add Tariff</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tariff Form</h5>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.tariff.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <!-- Title -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter title"
                                        value="{{ old('title') }}">
                                </div>

                                 <!-- Available Votes -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Available Votes</label>
                                    <input type="number" name="available_votes" class="form-control"
                                        placeholder="Available Votes" value="{{ old('available_votes') }}">
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                </div>

                                <!-- Features -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Features</label>
                                    <div id="features-wrapper">
                                        <div class="input-group mb-2">
                                            <input type="text" name="features[]" class="form-control"
                                                placeholder="Enter feature">
                                            <button type="button" class="btn btn-success add-feature">+</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price & Currency -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price (in cents)</label>
                                    <input type="number" name="price_cents" class="form-control"
                                        placeholder="e.g. 500 = $5.00" value="{{ old('price_cents') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" name="currency" class="form-control" placeholder="Currency"
                                        value="{{ old('currency') }}">
                                </div>

                                <!-- Note -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                                </div>
                                

                                <!-- Submit -->
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Add Tariff
                                    </button>

                                    <a href="{{ route('admin.tariff.index') }}" class="btn btn-secondary">
                                        Back
                                    </a>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('features-wrapper');

            wrapper.addEventListener('click', function(e) {
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
