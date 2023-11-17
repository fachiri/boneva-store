@extends('admin.layouts.app')

@push('styles')
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-8">{{ $title ?? '' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ?? '' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <div id="table_config_filter" class="position-relative">
                        <input type="search" id="search-box" class="form-control ps-5" aria-controls="table_config"
                            placeholder="Search Product..." />
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                </div>
                <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                    {{-- <a href="{{ route('admin.' . $active . '.create') }}" class="btn btn-info d-flex align-items-center">
                        <i class="ti ti-plus text-white me-1 fs-5"></i> Add Product
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- notifikasi --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert"
            id="success-alert">
            <div class="d-flex gap-2 align-items-center">
                <div>
                    <span class="d-inline-flex p-1 rounded-circle border border-2 border-white mb-0">
                        <i class="fs-5 ti ti-check"></i>
                    </span>
                </div>
                <div>
                    {{ $message ?? '' }}
                </div>
            </div>
        </div>
    @endif

    @if (count($datas) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_config" class="table align-middle text-nowrap">
                        <thead class="header-item">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Verified</th>
                                <th>Alamat</th>
                                <th>Bukti</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ App\Models\User::find($result->user_id)->name }}</td>
                                    <td>
                                        <form action="{{ route('admin.order.verified', $result->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            @method('put')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $result->alamat }}</td>
                                    <td>
                                        <img src="{{ asset('uploads/catalog/image/' . $result->bukti) }}"
                                            alt="{{ $result->name }}" class="img-fluid rounded" width="100"
                                            height="100">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.' . $active . '.show', $result->id) }}"
                                            class="btn btn-sm btn-secondary">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.' . $active . '.delete', $result->id) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- <div class="card">
        <div class="card-body">
            <div class="alert alert-warning mb-0" role="alert">
                <div class="d-flex gap-2 align-items-center">
                    <span class="rounded-circle px-1 py-0 border border-2 border-warning text-light bg-warning mb-0 d-block" style="font-size: 16px;">
                        <i class="ti ti-alert-circle"></i>
                    </span>
                    <p class="mb-0">
                        No product data yet. <a href="{{ route('admin.'. $active . '.create') }}">Add</a> now.
                    </p>
                </div>
            </div>
        </div>
    </div> --}}
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>

    <script>
        dTable = $("#table_config").DataTable({
            "dom": "lrtip"
        });

        $("#search-box").keyup(function() {
            dTable.search($(this).val()).draw();
        });
    </script>
@endpush
