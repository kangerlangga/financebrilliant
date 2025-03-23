<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.head')
</head>

@section('content')
@php
    $pKas = ($jSKas / $jSk) * 100;
    $pBCA = ($jSBCA / $jSk) * 100;
    $pBRI = ($jSBRI / $jSk) * 100;
    $pBNI = ($jSBNI / $jSk) * 100;
    $pMan = ($jSMan / $jSk) * 100;
@endphp
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
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <h2 class="pb-4 mb-0">Rp {{ number_format($jSKas, 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Tabungan Kas</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">(Tanpa Bank)</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pKas, 2, ',', '.'), '0'), ',') }} %</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <img src="{{ asset('assets/admin/img/bca.png') }}" height="25" alt="...">
                                <h2 class="py-4 mb-0">Rp {{ number_format($jSBCA, 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Bank Central Asia (BCA)</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">131-00-4444298-8</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pBCA, 2, ',', '.'), '0'), ',') }} %</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <img src="{{ asset('assets/admin/img/bri.png') }}" height="25" alt="...">
                                <h2 class="py-4 mb-0">Rp {{ number_format($jSBRI, 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Bank Rakyat Indonesia (BRI)</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">131-00-4444298-8</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pBRI, 2, ',', '.'), '0'), ',') }} %</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <img src="{{ asset('assets/admin/img/bni.png') }}" height="25" alt="...">
                                <h2 class="py-4 mb-0">Rp {{ number_format($jSBNI, 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Bank Negara Indonesia (BNI)</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">131-00-4444298-8</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pBNI, 2, ',', '.'), '0'), ',') }} %</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-dark bg-secondary-gradient">
                            <div class="card-body skew-shadow">
                                <img src="{{ asset('assets/admin/img/mandiri.png') }}" height="25" alt="...">
                                <h2 class="py-4 mb-0">Rp {{ number_format($jSMan, 0, ',', '.') }}</h2>
                                <div class="row">
                                    <div class="col-8 pr-0">
                                        <h3 class="fw-bold mb-1">Bank Mandiri</h3>
                                        <div class="text-small text-uppercase fw-bold op-8">131-00-4444298-8</div>
                                    </div>
                                    <div class="col-4 pl-0 text-right">
                                        <h3 class="fw-bold mb-1">{{ rtrim(rtrim(number_format($pMan, 2, ',', '.'), '0'), ',') }} %</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
