@extends('admin.layout.master')

@section('title', 'Show Tariffs')

@section('content')
    <div class="pagetitle">
        <h1>All Tariffs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Tariffs</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h5 class="card-title mb-0">Tariff List</h5>
                            </div>

                            <div class="col text-end">
                                <a href="{{ route('admin.tariff.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Tariff
                                </a>
                            </div>
                        </div>


                        @if (session('success'))
                            <div class="alert alert-success auto-dismiss">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger auto-dismiss">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Note</th>
                                    <th>Description</th>
                                    <th>Features</th>
                                    <th>Price (cents)</th>
                                    <th>Currency</th>
                                    <th>Available Votes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tariffs as $index => $tariff)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tariff->title }}</td>
                                        <td>{{ $tariff->note }}</td>
                                        <td>{{ $tariff->description }}</td>
                                        <td>
                                            @if (!empty($tariff->features) && is_array($tariff->features))
                                                <ul class="mb-0">
                                                    @foreach ($tariff->features as $feature)
                                                        <li>{{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No features</span>
                                            @endif
                                        </td>

                                        <td>{{ $tariff->price_cents }}</td>
                                        <td>{{ $tariff->currency }}</td>
                                        <td>{{ $tariff->available_votes }}</td>
                                        <td class="text-wrap">
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('admin.tariff.edit', $tariff->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <form action="{{ route('admin.tariff.destroy', $tariff->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this tariff?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>



                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No tariffs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        setTimeout(function() {
            document.querySelectorAll('.auto-dismiss').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
@endsection
