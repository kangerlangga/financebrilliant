<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
<style>
@media (max-width: 768px) {
    .page-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .breadcrumbs {
        padding-left: 0 !important;
        margin-left: 0 !important;
    }
}
</style>
<div class="wrapper">
    <div class="main-header">
        @include('layouts.admin.nav')
    </div>
    @include('layouts.admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="page-header">
                    <h4 class="page-title">{{ $judul }}</h4>
                    <ul class="breadcrumbs">
                        <a href="{{ route('tabungan.add') }}" class="btn btn-round text-white ml-auto fw-bold" style="background-color: #404285">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tabungan Baru
                        </a>
                    </ul>
                </div>
                <div class="row">
                    @foreach ($DataTN as $TN)
                        <div class="col-12">
                            <div class="card card-dark bg-secondary-gradient">
                                <div class="card-body skew-shadow">
                                    <h2 class="pb-4 mb-0">Rp {{ number_format($jSt[$TN->id_tabungans] ?? 0, 0, ',', '.') }}</h2>
                                    <div class="row">
                                        <div class="col-8 pr-0">
                                            <h3 class="fw-bold mb-1">{{ $TN->nama_tabungans }}</h3>
                                            <div class="text-small text-uppercase fw-bold op-8">(Tanpa Bank)</div>
                                        </div>
                                        <div class="col-4 pl-0 text-right">
                                            <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pT[$TN->id_tabungans] ?? 0, 2, ',', '.'), '0'), ',') }} %</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($DataTB as $TB)
                        <div class="col-md-6">
                            <div class="card card-dark bg-secondary-gradient">
                                <div class="card-body skew-shadow">
                                    <img src="{{ asset('assets/admin/img/Tabungan/'.$TB->logo_tabungans) }}" height="25" alt="{{ $TB->nama_tabungans }}">
                                    <h2 class="py-4 mb-0">Rp {{ number_format($jSt[$TB->id_tabungans] ?? 0, 0, ',', '.') }}</h2>
                                    <div class="row">
                                        <div class="col-8 pr-0">
                                            <h3 class="fw-bold mb-1">{{ $TB->nama_tabungans }}</h3>
                                            <div class="text-small text-uppercase fw-bold op-8">{{ $TB->rekening_tabungans }}</div>
                                        </div>
                                        <div class="col-4 pl-0 text-right">
                                            <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pT[$TB->id_tabungans] ?? 0, 2, ',', '.'), '0'), ',') }} %</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @include('layouts.admin.footer')
    </div>
</div>
@include('layouts.admin.script')
<script>
    @if(session('success'))
    Swal.fire({
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @elseif(session('error'))
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 3000
    });
    @endif
</script>
@endsection

<body>
    @yield('content')
</body>
</html>
